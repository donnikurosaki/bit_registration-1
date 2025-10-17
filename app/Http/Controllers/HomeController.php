<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use App\Models\Testimonial;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérez les données pour les statistiques
        // $stats = [
        //     'students' => Student::count(),
        //     'programs' => Program::count(),
        //     'success_rate' => $this->calculateSuccessRate()
        // ];

        // Récupérez les témoignages
        // $testimonials = Testimonial::where('featured', true)
        //                         ->orderBy('created_at', 'desc')
        //                         ->take(3)
        //                         ->get();

        return view('home');
    }

    /**
     * Calcule le taux de réussite
     *
     * @return int
     */
    protected function calculateSuccessRate()
    {
        // Logique de calcul selon votre application
        // Exemple simplifié :
        // $totalStudents = Student::count();
        // $successfulStudents = Student::where('status', 'graduated')->count();

        // return $totalStudents > 0 ? round(($successfulStudents / $totalStudents) * 100) : 0;
    }
}
