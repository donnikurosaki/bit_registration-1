@extends('layouts.app')

@section('title', 'Suivi de Dossier - ' . $submission->submission_id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header avec informations principales -->
        <div class="text-center mb-8 animate-fade-in-down">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white shadow-sm mb-4">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-sm font-medium text-gray-700">Dossier #{{ $submission->submission_id }}</span>
            </div>

            <h1 class="text-4xl font-bold text-gray-900 tracking-tight sm:text-5xl mb-4">
                Suivi de Votre Dossier
            </h1>

            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <!-- Badge année d'admission -->
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    {{ $submission->admission_year === 'first' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    </svg>
                    {{ $submission->admission_year === 'first' ? 'Première Année' : 'Deuxième Année' }}
                </span>

                <!-- Badge programme -->
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    {{ $programs[$submission->program] ?? $submission->program }}
                </span>

                <!-- Badge statut -->
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    {{ $submission->status === 'approved' ? 'bg-green-100 text-green-800' :
                       ($submission->status === 'rejected' ? 'bg-red-100 text-red-800' :
                       ($submission->status === 'under_review' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ $submission->status === 'pending' ? 'En attente' :
                       ($submission->status === 'approved' ? 'Approuvé' :
                       ($submission->status === 'rejected' ? 'Rejeté' : 'En examen')) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne de gauche - Informations candidat -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Informations Candidat</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">
                                        {{ substr($submission->first_name, 0, 1) }}{{ substr($submission->last_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $submission->first_name }} {{ $submission->last_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $submission->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Téléphone</span>
                                <span class="text-sm text-gray-900">{{ $submission->phone }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Date de soumission</span>
                                <span class="text-sm text-gray-900">{{ $submission->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Dernière mise à jour</span>
                                <span class="text-sm text-gray-900">{{ $submission->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        <!-- Informations spécifiques selon l'année -->
                        @if($submission->admission_year === 'second')
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">Informations Admission Directe</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Établissement précédent</span>
                                    <span class="text-blue-900">{{ $submission->previous_school ?? 'Non renseigné' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Programme précédent</span>
                                    <span class="text-blue-900">{{ $submission->previous_program ?? 'Non renseigné' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Année validée</span>
                                    <span class="text-blue-900">{{ $submission->year_completed ?? 'Non renseigné' }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Carte de progression -->
                <div class="mt-6 bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Progression du Dossier</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-full">
                                <span class="text-2xl font-bold text-white">{{ $progress }}%</span>
                            </div>
                        </div>

                        <div class="relative pt-1">
                            <div class="flex mb-2 justify-between items-center">
                                <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                                        Avancement
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-green-600">
                                        {{ $progress }}% complet
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-3 mb-4 text-xs flex rounded bg-gray-200">
                                <div id="progress-bar"
                                     style="width: 0%"
                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-green-500 to-green-600 transition-all duration-1000 ease-out">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            @if($submission->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Dossier approuvé
                            </span>
                            @elseif($submission->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Dossier rejeté
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                En cours de traitement
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne de droite - Timeline et documents -->
            <div class="lg:col-span-2">
                <!-- Timeline améliorée -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Étapes de Traitement</h2>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            <!-- Ligne de progression -->
                            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200 transform translate-x-0.5"></div>

                            <div class="space-y-8">
                                @foreach($etapes as $index => $etape)
                                <div class="relative flex items-start group"
                                     x-data="{ expanded: {{ $index === 0 || $etape['completed'] ? 'true' : 'false' }} }">

                                    <!-- Point de timeline -->
                                    <div class="flex-shrink-0 z-10">
                                        <div class="h-12 w-12 rounded-full flex items-center justify-center border-4 bg-white
                                            {{ $etape['completed']
                                                ? 'border-green-500 shadow-lg shadow-green-500/25'
                                                : ($loop->first ? 'border-blue-500 animate-pulse shadow-lg shadow-blue-500/25' : 'border-gray-300') }}">

                                            @if($etape['completed'])
                                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            @else
                                            <span class="text-lg font-semibold {{ $loop->first ? 'text-blue-500' : 'text-gray-400' }}">
                                                {{ $index + 1 }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Contenu de l'étape -->
                                    <div class="ml-6 flex-1 min-w-0">
                                        <div class="bg-gray-50 rounded-lg p-4 cursor-pointer hover:bg-gray-100 transition-colors duration-200"
                                             @click="expanded = !expanded">
                                            <div class="flex justify-between items-center">
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $etape['nom'] }}</h3>
                                                    <div class="flex items-center mt-1 space-x-4">
                                                        <span class="inline-flex items-center text-sm text-gray-500">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            {{ $etape['date'] }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            {{ $etape['completed'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ $etape['completed'] ? 'Terminé' : 'En attente' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <button class="text-gray-400 hover:text-gray-600 transition-colors ml-4">
                                                    <svg class="h-5 w-5 transition-transform duration-200"
                                                         :class="{ 'rotate-180': expanded }"
                                                         viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Contenu dépliable -->
                                            <div x-show="expanded" x-collapse class="mt-3 space-y-2 pt-3 border-t border-gray-200">
                                                @if(isset($etape['description']) && $etape['description'])
                                                <p class="text-sm text-gray-600 leading-relaxed">{{ $etape['description'] }}</p>
                                                @endif

                                                @if(isset($etape['documents']) && is_array($etape['documents']) && count($etape['documents']) > 0)
                                                <div>
                                                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Documents requis</h4>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($etape['documents'] as $doc)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-white border border-gray-200">
                                                            <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ $doc }}
                                                        </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section documents et actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Actions rapides -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h2 class="text-lg font-semibold text-white">Actions Rapides</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <a href="{{ route('submission.download', $submission) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                </svg>
                                Télécharger le dossier complet
                            </a>

                            <a href="mailto:admission@universite.com?subject=Question%20sur%20le%20dossier%20{{ $submission->submission_id }}&body=Bonjour,%0D%0A%0D%0AJ'ai une question concernant mon dossier de candidature {{ $submission->submission_id }}.%0D%0A%0D%0ACordialement,%0D%0A{{ $submission->first_name }} {{ $submission->last_name }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Contacter le service admission
                            </a>

                            <a href="{{ url('/') }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Retour à l'accueil
                            </a>
                        </div>
                    </div>

                    <!-- Statistiques du dossier -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                            <h2 class="text-lg font-semibold text-white">Statistiques</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-4 bg-orange-50 rounded-lg">
                                    <div class="text-2xl font-bold text-orange-600">{{ count($etapes) }}</div>
                                    <div class="text-sm text-orange-700">Étapes totales</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ collect($etapes)->where('completed', true)->count() }}
                                    </div>
                                    <div class="text-sm text-green-700">Étapes terminées</div>
                                </div>
                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $submission->created_at->diffInDays(now()) }}
                                    </div>
                                    <div class="text-sm text-blue-700">Jours écoulés</div>
                                </div>
                                <div class="text-center p-4 bg-purple-50 rounded-lg">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ $submission->admission_year === 'first' ? '1ère' : '2ème' }}
                                    </div>
                                    <div class="text-sm text-purple-700">Année demandée</div>
                                </div>
                            </div>

                            @if($submission->admin_notes)
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">📝 Note administrative</h4>
                                <p class="text-sm text-gray-600">{{ $submission->admin_notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confetti Canvas pour célébration -->
<canvas id="confetti-canvas" class="fixed inset-0 w-full h-full pointer-events-none z-50 opacity-0 transition-opacity duration-500"></canvas>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation de la barre de progression
    const progressBar = document.getElementById('progress-bar');
    const targetWidth = "{{ $progress }}%";

    setTimeout(() => {
        progressBar.style.width = targetWidth;
    }, 500);

    // Effet confetti pour les dossiers approuvés
    @if($progress >= 100 && $submission->status === 'approved')
    setTimeout(() => {
        celebrateApproval();
    }, 1000);
    @endif

    function celebrateApproval() {
        const canvas = document.getElementById('confetti-canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        canvas.style.opacity = '1';

        const confettiPieces = [];
        const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57', '#ff9ff3', '#54a0ff'];

        // Créer les particules de confetti
        for (let i = 0; i < 200; i++) {
            confettiPieces.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height - canvas.height,
                size: Math.random() * 12 + 3,
                color: colors[Math.floor(Math.random() * colors.length)],
                speed: Math.random() * 3 + 2,
                rotation: Math.random() * 360,
                rotationSpeed: Math.random() * 10 - 5,
                shape: Math.random() > 0.5 ? 'circle' : 'rect'
            });
        }

        function animateConfetti() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            let activePieces = 0;

            confettiPieces.forEach(piece => {
                piece.y += piece.speed;
                piece.rotation += piece.rotationSpeed;

                if (piece.y < canvas.height) {
                    activePieces++;

                    ctx.save();
                    ctx.translate(piece.x, piece.y);
                    ctx.rotate(piece.rotation * Math.PI / 180);
                    ctx.fillStyle = piece.color;

                    if (piece.shape === 'circle') {
                        ctx.beginPath();
                        ctx.arc(0, 0, piece.size / 2, 0, Math.PI * 2);
                        ctx.fill();
                    } else {
                        ctx.fillRect(-piece.size/2, -piece.size/2, piece.size, piece.size);
                    }

                    ctx.restore();
                }
            });

            if (activePieces > 0) {
                requestAnimationFrame(animateConfetti);
            } else {
                setTimeout(() => {
                    canvas.style.opacity = '0';
                }, 2000);
            }
        }

        animateConfetti();
    }

    // Mise à jour de la taille du canvas lors du redimensionnement
    window.addEventListener('resize', function() {
        const canvas = document.getElementById('confetti-canvas');
        if (canvas) {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
    });
});
</script>

<style>
.animate-fade-in-down {
    animation: fadeInDown 0.8s ease-out forwards;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

[x-cloak] {
    display: none !important;
}

/* Animation pulse pour les étapes en cours */
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* Amélioration du scroll */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Effets de hover améliorés */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endsection
