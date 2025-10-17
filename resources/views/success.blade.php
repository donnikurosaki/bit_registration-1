@extends('layouts.app')

@section('title', 'Soumission réussie')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Dossier soumis avec succès
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Votre référence est: <span class="font-medium text-accent">{{ $submission->submission_id }}</span>
            <button 
                class="ml-2 p-1 text-accent hover:text-accent-dark focus:outline-none" 
                style="color: #f72585;"
                onclick="showReferencePopup()"
                title="Afficher le popup de référence"
            >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </button>
        </p>
    </div>  

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="text-center">
                <p class="text-sm text-gray-700 mb-6">
                    Nous avons reçu votre dossier et nous l'examinerons dans les plus brefs délais. Revenez souvent pour connaître l'état de votre candidature.
                </p>
                
                <div class="border-t border-b border-gray-200 py-4 my-4">
                    <div class="flex flex-col space-y-2">
                        <div>
                            <span class="text-xs text-gray-500">Nom complet:</span>
                            <p class="font-medium">{{ $submission->first_name }} {{ $submission->last_name }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Programme:</span>
                            <p class="font-medium">{{ $submission->program }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Date de soumission:</span>
                            <p class="font-medium">{{ $submission->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col space-y-4">
                    <a href="{{ route('filetracking.show', $submission->submission_id) }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none" style="background-color: #f72585;">
                        Suivre mon dossier
                    </a>
                    
                    <button
                        type="button"
                        onclick="showReferencePopup()"
                        class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none"
                    >
                        <svg class="mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        Revoir mon numéro de référence
                    </button>
                    
                    <a href="{{ route('home') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popup avec le numéro de référence -->
<div id="reference-popup" class="fixed inset-0 z-50 flex items-center justify-center" x-data="{ open: true }" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <!-- Overlay avec effet de flou -->
    <div class="absolute inset-0 bg-gray-900 bg-opacity-70 backdrop-filter backdrop-blur-sm"></div>
    
    <!-- Contenu de la popup -->
    <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6 transform transition-all duration-300" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div class="absolute top-0 right-0 pt-3 pr-3">
            <button type="button" @click="open = false" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                <span class="sr-only">Fermer</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-accent bg-opacity-10 mb-4">
                <svg class="h-6 w-6 text-accent" fill="currentColor" viewBox="0 0 20 20" style="color: #f72585;">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            
            <h3 class="text-lg font-medium text-gray-900 mb-2">Important: conservez votre référence</h3>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-600 mb-2">Votre numéro de référence unique est:</p>
                <div class="flex items-center justify-center">
                    <span class="font-mono text-lg font-bold text-accent px-4 py-2 border border-gray-200 rounded-md bg-white" style="color: #f72585;">{{ $submission->submission_id }}</span>
                    <button 
                        class="ml-2 p-2 text-gray-500 hover:text-accent focus:outline-none" 
                        onclick="copyToClipboard('{{ $submission->submission_id }}')"
                        title="Copier"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path>
                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <p class="text-sm text-gray-500 mb-4">
                Vous aurez besoin de ce numéro pour suivre l'état de votre candidature. Veuillez le conserver précieusement.
            </p>
            
            <button 
                type="button" 
                @click="open = true" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none" 
                style="background-color: #f72585;"
            >
                J'ai noté ma référence
            </button>
        </div>
    </div>
</div>

<!-- AlpineJS -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Script pour copier dans le presse-papier et afficher le popup -->
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Montrer une notification de succès
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white py-2 px-4 rounded-md shadow-lg transition-opacity duration-300';
            notification.textContent = 'Référence copiée!';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 2000);
        });
    }
    
    function showReferencePopup() {
        // Accéder au composant AlpineJS et changer son état
        const popup = document.getElementById('reference-popup').__x;
        popup.$data.open = true;
    }
</script>
@endsection
