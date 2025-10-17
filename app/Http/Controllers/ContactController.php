<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    // Adresse e-mail du destinataire - remplacez par votre adresse
    protected $toEmail = 'daradieudonne97@gmail.com'; // REMPLACEZ CETTE ADRESSE PAR LA VÔTRE
    
    public function showForm()
    {
        return view('contact');
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Envoi de l'e-mail à l'adresse spécifiée
            Mail::to($this->toEmail)->send(new ContactFormMail($validated));
            
            // Journalisation de l'envoi pour le débogage
            Log::info('Email envoyé à: ' . $this->toEmail, [
                'from' => $validated['email'],
                'subject' => $validated['subject']
            ]);
            
            return redirect()->back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer ultérieurement.')->withInput();
        }
    }
} 