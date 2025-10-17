<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use Illuminate\Database\Eloquent\Builder;

class Submission extends Model
{
    use HasFactory;
   protected $fillable = [
        'submission_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'program',
        'photo_path',
        'bac_attestation_path',
        'motivation_letter_path',
        'report_paths',
        'additional_doc_paths',
        'status',
        'admin_notes'
    ];

    protected $casts = [
        'report_paths' => 'array',
        'additional_doc_paths' => 'array'
    ];

    public function programs() {
        return $programs;
    }

    // app/Models/Submission.php


    public function scopeFilter(Builder $query, array $filters)
    {
        // Filtre de recherche global (nom, email, id de soumission...)
        $query->when($filters['search'] ?? null, function (Builder $query, $search) {
            $query->where(function (Builder $query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('submission_id', 'like', "%{$search}%");
            });
        });

        // Filtre par statut
        $query->when($filters['status'] ?? null, function (Builder $query, $status) {
            $query->where('status', $status);
        });

        // Filtre par programme
        $query->when($filters['program'] ?? null, function (Builder $query, $program) {
            $query->where('program', $program);
        });
    }

}
