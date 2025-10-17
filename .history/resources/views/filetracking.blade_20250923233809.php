@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-down">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">
                Suivi de Dossier
            </h1>
            <p class="mt-3 text-xl text-gray-500 max-w-2xl mx-auto">
                Votre dossier <span class="font-semibold">#{{ $submission->submission_id }}</span> est en cours de traitement
            </p>

            <!-- Badge d'année d'admission -->
            <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                {{ $submission->admission_year === 'first' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                </svg>
                Admission en {{ $submission->admission_year === 'first' ? 'Première Année' : 'Deuxième Année' }}
            </div>
        </div>

        <!-- Progress timeline -->
        <div class="relative">
            <!-- Progress line -->
            <div class="absolute left-4 top-5 h-full w-1 bg-gray-200 transform -translate-x-1/2"></div>

            <div class="space-y-8">
                @foreach($etapes as $index => $etape)
                <div
                    class="relative flex items-start group transition-all duration-300"
                    x-data="{ expanded: {{ $index === 0 ? 'true' : ($etape['completed'] ? 'true' : 'false') }} }"
                >
                    <div class="flex-shrink-0 z-10">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center border-4 bg-white
                            {{ $etape['completed']
                                ? 'border-green-500 ring-4 ring-green-500/20'
                                : ($loop->first ? 'border-blue-500 animate-pulse' : 'border-gray-300') }}">

                            @if($etape['completed'])
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            @else
                            <span class="text-gray-500 font-medium">{{ $index + 1 }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="ml-6 flex-1 min-w-0">
                        <div
                            class="bg-white rounded-xl shadow-sm p-5 cursor-pointer hover:shadow-md transition-shadow duration-300"
                            @click="expanded = !expanded"
                        >
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $etape['nom'] }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 flex items-center">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $etape['date'] }}
                                    </p>
                                </div>
                                <button class="text-gray-400 hover:text-gray-500 transition-colors">
                                    <svg
                                        class="h-5 w-5 transition-transform duration-200 transform"
                                        :class="{ 'rotate-180': expanded }"
                                        viewBox="0 0 20 20" fill="currentColor"
                                    >
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Expandable content -->
                            <div
                                x-show="expanded"
                                x-collapse
                                class="mt-4 space-y-3 border-t border-gray-100 pt-4"
                            >
                                <div>
                                    <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500">Statut</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                        {{ $etape['completed'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $etape['completed'] ? 'Complété' : 'En attente' }}
                                    </span>
                                </div>

                                @if(isset($etape['description']) && $etape['description'])
                                <div>
                                    <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500">Détails</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $etape['description'] }}
                                    </p>
                                </div>
                                @endif

                                @if(isset($etape['documents']) && is_array($etape['documents']) && count($etape['documents']) > 0)
                                <div>
                                    <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500">Documents</h4>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($etape['documents'] as $doc)
                                        <span class="inline-flex items-center text-sm bg-gray-50 px-2 py-1 rounded">
                                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
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

        <!-- Status card -->
        <div class="mt-16 bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in-up">
            <div class="px-6 py-8 sm:p-10">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <h3 class="text-xl font-bold text-gray-900">État actuel du dossier</h3>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @switch($submission->status)
                                        @case('approved')
                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500"></div>
                                            @break
                                        @case('rejected')
                                            <div class="h-2.5 w-2.5 rounded-full bg-red-500"></div>
                                            @break
                                        @case('under_review')
                                            <div class="h-2.5 w-2.5 rounded-full bg-yellow-500 animate-pulse"></div>
                                            @break
                                        @default
                                            <div class="h-2.5 w-2.5 rounded-full bg-gray-500"></div>
                                    @endswitch
                                </div>
                                <div class="ml-3">
                                    <p class="text-lg font-medium text-gray-900">
                                        @switch($submission->status)
                                            @case('pending')
                                                📝 En attente de traitement
                                                @break
                                            @case('under_review')
                                                🔍 En cours d'examen
                                                @break
                                            @case('approved')
                                                ✅ Dossier approuvé
                                                @break
                                            @case('rejected')
                                                ❌ Dossier rejeté
                                                @break
                                            @default
                                                📋 En attente de traitement
                                        @endswitch
                                    </p>
                                    @if($submission->admin_notes)
                                    <p class="mt-2 text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                        <strong>Note administrative :</strong> {{ $submission->admin_notes }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <p class="mt-3 text-base text-gray-500">
                                Dernière mise à jour : {{ $submission->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-8">
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-100">
                                    Progression globale
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-blue-600">
                                    {{ $progress }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-3 mb-4 text-xs flex rounded bg-gray-200">
                            <div
                                id="progress-bar"
                                style="width: 0%;"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 transition-all duration-1000 ease-out"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Informations du dossier -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <span class="font-medium text-gray-700">Candidat :</span>
                        <p class="mt-1">{{ $submission->first_name }} {{ $submission->last_name }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <span class="font-medium text-gray-700">Programme :</span>
                        <p class="mt-1">
                            @php
                                $programs = [
                                    'informatique' => 'Licence Informatique et Entrepreunariat',
                                    'mecanique_agriculture' => 'Licence Mécanique Option Agriculture',
                                    'mecanique_mine' => 'Licence Mécanique Option Mine',
                                    'energie' => 'Licence Énergie Renouvelable',
                                    'master' => 'Master Informatique Option Intelligence Artificielle',
                                ];
                            @endphp
                            {{ $programs[$submission->program] ?? $submission->program }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <span class="font-medium text-gray-700">Email :</span>
                        <p class="mt-1">{{ $submission->email }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <span class="font-medium text-gray-700">Téléphone :</span>
                        <p class="mt-1">{{ $submission->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-50 px-6 py-4 sm:px-10 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <div class="text-center sm:text-left">
                        <p class="text-sm text-gray-600">
                            📞 Besoin d'aide ?
                            <a href="mailto:admission@universite.com" class="font-medium text-blue-600 hover:text-blue-500 ml-1">
                                Contactez notre service admission
                            </a>
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('submission.download', $submission) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:-translate-y-0.5">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            Télécharger le dossier
                        </a>
                        <a href="{{ route('tracking.form') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Nouvelle recherche
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confetti canvas (hidden until needed) -->
        <canvas id="confetti-canvas" class="fixed inset-0 w-full h-full pointer-events-none z-50 opacity-0 transition-opacity duration-300"></canvas>
    </div>
</div>

<!-- AlpineJS -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bar
    const progressBar = document.getElementById('progress-bar');
    const targetWidth = "{{ $progress }}%";

    setTimeout(() => {
        progressBar.style.width = targetWidth;
    }, 300);

    // Confetti effect for approved submissions
    @if($progress == 100 && $submission->status === 'approved')
    setTimeout(() => {
        const confettiCanvas = document.getElementById('confetti-canvas');
        confettiCanvas.style.opacity = '1';

        // Simple confetti effect without external library
        createConfetti();

        setTimeout(() => {
            confettiCanvas.style.opacity = '0';
        }, 3000);
    }, 1500);

    function createConfetti() {
        const canvas = document.getElementById('confetti-canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const confettiPieces = [];
        const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];

        // Create confetti pieces
        for (let i = 0; i < 150; i++) {
            confettiPieces.push({
                x: Math.random() * canvas.width,
                y: -10,
                size: Math.random() * 10 + 5,
                color: colors[Math.floor(Math.random() * colors.length)],
                speed: Math.random() * 3 + 2,
                rotation: Math.random() * 360,
                rotationSpeed: Math.random() * 10 - 5
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
                    ctx.fillRect(-piece.size/2, -piece.size/2, piece.size, piece.size);
                    ctx.restore();
                }
            });

            if (activePieces > 0) {
                requestAnimationFrame(animateConfetti);
            }
        }

        animateConfetti();
    }
    @endif
});

// Update canvas size on window resize
window.addEventListener('resize', function() {
    const canvas = document.getElementById('confetti-canvas');
    if (canvas) {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
});
</script>

<style>
.animate-fade-in-down {
    animation: fadeInDown 0.6s ease-out forwards;
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

[x-cloak] {
    display: none !important;
}

/* Custom scrollbar for timeline */
.space-y-8::-webkit-scrollbar {
    width: 6px;
}

.space-y-8::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.space-y-8::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.space-y-8::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection
