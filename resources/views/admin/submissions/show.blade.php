@extends('layouts.app')

@section('content')
<div x-data="{ showPreview: false, previewUrl: '', previewExt: '' }" class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Détails de la Soumission</h1>
        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- En-tête avec année d'admission -->
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        </svg>
                        {{ $submission->admission_year === 'first' ? 'Première Année' : 'Deuxième Année' }}
                    </span>
                    <span class="ml-3 text-sm text-blue-600">
                        ID: {{ $submission->submission_id }}
                    </span>
                </div>
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                    {{ $submission->status === 'approved' ? 'bg-green-100 text-green-800' :
                        ($submission->status === 'rejected' ? 'bg-red-100 text-red-800' :
                        'bg-yellow-100 text-yellow-800') }}">
                    {{ $submission->status === 'pending' ? 'En attente' :
                        ($submission->status === 'approved' ? 'Approuvé' :
                        ($submission->status === 'rejected' ? 'Rejeté' : 'En cours d\'examen')) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- Informations Personnelles -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations Personnelles</h2>
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="border-b border-gray-200">
                        <div class="grid grid-cols-2 px-4 py-3">
                            <div class="text-sm font-medium text-gray-500">Nom</div>
                            <div class="text-sm text-gray-900">{{ $submission->last_name }}</div>
                        </div>
                    </div>
                    <div class="border-b border-gray-200">
                        <div class="grid grid-cols-2 px-4 py-3">
                            <div class="text-sm font-medium text-gray-500">Prénom</div>
                            <div class="text-sm text-gray-900">{{ $submission->first_name }}</div>
                        </div>
                    </div>
                    <div class="border-b border-gray-200">
                        <div class="grid grid-cols-2 px-4 py-3">
                            <div class="text-sm font-medium text-gray-500">Email</div>
                            <div class="text-sm text-gray-900">{{ $submission->email }}</div>
                        </div>
                    </div>
                    <div class="border-b border-gray-200">
                        <div class="grid grid-cols-2 px-4 py-3">
                            <div class="text-sm font-medium text-gray-500">Téléphone</div>
                            <div class="text-sm text-gray-900">{{ $submission->phone }}</div>
                        </div>
                    </div>
                    <div class="border-b border-gray-200">
                        <div class="grid grid-cols-2 px-4 py-3">
                            <div class="text-sm font-medium text-gray-500">Programme</div>
                            <div class="text-sm text-gray-900">
                                {{ $programs[$submission->program] ?? $submission->program }}
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-gray-200">
                        <div class="grid grid-cols-2 px-4 py-3">
                            <div class="text-sm font-medium text-gray-500">Date de soumission</div>
                            <div class="text-sm text-gray-900">{{ $submission->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- Informations spécifiques à la deuxième année -->
                    @if($submission->admission_year === 'second')
                        <div class="border-b border-gray-200">
                            <div class="grid grid-cols-2 px-4 py-3">
                                <div class="text-sm font-medium text-gray-500">Établissement précédent</div>
                                <div class="text-sm text-gray-900">{{ $submission->previous_school ?? 'Non renseigné' }}</div>
                            </div>
                        </div>
                        <div class="border-b border-gray-200">
                            <div class="grid grid-cols-2 px-4 py-3">
                                <div class="text-sm font-medium text-gray-500">Programme précédent</div>
                                <div class="text-sm text-gray-900">{{ $submission->previous_program ?? 'Non renseigné' }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="grid grid-cols-2 px-4 py-3">
                                <div class="text-sm font-medium text-gray-500">Année validée</div>
                                <div class="text-sm text-gray-900">{{ $submission->year_completed ?? 'Non renseigné' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Documents</h2>
                <div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-200">
                    <!-- Photo d'identité -->
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Photo d'identité</span>
                        </div>
                        <div class="flex space-x-2">
                            @if($submission->photo_path)
                                <button @click.prevent="previewUrl='{{ route('admin.submissions.viewFile', ['submission' => $submission->id, 'fileType' => 'photo']) }}'; previewExt='{{ strtolower(pathinfo($submission->photo_path, PATHINFO_EXTENSION) ?: 'img') }}'; showPreview=true;"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553a2 2 0 00-2.828-2.828L12 7.172 7.275 2.447a2 2 0 00-2.828 2.828L9 10m6 0v10" />
                                    </svg>
                                    Prévisualiser
                                </button>
                                <a href="{{ route('admin.submissions.downloadFile', ['submission' => $submission->id, 'fileType' => 'photo']) }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Télécharger
                                </a>
                            @else
                                <span class="text-xs text-red-500">Fichier manquant</span>
                            @endif
                        </div>
                    </div>

                    <!-- Attestation BAC (uniquement pour première année) -->
                    @if($submission->admission_year === 'first')
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Attestation de BAC</span>
                            </div>
                            <div class="flex space-x-2">
                                @if($submission->bac_attestation_path)
                                    <button @click.prevent="previewUrl='{{ route('admin.submissions.viewFile', ['submission' => $submission->id, 'fileType' => 'bac']) }}'; previewExt='{{ strtolower(pathinfo($submission->bac_attestation_path, PATHINFO_EXTENSION) ?: 'pdf') }}'; showPreview=true;"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553a2 2 0 00-2.828-2.828L12 7.172 7.275 2.447a2 2 0 00-2.828 2.828L9 10m6 0v10" />
                                        </svg>
                                        Prévisualiser
                                    </button>
                                    <a href="{{ route('admin.submissions.downloadFile', ['submission' => $submission->id, 'fileType' => 'bac']) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Télécharger
                                    </a>
                                @else
                                    <span class="text-xs text-red-500">Fichier manquant</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Relevés de notes (uniquement pour deuxième année) -->
                    @if($submission->admission_year === 'second' && $submission->transcript_paths)
                        @foreach($submission->transcript_paths as $index => $path)
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Relevé de notes {{ $index + 1 }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click.prevent="previewUrl='{{ route('admin.submissions.viewFile', ['submission' => $submission->id, 'fileType' => 'transcript', 'index' => $index]) }}'; previewExt='{{ strtolower(pathinfo($path, PATHINFO_EXTENSION)) }}'; showPreview=true;"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553a2 2 0 00-2.828-2.828L12 7.172 7.275 2.447a2 2 0 00-2.828 2.828L9 10m6 0v10" />
                                        </svg>
                                        Prévisualiser
                                    </button>
                                    <a href="{{ route('admin.submissions.downloadFile', ['submission' => $submission->id, 'fileType' => 'transcript', 'index' => $index]) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- Lettre de motivation -->
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Lettre de motivation</span>
                        </div>
                        <div class="flex space-x-2">
                            @if($submission->motivation_letter_path)
                                <button @click.prevent="previewUrl='{{ route('admin.submissions.viewFile', ['submission' => $submission->id, 'fileType' => 'letter']) }}'; previewExt='{{ strtolower(pathinfo($submission->motivation_letter_path, PATHINFO_EXTENSION) ?: 'pdf') }}'; showPreview=true;"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553a2 2 0 00-2.828-2.828L12 7.172 7.275 2.447a2 2 0 00-2.828 2.828L9 10m6 0v10" />
                                    </svg>
                                    Prévisualiser
                                </button>
                                <a href="{{ route('admin.submissions.downloadFile', ['submission' => $submission->id, 'fileType' => 'letter']) }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Télécharger
                                </a>
                            @else
                                <span class="text-xs text-red-500">Fichier manquant</span>
                            @endif
                        </div>
                    </div>

                    <!-- Bulletins (uniquement pour première année) -->
                    @if($submission->admission_year === 'first' && $submission->report_paths && is_array($submission->report_paths))
                        @foreach($submission->report_paths as $index => $path)
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Bulletin {{ $index + 1 }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click.prevent="previewUrl='{{ route('admin.submissions.viewFile', ['submission' => $submission->id, 'fileType' => 'report', 'index' => $index]) }}'; previewExt='{{ strtolower(pathinfo($path, PATHINFO_EXTENSION)) }}'; showPreview=true;"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553a2 2 0 00-2.828-2.828L12 7.172 7.275 2.447a2 2 0 00-2.828 2.828L9 10m6 0v10" />
                                        </svg>
                                        Prévisualiser
                                    </button>
                                    <a href="{{ route('admin.submissions.downloadFile', ['submission' => $submission->id, 'fileType' => 'report', 'index' => $index]) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- Documents supplémentaires -->
                    @if($submission->additional_doc_paths && is_array($submission->additional_doc_paths) && count($submission->additional_doc_paths) > 0)
                        @foreach($submission->additional_doc_paths as $index => $path)
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Document supplémentaire {{ $index + 1 }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click.prevent="previewUrl='{{ route('admin.submissions.viewFile', ['submission' => $submission->id, 'fileType' => 'additional', 'index' => $index]) }}'; previewExt='{{ strtolower(pathinfo($path, PATHINFO_EXTENSION)) }}'; showPreview=true;"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553a2 2 0 00-2.828-2.828L12 7.172 7.275 2.447a2 2 0 00-2.828 2.828L9 10m6 0v10" />
                                        </svg>
                                        Prévisualiser
                                    </button>
                                    <a href="{{ route('admin.submissions.downloadFile', ['submission' => $submission->id, 'fileType' => 'additional', 'index' => $index]) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- Télécharger tous les documents -->
                    <div class="p-4 flex items-center justify-between bg-gray-50">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Tous les documents</span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('submission.download', $submission) }}"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Télécharger ZIP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Administratives -->
        <div class="mt-6 p-6 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes Administratives</h2>
            <form action="{{ route('admin.submissions.updateStatus', $submission) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Statut
                        </label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="pending" {{ $submission->status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="under_review" {{ $submission->status === 'under_review' ? 'selected' : '' }}>En cours d'examen</option>
                            <option value="approved" {{ $submission->status === 'approved' ? 'selected' : '' }}>Approuvé</option>
                            <option value="rejected" {{ $submission->status === 'rejected' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notes
                        </label>
                        <textarea id="admin_notes" name="admin_notes" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ $submission->admin_notes }}</textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de prévisualisation -->
    <div x-show="showPreview" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
        <div @click.away="showPreview=false" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-3xl w-full p-4">
            <div class="flex justify-end">
                <button @click="showPreview=false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2">
                <!-- PDF Preview -->
                <template x-if="previewExt === 'pdf'">
                    <iframe :src="previewUrl" class="w-full h-[600px]"></iframe>
                </template>

                <!-- Image Preview (png/jpg/jpeg/gif/svg/webp) -->
                <template x-if="['png','jpg','jpeg','gif','svg','webp','img'].includes(previewExt)">
                    <img :src="previewUrl" alt="Prévisualisation" class="max-w-full max-h-[600px] mx-auto" />
                </template>

                <!-- Other file types (doc, docx, etc.) -->
                <template x-if="!['pdf','png','jpg','jpeg','gif','svg','webp','img'].includes(previewExt)">
                    <div class="text-center">
                        <p class="text-gray-700 mb-4">Prévisualisation non disponible pour ce type de fichier.</p>
                        <a :href="previewUrl" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Télécharger le fichier
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@endsection
