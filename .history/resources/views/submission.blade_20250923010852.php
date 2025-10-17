@extends('layouts.app')

@section('title', 'Inscription - Première et Deuxième Année')

@section('content')
    <style>
        /* Enhanced Animation System */
        .form-card {
            animation: slideIn 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .file-item {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .file-item:hover {
            background-color: #f8fafc;
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .file-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .file-item:hover::before {
            opacity: 1;
        }

        .drag-active {
            border-color: #3b82f6 !important;
            background-color: #f0f7ff !important;
            transform: scale(1.015);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .progress-bar {
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 3px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0.15) 0%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0.15) 100%
            );
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .file-success {
            color: #10b981;
            font-weight: 500;
        }

        .file-error {
            color: #ef4444;
            font-weight: 500;
        }

        .form-input-enhanced {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .form-input-enhanced:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .form-section {
            padding: 2rem;
            border-bottom: 1px solid #f0f4f8;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .btn-primary {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(59, 130, 246, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
            background: linear-gradient(to right, #2563eb, #1d4ed8);
        }

        .file-drop-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #f9fafb;
        }

        .file-drop-zone:hover {
            background-color: #f0f7ff;
            border-color: #93c5fd;
        }

        .input-icon {
            color: #9ca3af;
            transition: color 0.2s ease;
        }

        .form-input-enhanced:focus + .input-icon {
            color: #3b82f6;
        }

        /* Card Selection Styles */
        .card-selection {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
        }

        .card-selection:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .card-selection.selected {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
        }

        .card-selection.selected::before {
            content: '✓';
            position: absolute;
            top: -10px;
            right: -10px;
            background: #3b82f6;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        /* Filiere Colors */
        .filiere-cs { border-left: 4px solid #3b82f6; }
        .filiere-me { border-left: 4px solid #10b981; }
        .filiere-ee { border-left: 4px solid #f59e0b; }

        .filiere-badge-cs { background-color: #dbeafe; color: #1e40af; }
        .filiere-badge-me { background-color: #d1fae5; color: #047857; }
        .filiere-badge-ee { background-color: #fef3c7; color: #92400e; }
    </style>

    <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Inscription Universitaire</h1>
                <p class="text-lg text-gray-600">Choisissez votre niveau d'inscription et complétez votre dossier</p>
            </div>

            <!-- Year Selection Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Première Année Card -->
                <div class="card-selection relative bg-white rounded-xl shadow-sm p-6 cursor-pointer selected"
                     data-year="first" id="first-year-card">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Première Année</h3>
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                Nouveaux étudiants
                            </span>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5"/>
                            </svg>
                        </div>
                    </div>

                    <p class="text-gray-600 mb-4">Inscription pour les nouveaux étudiants souhaitant intégrer la première année.</p>

                    <div class="space-y-2">
                        <h4 class="font-medium text-gray-900">Filières disponibles :</h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="filiere-badge-cs px-3 py-1 rounded-full text-sm font-medium">Computer Science (CS)</span>
                            <span class="filiere-badge-me px-3 py-1 rounded-full text-sm font-medium">Mechanical Engineering (ME)</span>
                            <span class="filiere-badge-ee px-3 py-1 rounded-full text-sm font-medium">Electrical Engineering (EE)</span>
                        </div>
                    </div>
                </div>

                <!-- Deuxième Année Card -->
                <div class="card-selection relative bg-white rounded-xl shadow-sm p-6 cursor-pointer"
                     data-year="second" id="second-year-card">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Deuxième Année</h3>
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                Étudiants avancés
                            </span>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>

                    <p class="text-gray-600 mb-4">Inscription pour les étudiants ayant validé leur première année.</p>

                    <div class="space-y-2">
                        <h4 class="font-medium text-gray-900">Filières disponibles :</h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="filiere-badge-cs px-3 py-1 rounded-full text-sm font-medium">Computer Science (CS)</span>
                            <span class="filiere-badge-me px-3 py-1 rounded-full text-sm font-medium">Mechanical Engineering (ME)</span>
                            <span class="filiere-badge-ee px-3 py-1 rounded-full text-sm font-medium">Electrical Engineering (EE)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Form -->
            <div class="form-card bg-white rounded-xl shadow-lg">
                <form action="{{ route('submission.store') }}" method="POST" enctype="multipart/form-data" id="submission-form">
                    @csrf
                    <input type="hidden" name="academic_year" id="academic_year" value="first">

                    <!-- Progress Bar -->
                    <div class="px-6 pt-6">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-xl font-bold text-gray-900" id="form-title">Inscription - Première Année</h2>
                            <span class="text-sm font-medium text-blue-600">Étape 1 sur 2</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 50%"></div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Informations personnelles
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" id="first_name" required
                                       class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4"
                                       placeholder="Votre prénom" value="{{ $userData['first_name'] ?? '' }}">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" id="last_name" required
                                       class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4"
                                       placeholder="Votre nom" value="{{ $userData['last_name'] ?? '' }}">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" required
                                       class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4"
                                       placeholder="email@exemple.com" value="{{ $userData['email'] ?? '' }}">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Téléphone <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone" required
                                       class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4"
                                       placeholder="Votre numéro de téléphone" value="{{ $userData['phone'] ?? '' }}">
                            </div>

                            <!-- Filiere Selection -->
                            <div class="sm:col-span-2">
                                <label for="filiere" class="block text-sm font-medium text-gray-700 mb-1">
                                    Filière souhaitée <span class="text-red-500">*</span>
                                </label>
                                <select id="filiere" name="filiere" required
                                        class="form-input-enhanced mt-1 block w-full py-3 px-4 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">Sélectionnez votre filière</option>
                                    <option value="CS" class="filiere-cs">Computer Science (CS)</option>
                                    <option value="ME" class="filiere-me">Mechanical Engineering (ME)</option>
                                    <option value="EE" class="filiere-ee">Electrical Engineering (EE)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section - Dynamic based on year -->
                    <div class="form-section">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span id="documents-title">Documents requis - Première Année</span>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500" id="documents-description">
                                Veuillez télécharger tous les documents requis pour votre inscription en première année.
                            </p>
                        </div>

                        <!-- Common Documents -->
                        <div class="space-y-6 mt-6">
                            <!-- Photo Upload -->
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Photo d'identité <span class="text-red-500">*</span>
                                </label>
                                <div class="file-drop-zone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-all duration-300 cursor-pointer"
                                     data-input="photo">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span>Télécharger une photo</span>
                                                <input id="photo" name="photo" type="file" class="sr-only" accept="image/*" required>
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG (max 2MB)</p>
                                    </div>
                                </div>
                                <div id="photo-preview" class="hidden mt-2"></div>
                            </div>

                            <!-- BAC Attestation (First Year) -->
                            <div class="space-y-3 first-year-doc">
                                <label class="block text-sm font-medium text-gray-700">
                                    Attestation de réussite au BAC <span class="text-red-500">*</span>
                                </label>
                                <div class="file-drop-zone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-all duration-300 cursor-pointer"
                                     data-input="bac_attestation">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span>Télécharger le document</span>
                                                <input id="bac_attestation" name="bac_attestation" type="file" class="sr-only" accept=".pdf">
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF (max 5MB)</p>
                                    </div>
                                </div>
                                <div id="bac_attestation-preview" class="hidden mt-2"></div>
                            </div>

                            <!-- First Year Transcript (Second Year) -->
                            <div class="space-y-3 second-year-doc hidden">
                                <label class="block text-sm font-medium text-gray-700">
                                    Relevé de notes de première année <span class="text-red-500">*</span>
                                </label>
                                <div class="file-drop-zone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-all duration-300 cursor-pointer"
                                     data-input="first_year_transcript">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span>Télécharger le relevé</span>
                                                <input id="first_year_transcript" name="first_year_transcript" type="file" class="sr-only" accept=".pdf">
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF (max 5MB)</p>
                                    </div>
                                </div>
                                <div id="first_year_transcript-preview" class="hidden mt-2"></div>
                            </div>

                            <!-- Motivation Letter -->
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Lettre de motivation <span class="text-red-500">*</span>
                                </label>
                                <div class="file-drop-zone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-all duration-300 cursor-pointer"
                                     data-input="motivation_letter">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span>Télécharger la lettre</span>
                                                <input id="motivation_letter" name="motivation_letter" type="file" class="sr-only" accept=".pdf,.doc,.docx" required>
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX (max 5MB)</p>
                                    </div>
                                </div>
                                <div id="motivation_letter-preview" class="hidden mt-2"></div>
                            </div>

                            <!-- Additional Documents -->
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Documents supplémentaires (optionnels)
                                </label>
                                <div class="file-drop-zone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-all duration-300 cursor-pointer"
                                    data-input="additional_docs">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m4-6H8"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span>Télécharger les fichiers</span>
                                                <input id="additional_docs" name="additional_docs[]" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpeg,.jpg,.png" multiple>
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG (max 5MB chacun)</p>
                                    </div>
                                </div>
                                <div id="additional_docs-preview" class="hidden mt-2 grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-section bg-gray-50 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <span id="selected-year-display">Inscription en Première Année</span>
                                •
                                <span id="selected-filiere-display" class="font-medium">Filière non sélectionnée</span>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    Annuler
                                </button>
                                <button type="submit" id="submit-button" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    Soumettre le dossier
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        class YearSelectionManager {
            constructor() {
                this.currentYear = 'first';
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.updateFormDisplay();
            }

            setupEventListeners() {
                const firstYearCard = document.getElementById('first-year-card');
                const secondYearCard = document.getElementById('second-year-card');

                firstYearCard.addEventListener('click', () => this.selectYear('first'));
                secondYearCard.addEventListener('click', () => this.selectYear('second'));

                // Update filiere display when selection changes
                document.getElementById('filiere').addEventListener('change', (e) => {
                    this.updateFiliereDisplay(e.target.value);
                });
            }

            selectYear(year) {
                this.currentYear = year;
                this.updateCardSelection();
                this.updateFormDisplay();
                this.updateDocumentRequirements();
            }

            updateCardSelection() {
                const firstYearCard = document.getElementById('first-year-card');
                const secondYearCard = document.getElementById('second-year-card');

                firstYearCard.classList.toggle('selected', this.currentYear === 'first');
                secondYearCard.classList.toggle('selected', this.currentYear === 'second');
            }

            updateFormDisplay() {
                const formTitle = document.getElementById('form-title');
                const documentsTitle = document.getElementById('documents-title');
                const documentsDescription = document.getElementById('documents-description');
                const academicYearInput = document.getElementById('academic_year');
                const selectedYearDisplay = document.getElementById('selected-year-display');

                if (this.currentYear === 'first') {
                    formTitle.textContent = 'Inscription - Première Année';
                    documentsTitle.textContent = 'Documents requis - Première Année';
                    documentsDescription.textContent = 'Veuillez télécharger tous les documents requis pour votre inscription en première année.';
                    selectedYearDisplay.textContent = 'Inscription en Première Année';
                    academicYearInput.value = 'first';
                } else {
                    formTitle.textContent = 'Inscription - Deuxième Année';
                    documentsTitle.textContent = 'Documents requis - Deuxième Année';
                    documentsDescription.textContent = 'Veuillez télécharger tous les documents requis pour votre inscription en deuxième année.';
                    selectedYearDisplay.textContent = 'Inscription en Deuxième Année';
                    academicYearInput.value = 'second';
                }
            }

            updateDocumentRequirements() {
                const firstYearDocs = document.querySelectorAll('.first-year-doc');
                const secondYearDocs = document.querySelectorAll('.second-year-doc');
                const bacAttestationInput = document.getElementById('bac_attestation');
                const transcriptInput = document.getElementById('first_year_transcript');

                if (this.currentYear === 'first') {
                    firstYearDocs.forEach(doc => doc.classList.remove('hidden'));
                    secondYearDocs.forEach(doc => doc.classList.add('hidden'));
                    bacAttestationInput.required = true;
                    transcriptInput.required = false;
                } else {
                    firstYearDocs.forEach(doc => doc.classList.add('hidden'));
                    secondYearDocs.forEach(doc => doc.classList.remove('hidden'));
                    bacAttestationInput.required = false;
                    transcriptInput.required = true;
                }
            }

            updateFiliereDisplay(filiere) {
                const filiereDisplay = document.getElementById('selected-filiere-display');
                const filiereNames = {
                    'CS': 'Computer Science (CS)',
                    'ME': 'Mechanical Engineering (ME)',
                    'EE': 'Electrical Engineering (EE)'
                };

                filiereDisplay.textContent = filiere ? filiereNames[filiere] : 'Filière non sélectionnée';

                // Update color based on filiere
                const colors = {
                    'CS': 'text-blue-600',
                    'ME': 'text-green-600',
                    'EE': 'text-yellow-600'
                };

                // Remove previous color classes
                filiereDisplay.className = filiereDisplay.className.replace(/text-(blue|green|yellow)-600/g, '');

                if (filiere && colors[filiere]) {
                    filiereDisplay.classList.add(colors[filiere], 'font-medium');
                }
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new YearSelectionManager();
            // Note: FileUploadManager and FormSubmissionHandler would be included here
            // from the original code, but are omitted for brevity
        });
    </script>
@endsection
