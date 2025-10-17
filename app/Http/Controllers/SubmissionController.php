<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Notifications\SubmissionReceived;
use App\Notifications\SubmissionStatusChanged;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class SubmissionController extends Controller
{
    protected $submissionService;

    protected $programs = [
        'informatique' => 'Licence Informatique et Entrepreunariat',
        'mecanique_agriculture' => 'Licence Mécanique Option Agriculture',
        'mecanique_mine' => 'Licence Mécanique Option Mine',
        'energie' => 'Licence Énergie Renouvelable',
        'master' => 'Master Informatique Option Intelligence Artificielle',
    ];

    // Configuration des années d'admission
    protected $admissionYears = [
        'first' => [
            'name' => 'Première Année',
            'required_docs' => ['photo', 'bac_attestation', 'motivation_letter', 'reports'],
            'additional_fields' => []
        ],
        'second' => [
            'name' => 'Deuxième Année',
            'required_docs' => ['photo', 'transcripts', 'motivation_letter'],
            'additional_fields' => ['previous_school', 'previous_program', 'year_completed']
        ]
    ];

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    /**
     * Affiche le formulaire de soumission
     */
    public function create()
    {
        $userData = null;

        // Si l'utilisateur est connecté, récupérer ses informations
        if (auth()->check()) {
            $user = auth()->user();

            // Gestion du nom complet si first_name ou last_name n'existent pas
            $firstName = $user->first_name ?? '';
            $lastName = $user->last_name ?? '';

            // Si le nom complet existe mais pas first_name/last_name
            if (empty($firstName) && empty($lastName) && !empty($user->name)) {
                $nameParts = explode(' ', $user->name, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';
            }

            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? $user->telephone ?? $user->mobile ?? '',
            ];

            // Log pour le débogage
            Log::info('User data for prefill:', $userData);
        }

        return view('submission', [
            'programs' => $this->programs,
            'admissionYears' => $this->admissionYears,
            'userData' => $userData
        ]);
    }

    /**
     * Traite la soumission du formulaire
     */
    public function store(Request $request)
    {
        $admissionYear = $request->input('admission_year', 'first');

        // Validation spécifique selon l'année d'admission
        $validator = Validator::make(
            $request->all(),
            $this->getValidationRules($admissionYear),
            $this->getValidationMessages($admissionYear)
        );

        if ($validator->fails()) {
            Log::error('Validation failed', [
                'admission_year' => $admissionYear,
                'errors' => $validator->errors()->all()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Veuillez corriger les erreurs dans le formulaire'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $submissionId = 'SUB-' . Str::upper(Str::random(8));

            // Stockage des fichiers selon l'année d'admission
            $filePaths = $this->submissionService->storeFiles($request, $submissionId, $admissionYear);
            if (!$filePaths) {
                throw new \Exception("Erreur lors du stockage des fichiers");
            }

            // Données de base communes
            $submissionData = [
                'submission_id' => $submissionId,
                'admission_year' => $admissionYear,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'program' => $request->program,
                'photo_path' => $filePaths['photo_path'] ?? null,
                'motivation_letter_path' => $filePaths['motivation_letter_path'] ?? null,
                'status' => 'pending',
                'admin_notes' => null,
            ];

            // Champs spécifiques selon l'année d'admission
            if ($admissionYear === 'first') {
                $submissionData['bac_attestation_path'] = $filePaths['bac_attestation_path'] ?? null;
                $submissionData['report_paths'] = json_encode($filePaths['report_paths'] ?? []);
            } else {
                $submissionData['previous_school'] = $request->previous_school;
                $submissionData['previous_program'] = $request->previous_program;
                $submissionData['year_completed'] = $request->year_completed;
                $submissionData['transcript_paths'] = json_encode($filePaths['transcript_paths'] ?? []);
            }

            // Documents supplémentaires (communs aux deux années)
            $submissionData['additional_doc_paths'] = json_encode($filePaths['additional_doc_paths'] ?? []);

            // Création de la soumission
            $submission = Submission::create($submissionData);

            DB::commit();

            // Envoi des notifications
            $this->sendNotifications($submission);

            Log::info('Submission created successfully', [
                'submission_id' => $submissionId,
                'admission_year' => $admissionYear
            ]);

            return response()->json([
                'success' => true,
                'submission_id' => $submissionId,
                'redirect_url' => route('submission.success', $submissionId),
                'message' => 'Dossier soumis avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur soumission: " . $e->getMessage(), [
                'admission_year' => $admissionYear,
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['photo', 'bac_attestation', 'motivation_letter', 'reports', 'transcripts', 'additional_docs'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la soumission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche la page de succès après soumission
     */
    public function success($submissionId)
    {
        $submission = Submission::where('submission_id', $submissionId)
            ->firstOrFail();

        return view('success', compact('submission'));
    }

    /**
     * Liste toutes les soumissions (admin) avec filtrage par année
     */
    public function index(Request $request)
    {
        $query = Submission::latest();

        // Filtrage par année d'admission
        if ($request->has('admission_year') && $request->admission_year) {
            $query->where('admission_year', $request->admission_year);
        }

        // Filtrage par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtrage par programme
        if ($request->has('program') && $request->program) {
            $query->where('program', $request->program);
        }

        // Recherche par nom, email ou ID de soumission
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('submission_id', 'LIKE', "%{$search}%");
            });
        }

        $submissions = $query->paginate(15)->appends($request->query());

        // Réponse AJAX (JSON)
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.submissions.partials.rows', compact('submissions'))->render();

            return response()->json([
                'html' => $html,
                'display' => $submissions->count(),
                'total' => $submissions->total(),
                'from' => $submissions->firstItem(),
                'to' => $submissions->lastItem(),
            ]);
        }

        // Réponse classique (HTML)
        return view('admin.submissions.index', [
            'submissions' => $submissions,
            'programs' => $this->programs,
            'admissionYears' => $this->admissionYears
        ]);
    }

    /**
     * Affiche une soumission spécifique (admin)
     */
    public function show(Submission $submission)
    {
        // Décoder les chemins de fichiers selon l'année d'admission
        if ($submission->admission_year === 'first') {
            $submission->report_paths = json_decode($submission->report_paths);
        } else {
            $submission->transcript_paths = json_decode($submission->transcript_paths);
        }

        $submission->additional_doc_paths = json_decode($submission->additional_doc_paths);

        return view('admin.submissions.show', compact('submission'));
    }

    /**
     * Met à jour le statut d'une soumission (admin)
     */
    public function updateStatus(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,under_review',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $submission->update($validated);

        try {
            $submission->notify(new SubmissionStatusChanged($submission));
        } catch (\Exception $e) {
            Log::error("Notification Error: " . $e->getMessage(), [
                'submission_id' => $submission->id
            ]);
        }

        return redirect()->route('admin.submissions.show', $submission->id)
            ->with('success', 'Statut de la soumission mis à jour avec succès.');
    }

    /**
     * Télécharge un fichier spécifique
     */
    public function downloadFile(Submission $submission, $fileType, $index = null)
    {
        try {
            $path = null;
            $filename = null;

            switch ($fileType) {
                case 'photo':
                    $path = $submission->photo_path;
                    $filename = 'photo_identite_' . $submission->last_name . '.' . pathinfo($path, PATHINFO_EXTENSION);
                    break;
                case 'bac':
                    if ($submission->admission_year !== 'first') {
                        abort(404, 'Document non applicable pour cette année d\'admission');
                    }
                    $path = $submission->bac_attestation_path;
                    $filename = 'attestation_bac_' . $submission->last_name . '.pdf';
                    break;
                case 'transcript':
                    if ($submission->admission_year !== 'second') {
                        abort(404, 'Document non applicable pour cette année d\'admission');
                    }
                    $transcripts = json_decode($submission->transcript_paths);
                    $path = $transcripts[$index] ?? null;
                    $filename = 'releve_notes_' . ($index + 1) . '_' . $submission->last_name . '.' . pathinfo($path, PATHINFO_EXTENSION);
                    break;
                case 'letter':
                    $path = $submission->motivation_letter_path;
                    $filename = 'lettre_motivation_' . $submission->last_name . '.' . pathinfo($path, PATHINFO_EXTENSION);
                    break;
                case 'report':
                    if ($submission->admission_year !== 'first') {
                        abort(404, 'Document non applicable pour cette année d\'admission');
                    }
                    $reports = json_decode($submission->report_paths);
                    $path = $reports[$index] ?? null;
                    $filename = 'bulletin_' . ($index + 1) . '_' . $submission->last_name . '.' . pathinfo($path, PATHINFO_EXTENSION);
                    break;
                case 'additional':
                    $additional = json_decode($submission->additional_doc_paths);
                    $path = $additional[$index] ?? null;
                    $filename = 'document_' . ($index + 1) . '_' . $submission->last_name . '.' . pathinfo($path, PATHINFO_EXTENSION);
                    break;
                default:
                    abort(404);
            }

            if (!$path || !Storage::disk('public')->exists($path)) {
                abort(404, 'Fichier non trouvé');
            }

            return Storage::disk('public')->download($path, $filename);

        } catch (\Exception $e) {
            Log::error("Download Error: " . $e->getMessage(), [
                'submission_id' => $submission->id,
                'file_type' => $fileType,
                'index' => $index,
                'admission_year' => $submission->admission_year
            ]);
            abort(500, 'Erreur lors du téléchargement du fichier');
        }
    }

    /**
     * Supprime une soumission (admin)
     */
    public function destroy(Submission $submission)
    {
        try {
            $this->submissionService->deleteSubmissionFiles($submission);
            $submission->delete();

            return redirect()->route('admin.submissions.index')
                ->with('success', 'Soumission supprimée avec succès.');

        } catch (\Exception $e) {
            Log::error("Delete Error: " . $e->getMessage(), [
                'submission_id' => $submission->id
            ]);
            return back()->with('error', 'Erreur lors de la suppression de la soumission.');
        }
    }

    /**
     * Exporte les soumissions au format CSV
     */
    public function export(Request $request)
    {
        $query = Submission::query();

        // Appliquer les mêmes filtres que pour l'index
        if ($request->has('admission_year') && $request->admission_year) {
            $query->where('admission_year', $request->admission_year);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('program') && $request->program) {
            $query->where('program', $request->program);
        }

        $submissions = $query->get();
        $fileName = 'soumissions_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'ID', 'Année d\'admission', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Programme',
            'Statut', 'Date de soumission', 'Établissement précédent', 'Programme précédent', 'Année validée', 'Notes'
        ];

        $callback = function() use($submissions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');

            foreach ($submissions as $submission) {
                $row = [
                    $submission->submission_id,
                    $this->admissionYears[$submission->admission_year]['name'] ?? $submission->admission_year,
                    $submission->last_name,
                    $submission->first_name,
                    $submission->email,
                    $submission->phone,
                    $this->programs[$submission->program] ?? $submission->program,
                    ucfirst($submission->status),
                    $submission->created_at->format('d/m/Y H:i'),
                    $submission->previous_school ?? 'N/A',
                    $submission->previous_program ?? 'N/A',
                    $submission->year_completed ?? 'N/A',
                    $submission->admin_notes
                ];

                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Règles de validation dynamiques selon l'année d'admission
     */
    protected function getValidationRules($admissionYear)
    {
        $baseRules = [
            'admission_year' => 'required|in:first,second',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'program' => 'required|string|in:' . implode(',', array_keys($this->programs)),
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'motivation_letter' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'additional_docs' => 'nullable|array',
            'additional_docs.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ];

        // Règles spécifiques pour la première année
        if ($admissionYear === 'first') {
            $baseRules['bac_attestation'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $baseRules['reports'] = 'required|array|min:1';
            $baseRules['reports.*'] = 'required|file|mimes:pdf,jpeg,png,jpg|max:5120';
        }

        // Règles spécifiques pour la deuxième année
        if ($admissionYear === 'second') {
            $baseRules['previous_school'] = 'required|string|max:255';
            $baseRules['previous_program'] = 'required|string|max:255';
            $baseRules['year_completed'] = 'required|integer|min:1|max:1';
            $baseRules['transcripts'] = 'required|array|min:1';
            $baseRules['transcripts.*'] = 'required|file|mimes:pdf,jpeg,png,jpg|max:5120';
        }

        return $baseRules;
    }

    /**
     * Messages de validation personnalisés selon l'année d'admission
     */
    protected function getValidationMessages($admissionYear)
    {
        $messages = [
            'required' => 'Le champ :attribute est obligatoire.',
            'max' => 'Le fichier :attribute ne doit pas dépasser :max Ko.',
            'mimes' => 'Le fichier :attribute doit être de type :values.',
            'in' => 'Le programme sélectionné est invalide.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'phone' => 'Veuillez entrer un numéro de téléphone valide.',
            'photo.required' => 'Une photo d\'identité est obligatoire.',
            'motivation_letter.required' => 'La lettre de motivation est obligatoire.',
            'admission_year.required' => 'L\'année d\'admission est obligatoire.',
            'admission_year.in' => 'L\'année d\'admission sélectionnée est invalide.',
        ];

        // Messages spécifiques pour la première année
        if ($admissionYear === 'first') {
            $messages['bac_attestation.required'] = 'L\'attestation de BAC est obligatoire pour l\'admission en première année.';
            $messages['reports.required'] = 'Au moins un bulletin est requis.';
            $messages['reports.*.required'] = 'Chaque bulletin est obligatoire.';
        }

        // Messages spécifiques pour la deuxième année
        if ($admissionYear === 'second') {
            $messages['previous_school.required'] = 'Le nom de l\'établissement précédent est obligatoire.';
            $messages['previous_program.required'] = 'Le programme précédent est obligatoire.';
            $messages['year_completed.required'] = 'L\'année validée est obligatoire.';
            $messages['transcripts.required'] = 'Au moins un relevé de notes est requis.';
            $messages['transcripts.*.required'] = 'Chaque relevé de notes est obligatoire.';
        }

        return $messages;
    }

    /**
     * Envoie les notifications
     */
    protected function sendNotifications(Submission $submission)
    {
        try {
            // Notification à l'étudiant
            $submission->notify(new SubmissionReceived($submission));

            // Notification aux administrateurs
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new \App\Notifications\NewSubmission($submission));

        } catch (\Exception $e) {
            Log::error("Notification Error: " . $e->getMessage(), [
                'submission_id' => $submission->id
            ]);
        }
    }

    /**
     * Affiche la page de suivi de dossier
     */
    public function tracking($submission_id)
    {
        $submission = Submission::where('submission_id', $submission_id)
            ->firstOrFail();

        // Définir les étapes du processus de candidature selon l'année
        $etapes = $this->getTrackingSteps($submission);

        // Calculer le pourcentage de progression
        $progress = 0;
        foreach ($etapes as $etape) {
            if ($etape['completed']) {
                $progress += (100 / count($etapes));
            }
        }

        return view('filetracking', [
            'submission' => $submission,
            'etapes' => $etapes,
            'progress' => round($progress),
        ]);
    }

    /**
     * Génère les étapes de suivi selon l'année d'admission
     */
    protected function getTrackingSteps(Submission $submission)
    {
        $baseSteps = [
            [
                'nom' => 'Soumission du dossier',
                'date' => $submission->created_at->format('d/m/Y'),
                'completed' => true,
                'description' => 'Votre dossier a été soumis avec succès et est en attente de traitement.',
            ],
            [
                'nom' => 'Vérification des documents',
                'date' => $submission->created_at->addDays(2)->format('d/m/Y'),
                'completed' => in_array($submission->status, ['under_review', 'approved', 'rejected']),
                'description' => 'Nous vérifions que tous les documents requis ont été fournis et sont conformes.',
            ],
            [
                'nom' => 'Évaluation du dossier',
                'date' => $submission->created_at->addDays(7)->format('d/m/Y'),
                'completed' => in_array($submission->status, ['approved', 'rejected']),
                'description' => 'Votre dossier est en cours d\'évaluation par notre comité de sélection.',
            ],
            [
                'nom' => 'Décision finale',
                'date' => $submission->created_at->addDays(14)->format('d/m/Y'),
                'completed' => in_array($submission->status, ['approved', 'rejected']),
                'description' => $this->getFinalDecisionDescription($submission),
            ]
        ];

        // Ajouter des étapes spécifiques selon l'année
        if ($submission->admission_year === 'second') {
            array_splice($baseSteps, 2, 0, [[
                'nom' => 'Validation des prérequis',
                'date' => $submission->created_at->addDays(5)->format('d/m/Y'),
                'completed' => in_array($submission->status, ['under_review', 'approved', 'rejected']),
                'description' => 'Vérification de votre parcours académique antérieur et des prérequis pour l\'admission directe.',
            ]]);
        }

        return $baseSteps;
    }

    /**
     * Description de la décision finale
     */
    protected function getFinalDecisionDescription(Submission $submission)
    {
        if ($submission->status === 'approved') {
            return 'Félicitations ! Votre candidature a été acceptée. Vous recevrez sous peu les informations pour la suite de la procédure.';
        } elseif ($submission->status === 'rejected') {
            return 'Nous sommes désolés, votre candidature n\'a pas été retenue pour cette session.';
        } else {
            return 'La décision concernant votre candidature sera bientôt disponible.';
        }
    }

    /**
     * Télécharge le dossier complet
     */
    public function downloadSubmission(Submission $submission)
    {
        try {
            // Vérifier l'existence des fichiers selon l'année d'admission
            $files = [];

            // Fichiers communs
            if ($submission->photo_path && Storage::disk('public')->exists($submission->photo_path)) {
                $files[] = storage_path('app/public/' . $submission->photo_path);
            }

            if ($submission->motivation_letter_path && Storage::disk('public')->exists($submission->motivation_letter_path)) {
                $files[] = storage_path('app/public/' . $submission->motivation_letter_path);
            }

            // Fichiers spécifiques à la première année
            if ($submission->admission_year === 'first') {
                if ($submission->bac_attestation_path && Storage::disk('public')->exists($submission->bac_attestation_path)) {
                    $files[] = storage_path('app/public/' . $submission->bac_attestation_path);
                }

                $reports = json_decode($submission->report_paths) ?: [];
                foreach ($reports as $report) {
                    if (Storage::disk('public')->exists($report)) {
                        $files[] = storage_path('app/public/' . $report);
                    }
                }
            }

            // Fichiers spécifiques à la deuxième année
            if ($submission->admission_year === 'second') {
                $transcripts = json_decode($submission->transcript_paths) ?: [];
                foreach ($transcripts as $transcript) {
                    if (Storage::disk('public')->exists($transcript)) {
                        $files[] = storage_path('app/public/' . $transcript);
                    }
                }
            }

            // Documents supplémentaires (communs)
            $additionalDocs = json_decode($submission->additional_doc_paths) ?: [];
            foreach ($additionalDocs as $doc) {
                if (Storage::disk('public')->exists($doc)) {
                    $files[] = storage_path('app/public/' . $doc);
                }
            }

            if (empty($files)) {
                return back()->with('error', 'Aucun fichier disponible pour téléchargement');
            }

            // Créer un zip avec tous les fichiers
            $zipName = 'dossier_' . $submission->submission_id . '_' . $submission->admission_year . '.zip';
            $zipPath = storage_path('app/public/temp/' . $zipName);

            // S'assurer que le répertoire existe
            if (!Storage::disk('public')->exists('temp')) {
                Storage::disk('public')->makeDirectory('temp');
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                foreach ($files as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();

                return response()->download($zipPath)->deleteFileAfterSend(true);
            }

            return back()->with('error', 'Impossible de créer l\'archive');

        } catch (\Exception $e) {
            Log::error("Download Error: " . $e->getMessage(), [
                'submission_id' => $submission->id,
                'admission_year' => $submission->admission_year
            ]);
            return back()->with('error', 'Erreur lors du téléchargement du dossier');
        }
    }

    /**
     * Affiche le formulaire de recherche de dossier
     */
    public function trackingForm()
    {
        return view('tracking-form');
    }

    /**
     * Recherche une soumission par son ID
     */
    public function searchSubmission(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|string|max:255'
        ], [
            'submission_id.required' => 'Veuillez saisir un numéro de référence'
        ]);

        try {
            $submission = Submission::where('submission_id', $validated['submission_id'])->first();

            if (!$submission) {
                return back()->withErrors([
                    'submission_id' => 'Aucun dossier trouvé avec ce numéro de référence'
                ])->withInput();
            }

            return redirect()->route('filetracking.show', $submission->submission_id);

        } catch (\Exception $e) {
            Log::error("Search Error: " . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la recherche');
        }
    }

    /**
     * Affiche un fichier en ligne
     */
    public function viewFile(Submission $submission, $fileType, $index = null)
    {
        try {
            $path = null;

            switch ($fileType) {
                case 'photo':
                    $path = $submission->photo_path;
                    break;
                case 'bac':
                    if ($submission->admission_year !== 'first') {
                        abort(404, 'Document non applicable pour cette année d\'admission');
                    }
                    $path = $submission->bac_attestation_path;
                    break;
                case 'transcript':
                    if ($submission->admission_year !== 'second') {
                        abort(404, 'Document non applicable pour cette année d\'admission');
                    }
                    $transcripts = json_decode($submission->transcript_paths);
                    $path = $transcripts[$index] ?? null;
                    break;
                case 'letter':
                    $path = $submission->motivation_letter_path;
                    break;
                case 'report':
                    if ($submission->admission_year !== 'first') {
                        abort(404, 'Document non applicable pour cette année d\'admission');
                    }
                    $reports = json_decode($submission->report_paths);
                    $path = $reports[$index] ?? null;
                    break;
                case 'additional':
                    $additional = json_decode($submission->additional_doc_paths);
                    $path = $additional[$index] ?? null;
                    break;
                default:
                    abort(404);
            }

            if (!$path || !Storage::disk('public')->exists($path)) {
                abort(404, 'Fichier non trouvé');
            }

            $absolutePath = Storage::disk('public')->path($path);
            $mimeType = Storage::disk('public')->mimeType($path);

            return response()->file($absolutePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($absolutePath) . '"'
            ]);

        } catch (\Exception $e) {
            Log::error("Preview Error: " . $e->getMessage(), [
                'submission_id' => $submission->id,
                'file_type' => $fileType,
                'index' => $index,
                'admission_year' => $submission->admission_year
            ]);
            abort(500, 'Erreur lors de la prévisualisation du fichier');
        }
    }

    /**
     * Statistiques des soumissions (pour dashboard admin)
     */
    public function statistics()
    {
        $totalSubmissions = Submission::count();
        $firstYearSubmissions = Submission::where('admission_year', 'first')->count();
        $secondYearSubmissions = Submission::where('admission_year', 'second')->count();

        $statusStats = Submission::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $programStats = Submission::select('program', DB::raw('count(*) as count'))
            ->groupBy('program')
            ->get()
            ->keyBy('program');

        return response()->json([
            'total' => $totalSubmissions,
            'by_year' => [
                'first' => $firstYearSubmissions,
                'second' => $secondYearSubmissions
            ],
            'by_status' => $statusStats,
            'by_program' => $programStats
        ]);
    }
}
