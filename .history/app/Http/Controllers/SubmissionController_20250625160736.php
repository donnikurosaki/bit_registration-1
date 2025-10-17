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
            \Illuminate\Support\Facades\Log::info('User data for prefill:', $userData);
        }

        return view('submission', [
            'programs' => $this->programs,
            'userData' => $userData
        ]);
    }

    /**
     * Traite la soumission du formulaire
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->validationRules(),
            $this->validationMessages()
        );

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()->all()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Veuillez corriger les erreurs dans le formulaire'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $submissionId = 'SUB-' . Str::upper(Str::random(8));

            // Stockage des fichiers
            $filePaths = $this->submissionService->storeFiles($request, $submissionId);
            if (!$filePaths) {
                throw new \Exception("Erreur lors du stockage des fichiers");
            }

            // Création de la soumission
            $submission = Submission::create([
                'submission_id' => $submissionId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'program' => $request->program,
                'photo_path' => $filePaths['photo_path'] ?? null,
                'bac_attestation_path' => $filePaths['bac_attestation_path'] ?? null,
                'motivation_letter_path' => $filePaths['motivation_letter_path'] ?? null,
                'report_paths' => json_encode($filePaths['report_paths'] ?? []),
                'additional_doc_paths' => json_encode($filePaths['additional_doc_paths'] ?? []),
                'status' => 'pending',
                'admin_notes' => null,
            ]);

            DB::commit();

            // Envoi des notifications
            $this->sendNotifications($submission);

            return response()->json([
                'success' => true,
                'submission_id' => $submissionId,
                'redirect_url' => route('submission.success', $submissionId),
                'message' => 'Dossier soumis avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur soumission: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['photo', 'bac_attestation', 'motivation_letter', 'reports', 'additional_docs'])
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
     * Liste toutes les soumissions (admin)
     */
    public function index(Request $request)
    {
        $submissions = Submission::latest()
            ->filter($request->only('search', 'status', 'program'))
            ->paginate(15)
            ->appends($request->query());

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
            'programs' => $this->programs
        ]);
    }

    /**
     * Affiche une soumission spécifique (admin)
     */
    public function show(Submission $submission)
    {
        $submission->report_paths = json_decode($submission->report_paths);
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
                    $path = $submission->bac_attestation_path;
                    $filename = 'attestation_bac_' . $submission->last_name . '.pdf';
                    break;
                case 'letter':
                    $path = $submission->motivation_letter_path;
                    $filename = 'lettre_motivation_' . $submission->last_name . '.' . pathinfo($path, PATHINFO_EXTENSION);
                    break;
                case 'report':
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
                'index' => $index
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
    public function export()
    {
        $submissions = Submission::all();
        $fileName = 'soumissions_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Programme',
            'Statut', 'Date de soumission', 'Notes'
        ];

        $callback = function() use($submissions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');

            foreach ($submissions as $submission) {
                $row = [
                    $submission->submission_id,
                    $submission->last_name,
                    $submission->first_name,
                    $submission->email,
                    $submission->phone,
                    $this->programs[$submission->program] ?? $submission->program,
                    ucfirst($submission->status),
                    $submission->created_at->format('d/m/Y H:i'),
                    $submission->admin_notes
                ];

                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Règles de validation
     */
    protected function validationRules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'program' => 'required|string|in:' . implode(',', array_keys($this->programs)),
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bac_attestation' => 'required|file|mimes:pdf|max:5120',
            'motivation_letter' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'reports' => 'required|array|min:1',
            'reports.*' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'additional_docs' => 'nullable|array',
            'additional_docs.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function validationMessages()
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'max' => 'Le fichier :attribute ne doit pas dépasser :max Ko.',
            'mimes' => 'Le fichier :attribute doit être de type :values.',
            'in' => 'Le programme sélectionné est invalide.',
            'reports.required' => 'Au moins un bulletin est requis.',
            'reports.*.required' => 'Chaque bulletin est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'phone' => 'Veuillez entrer un numéro de téléphone valide.',
            'photo.required' => 'Une photo d\'identité est obligatoire.',
            'bac_attestation.required' => 'L\'attestation de BAC est obligatoire.',
            'motivation_letter.required' => 'La lettre de motivation est obligatoire.'
        ];
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

        // Définir les étapes du processus de candidature
        $etapes = [
            [
                'nom' => 'Soumission du dossier',
                'date' => $submission->created_at->format('d/m/Y'),
                'completed' => true,
                'description' => 'Votre dossier a été soumis avec succès et est en attente de traitement.',
                'documents' => ['Formulaire de candidature', 'Photo d\'identité', 'Attestation de BAC', 'Lettre de motivation']
            ],
            [
                'nom' => 'Vérification des documents',
                'date' => $submission->created_at->addDays(2)->format('d/m/Y'),
                'completed' => in_array($submission->status, ['under_review', 'approved', 'rejected']),
                'description' => 'Nous vérifions que tous les documents requis ont été fournis et sont conformes.',
                'documents' => null
            ],
            [
                'nom' => 'Évaluation du dossier',
                'date' => $submission->created_at->addDays(7)->format('d/m/Y'),
                'completed' => in_array($submission->status, ['approved', 'rejected']),
                'description' => 'Votre dossier est en cours d\'évaluation par notre comité de sélection.',
                'documents' => null
            ],
            [
                'nom' => 'Décision finale',
                'date' => $submission->created_at->addDays(14)->format('d/m/Y'),
                'completed' => in_array($submission->status, ['approved', 'rejected']),
                'description' => $submission->status === 'approved'
                    ? 'Félicitations ! Votre candidature a été acceptée.'
                    : ($submission->status === 'rejected'
                        ? 'Nous sommes désolés, votre candidature n\'a pas été retenue pour cette session.'
                        : 'La décision concernant votre candidature sera bientôt disponible.'),
                'documents' => null
            ]
        ];

        // Calculer le pourcentage de progression
        $progress = 0;
        foreach ($etapes as $etape) {
            if ($etape['completed']) {
                $progress += 25; // 4 étapes = 25% chacune
            }
        }

        return view('filetracking', [
            'submission' => $submission,
            'etapes' => $etapes,
            'progress' => $progress,
        ]);
    }

    /**
     * Télécharge le dossier complet
     */
    public function downloadSubmission(Submission $submission)
    {
        try {
            // Vérifier l'existence des fichiers
            $files = [];

            if ($submission->photo_path && Storage::disk('public')->exists($submission->photo_path)) {
                $files[] = storage_path('app/public/' . $submission->photo_path);
            }

            if ($submission->bac_attestation_path && Storage::disk('public')->exists($submission->bac_attestation_path)) {
                $files[] = storage_path('app/public/' . $submission->bac_attestation_path);
            }

            if ($submission->motivation_letter_path && Storage::disk('public')->exists($submission->motivation_letter_path)) {
                $files[] = storage_path('app/public/' . $submission->motivation_letter_path);
            }

            // Ajouter les rapports
            $reports = json_decode($submission->report_paths) ?: [];
            foreach ($reports as $report) {
                if (Storage::disk('public')->exists($report)) {
                    $files[] = storage_path('app/public/' . $report);
                }
            }

            // Ajouter les documents supplémentaires
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
            $zipName = 'dossier_' . $submission->submission_id . '.zip';
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
                'submission_id' => $submission->id
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

    public function viewFile(Submission $submission, $fileType, $index = null)
    {
        try {
            $path = null;

            switch ($fileType) {
                case 'photo':
                    $path = $submission->photo_path;
                    break;
                case 'bac':
                    $path = $submission->bac_attestation_path;
                    break;
                case 'letter':
                    $path = $submission->motivation_letter_path;
                    break;
                case 'report':
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

            if (!$path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                abort(404, 'Fichier non trouvé');
            }

            $absolutePath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
            $mimeType = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($path);

            return response()->file($absolutePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($absolutePath) . '"'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Preview Error: " . $e->getMessage(), [
                'submission_id' => $submission->id,
                'file_type' => $fileType,
                'index' => $index
            ]);
            abort(500, 'Erreur lors de la prévisualisation du fichier');
        }
    }
}
