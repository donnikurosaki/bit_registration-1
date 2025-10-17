@extends('layouts.app')

@section('title', 'Soumission réussie')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="mt-3 text-2xl font-bold text-gray-900">Votre dossier a été soumis avec succès!</h2>
                <p class="mt-2 text-sm text-gray-500">Nous avons bien reçu votre demande d'admission et nous l'examinerons dans les plus brefs délais.</p>

                <div class="mt-6 bg-blue-50 rounded-lg p-4 text-left">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Détails de votre soumission</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Numéro de dossier</p>
                            <p class="text-sm text-gray-900 font-semibold">{{ $submission->submission_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date de soumission</p>
                            <p class="text-sm text-gray-900 font-semibold">{{ $submission->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Programme</p>
                            <p class="text-sm text-gray-900 font-semibold">{{ ucfirst($submission->program) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Statut</p>
                            <p class="text-sm text-gray-900 font-semibold">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    En attente
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-sm text-gray-500">Nous vous enverrons un email de confirmation sous peu. Vous pouvez également suivre l'état de votre demande dans votre espace personnel.</p>
                </div>

                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
