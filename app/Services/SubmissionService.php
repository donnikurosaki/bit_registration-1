<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Submission;

class SubmissionService
{
    /**
     * Stocke les fichiers de la soumission
     */
    public function storeFiles(Request $request, string $submissionId): array
    {
        $paths = [];
        $basePath = "submissions/{$submissionId}";

        try {
            // Photo
            if ($request->hasFile('photo')) {
                $paths['photo_path'] = $request->file('photo')->store(
                    "{$basePath}/photo", 'public'
                );
            }

            // Attestation BAC
            if ($request->hasFile('bac_attestation')) {
                $paths['bac_attestation_path'] = $request->file('bac_attestation')->store(
                    "{$basePath}/bac", 'public'
                );
            }

            // Lettre de motivation
            if ($request->hasFile('motivation_letter')) {
                $paths['motivation_letter_path'] = $request->file('motivation_letter')->store(
                    "{$basePath}/motivation", 'public'
                );
            }

            // Bulletins
            if ($request->hasFile('reports')) {
                $paths['report_paths'] = [];
                foreach ($request->file('reports') as $file) {
                    $paths['report_paths'][] = $file->store(
                        "{$basePath}/reports", 'public'
                    );
                }
            }

            // Documents supplémentaires
            if ($request->hasFile('additional_docs')) {
                $paths['additional_doc_paths'] = [];
                foreach ($request->file('additional_docs') as $file) {
                    $paths['additional_doc_paths'][] = $file->store(
                        "{$basePath}/additional", 'public'
                    );
                }
            }

            return $paths;

        } catch (\Exception $e) {
            $this->cleanupFailedSubmission($paths);
            throw $e;
        }
    }

    /**
     * Nettoie les fichiers en cas d'échec
     */
    public function cleanupFailedSubmission(array $filePaths): void
    {
        foreach ($filePaths as $path) {
            if (is_array($path)) {
                foreach ($path as $p) {
                    Storage::disk('public')->delete($p);
                }
            } else {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Supprime les fichiers d'une soumission
     */
    public function deleteSubmissionFiles(Submission $submission): void
    {
        $files = [
            $submission->photo_path,
            $submission->bac_attestation_path,
            $submission->motivation_letter_path,
            ...json_decode($submission->report_paths),
            ...($submission->additional_doc_paths ? json_decode($submission->additional_doc_paths) : [])
        ];

        foreach ($files as $file) {
            if ($file) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
