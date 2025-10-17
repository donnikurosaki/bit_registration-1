@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    {{-- <h1>{{ __('submissions.title') }}</h1> --}}
    <p>{{ __('submissions.status.' . $submission->status) }}</p>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-down">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">
                Suivi de Dossier
            </h1>
            <p class="mt-3 text-xl text-gray-500 max-w-2xl mx-auto">
                Votre dossier <span class="font-semibold">#{{ $submission->submission_id }}</span> est en cours de traitement
            </p>
        </div>

        <!-- Progress timeline -->
        <div class="relative">
            <!-- Progress line -->
            <div class="absolute left-4 top-5 h-full w-1 bg-gray-200 transform -translate-x-1/2"></div>
            
            <div class="space-y-8">
                @foreach($etapes as $index => $etape)
                <div 
                    class="relative flex items-start group transition-all duration-300"
                    x-data="{ expanded: {{ $etape['completed'] ? 'true' : 'false' }} }"
                >
                    <div class="flex-shrink-0 z-10">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center border-4 bg-white
                            {{ $etape['completed'] 
                                ? 'border-accent ring-4 ring-accent/20' 
                                : ($loop->first ? 'border-accent animate-pulse' : 'border-gray-300') }}"
                            style="border-color: {{ $etape['completed'] || $loop->first ? '#f72585' : '' }};">

                            @if($etape['completed'])
                            <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #f72585;">
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
                                <button class="text-gray-400 hover:text-gray-500">
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

                                @if($etape['description'])
                                <div>
                                    <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500">Détails</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $etape['description'] }}
                                    </p>
                                </div>
                                @endif

                                @if($etape['documents'])
                                <div>
                                    <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500">Documents</h4>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($etape['documents'] as $doc)
                                        <span class="inline-flex items-center text-sm">
                                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-accent" fill="currentColor" viewBox="0 0 20 20" style="color: #f72585;">
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
                        <div class="bg-accent/10 p-3 rounded-lg" style="background-color: rgba(247, 37, 133, 0.1);">
                            <svg class="h-8 w-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #f72585;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <h3 class="text-xl font-bold text-gray-900">État actuel</h3>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-2.5 w-2.5 rounded-full bg-accent animate-pulse" style="background-color: #f72585;"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-lg font-medium text-gray-900">
                                        @switch($submission->status)
                                            @case('pending')
                                                En attente de traitement
                                                @break
                                            @case('under_review')
                                                En cours d'examen
                                                @break
                                            @case('approved')
                                                Approuvé
                                                @break
                                            @case('rejected')
                                                Rejeté
                                                @break
                                            @default
                                                En attente de traitement
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                            <p class="mt-3 text-base text-gray-500">
                                Dernière mise à jour: {{ $submission->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-accent bg-accent/10" style="background-color: rgba(247, 37, 133, 0.1); color: #f72585;">
                                    Progression
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-accent" style="color: #f72585;">
                                    {{ $progress }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-3 mb-4 text-xs flex rounded bg-gray-200">
                            <div 
                                id="progress-bar"
                                style="width: 0%; background-color: #f72585;"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-accent transition-all duration-1000 ease-out"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 sm:px-10">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-600">
                        Besoin d'aide ? <a href="#" class="font-medium text-accent hover:text-accent/80" style="color: #f72585;">Contactez notre support</a>
                    </p>
                    <a href="{{ route('submission.download', $submission->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-accent hover:bg-accent/90 focus:outline-none transition-transform transform hover:-translate-y-0.5" style="background-color: #f72585;">
                        Télécharger le dossier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AlpineJS & Progress animation -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bar
        const progressBar = document.getElementById('progress-bar');
        const targetWidth = "{{ $progress }}%";
        
        setTimeout(() => {
            progressBar.style.width = targetWidth;
        }, 300);

        // Add confetti effect on complete
        @if($progress == 100 && $submission->status === 'approved')
        setTimeout(() => {
            const confettiSettings = { target: 'confetti-canvas', size: 2 };
            const confetti = new ConfettiGenerator(confettiSettings);
            confetti.render();
            
            setTimeout(() => {
                confetti.clear();
            }, 5000);
        }, 1500);
        @endif
    });
</script>

<!-- Confetti library for completion effect -->
@if($progress == 100 && $submission->status === 'approved')
<canvas id="confetti-canvas" class="fixed inset-0 w-full h-full pointer-events-none z-50"></canvas>
<script src="https://cdn.jsdelivr.net/npm/confetti-js@0.0.18/dist/index.min.js"></script>
@endif

<!-- Custom animations -->
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
    
    [x-cloak] { display: none !important; }
</style>
@endsection