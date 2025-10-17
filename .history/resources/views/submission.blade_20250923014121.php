@extends('layouts.app')

@section('title', 'Dépôt de dossier d\'admission')

@section('content')
    <style>
        /* Enhanced Animation System */
        .file-preview {
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

        /* Premium File Item Styling */
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

        /* Drag & Drop Enhancements */
        .drag-active {
            border-color: #3b82f6 !important;
            background-color: #f0f7ff !important;
            transform: scale(1.015);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        /* Premium Progress Bar */
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

        /* Status Indicators */
        .file-success {
            color: #10b981;
            font-weight: 500;
        }

        .file-error {
            color: #ef4444;
            font-weight: 500;
        }

        /* Premium Form Elements */
        .form-input-enhanced {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .form-input-enhanced:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        /* Section Styling */
        .form-section {
            padding: 2rem;
            border-bottom: 1px solid #f0f4f8;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        /* Button Enhancements */
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

        /* Card Styling */
        .form-card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        /* Drop Zone Enhancements */
        .file-drop-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #f9fafb;
        }

        .file-drop-zone:hover {
            background-color: #f0f7ff;
            border-color: #93c5fd;
        }

        /* Icon Styling */
        .input-icon {
            color: #9ca3af;
            transition: color 0.2s ease;
        }

        .form-input-enhanced:focus + .input-icon {
            color: #3b82f6;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .animate-fade-out {
            animation: fadeOut 0.3s ease-in forwards;
        }

        /* Year Selection Cards */
        .year-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 2px solid transparent;
        }

        .year-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .year-card.active {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
        }

        .hidden {
            display: none;
        }
    </style>

    <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Year Selection Section -->
            <div id="year-selection" class="mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Dépôt de dossier d'admission</h2>
                    <p class="text-lg text-gray-600">Sélectionnez l'année d'admission pour commencer</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Première Année Card -->
                    <div class="year-card active form-card bg-white p-6" data-year="first">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l9-5m-9 5v10" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">Première Année</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Admission en première année du cycle d'ingénieur</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Baccalauréat requis
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Concours d'entrée
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Filières: CS/ ME/ Electrical Engineering(EE)
                            </li>
                        </ul>
                    </div>

                    <!-- Deuxième Année Card -->
                    <div class="year-card form-card bg-white p-6" data-year="second">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l9-5m-9 5v10" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l9-5m-9 5v10" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">Deuxième Année</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Admission directe en deuxième année</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Bac+1 requis
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Admission sur dossier
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Enhanced Progress Bar (Hidden by default) -->
            <div id="progress-bar-container" class="mb-8 hidden">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-2xl font-bold text-gray-900" id="form-title">Dépôt de dossier d'admission</h2>
                    <span class="text-sm font-medium text-accent" id="step-indicator">Étape 1 sur 2</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-accent h-2.5 rounded-full" style="width: 50%"></div>
                </div>
            </div>

            <!-- Premium Form Card for First Year -->
            <div id="first-year-form" class="form-card bg-white hidden">
                <form action="{{ route('submission.store') }}" method="POST" enctype="multipart/form-data" class="submission-form" data-year="first">
                    @csrf
                    <input type="hidden" name="admission_year" value="first">

                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-accent mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Informations personnelles - Première Année
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Renseignez vos informations personnelles pour votre dossier.</p>
                        </div>

                        <!-- Rest of the first year form content remains the same -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- First Name -->
                            <div>
                                <label for="first_name_first" class="block text-sm font-medium text-gray-700 mb-1">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="first_name" id="first_name_first" required
                                           class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                           placeholder="Votre prénom" value="{{ $userData['first_name'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name_first" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="last_name" id="last_name_first" required
                                           class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                           placeholder="Votre nom" value="{{ $userData['last_name'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email_first" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email_first" required
                                           class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                           placeholder="email@exemple.com" value="{{ $userData['email'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone_first" class="block text-sm font-medium text-gray-700 mb-1">
                                    Téléphone <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <input type="tel" name="phone" id="phone_first" required
                                           class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                           placeholder="Votre numéro de téléphone" value="{{ $userData['phone'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Program Selection -->
                            <div class="sm:col-span-2">
                                <label for="program_first" class="block text-sm font-medium text-gray-700 mb-1">Programme souhaité <span class="text-red-500">*</span></label>
                                <select id="program_first" name="program" required
                                        class="form-input-enhanced mt-1 block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">Sélectionnez un programme</option>
                                    @foreach($programs as $value => $label)
                                        <option value="{{ $value }}" @if(old('program') == $value) selected @endif>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('program')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="form-section">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-accent mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                Documents requis - Première Année
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Veuillez télécharger tous les documents requis pour votre admission.</p>
                        </div>

                        <!-- Photo Upload -->
                        <div class="space-y-3 mb-6">
                            <label class="block text-sm font-medium text-gray-700">
                                Photo d'identité <span class="text-red-500">*</span>
                            </label>
                            <div class="file-drop-zone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-all duration-300 cursor-pointer relative"
                                 data-input="photo_first">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-accent hover:text-accent-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Télécharger une photo</span>
                                            <input id="photo_first" name="photo" type="file" class="sr-only" accept="image/*" required>
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG (max 2MB)</p>
                                </div>
                            </div>
                            <div id="photo_first-preview" class="hidden mt-2"></div>
                        </div>

                        <!-- Rest of the document sections with updated IDs -->
                        <!-- BAC Attestation -->
                        <div class="space-y-3 mb-6">
                            <label class="block text-sm font-medium text-gray-700">
                                Attestation de réussite au BAC <span class="text-red-500">*</span>
                            </label>
                            <div class="file-drop-zone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-all duration-300 cursor-pointer relative"
                                 data-input="bac_attestation_first">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-accent hover:text-accent-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Télécharger le document</span>
                                            <input id="bac_attestation_first" name="bac_attestation" type="file" class="sr-only" accept=".pdf" required>
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF (max 5MB)</p>
                                </div>
                            </div>
                            <div id="bac_attestation_first-preview" class="hidden mt-2"></div>
                        </div>

                        <!-- Rest of the document sections continue with similar ID updates -->

                    </div>

                    <!-- Form Actions -->
                    <div class="form-section bg-gray-50 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <button type="button" class="back-to-selection inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Retour au choix
                            </button>
                            <button type="submit" class="submit-button inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Soumettre le dossier
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Premium Form Card for Second Year -->
            <div id="second-year-form" class="form-card bg-white hidden">
                <form action="{{ route('submission.store') }}" method="POST" enctype="multipart/form-data" class="submission-form" data-year="second">
                    @csrf
                    <input type="hidden" name="admission_year" value="second">

                    <!-- Personal Information Section for Second Year -->
                    <div class="form-section">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-accent mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Informations personnelles - Deuxième Année
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Renseignez vos informations personnelles pour votre dossier.</p>
                        </div>

                        <!-- Similar form structure as first year but with different IDs -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- First Name -->
                            <div>
                                <label for="first_name_second" class="block text-sm font-medium text-gray-700 mb-1">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="first_name" id="first_name_second" required
                                           class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                           placeholder="Votre prénom" value="{{ $userData['first_name'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name_second" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="last_name" id="last_name_second" required
                                           class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-3"
                                           placeholder="Votre nom" value="{{ $userData['last_name'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Add specific fields for second year admission -->
                            <div class="sm:col-span-2">
                                <label for="previous_school" class="block text-sm font-medium text-gray-700 mb-1">
                                    Établissement d'origine <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="previous_school" id="previous_school" required
                                       class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-3"
                                       placeholder="Nom de votre établissement précédent">
                            </div>

                            <div>
                                <label for="previous_program" class="block text-sm font-medium text-gray-700 mb-1">
                                    Programme précédent <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="previous_program" id="previous_program" required
                                       class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-3"
                                       placeholder="Programme suivi précédemment">
                            </div>

                            <div>
                                <label for="year_completed" class="block text-sm font-medium text-gray-700 mb-1">
                                    Année validée <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="year_completed" id="year_completed" required min="1" max="1"
                                       class="form-input-enhanced focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-3"
                                       placeholder="1">
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section for Second Year -->
                    <div class="form-section">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-accent mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                Documents requis - Deuxième Année
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Veuillez télécharger tous les documents requis pour votre admission.</p>
                        </div>

                        <!-- Similar document structure as first year but with different requirements -->
                        <!-- Add specific documents for second year admission -->
                    </div>

                    <!-- Form Actions -->
                    <div class="form-section bg-gray-50 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <button type="button" class="back-to-selection inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Retour au choix
                            </button>
                            <button type="submit" class="submit-button inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Soumettre le dossier
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        class YearSelectionManager {
            constructor() {
                this.currentYear = null;
                this.init();
            }

            init() {
                this.setupYearCards();
                this.setupBackButtons();
            }

            setupYearCards() {
                const yearCards = document.querySelectorAll('.year-card');

                yearCards.forEach(card => {
                    card.addEventListener('click', (e) => {
                        const year = card.dataset.year;
                        this.selectYear(year);
                    });
                });
            }

            setupBackButtons() {
                const backButtons = document.querySelectorAll('.back-to-selection');

                backButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        this.showYearSelection();
                    });
                });
            }

            selectYear(year) {
                this.currentYear = year;

                // Update active card
                document.querySelectorAll('.year-card').forEach(card => {
                    card.classList.remove('active');
                });
                document.querySelector(`.year-card[data-year="${year}"]`).classList.add('active');

                // Hide year selection
                document.getElementById('year-selection').classList.add('hidden');

                // Show progress bar and form
                document.getElementById('progress-bar-container').classList.remove('hidden');
                document.getElementById('form-title').textContent = `Dépôt de dossier - ${year === 'first' ? 'Première Année' : 'Deuxième Année'}`;

                // Hide all forms
                document.querySelectorAll('[id$="-year-form"]').forEach(form => {
                    form.classList.add('hidden');
                });

                // Show selected form
                document.getElementById(`${year}-year-form`).classList.remove('hidden');

                // Initialize file upload for this form
                this.initializeForm(year);
            }

            showYearSelection() {
                // Show year selection
                document.getElementById('year-selection').classList.remove('hidden');

                // Hide progress bar and forms
                document.getElementById('progress-bar-container').classList.add('hidden');
                document.querySelectorAll('[id$="-year-form"]').forEach(form => {
                    form.classList.add('hidden');
                });
            }

            initializeForm(year) {
                // Initialize file upload manager for the specific form
                new FileUploadManager(year);

                // Initialize form submission handler for the specific form
                new FormSubmissionHandler(year);
            }
        }

        class FileUploadManager {
            constructor(year) {
                this.year = year;
                this.fileData = new Map();
                this.init();
            }

            init() {
                this.setupDropZones();
                this.setupFileInputs();
            }

            setupDropZones() {
                const form = document.getElementById(`${this.year}-year-form`);
                const dropZones = form.querySelectorAll('.file-drop-zone');

                dropZones.forEach(zone => {
                    const inputId = zone.dataset.input;
                    const input = form.querySelector(`#${inputId}`);

                    // Click to open file dialog
                    zone.addEventListener('click', (e) => {
                        if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL') {
                            input.click();
                        }
                    });

                    // Drag and drop events
                    zone.addEventListener('dragenter', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        zone.classList.add('drag-active');
                    });

                    zone.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        zone.classList.add('drag-active');
                    });

                    zone.addEventListener('dragleave', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        if (!zone.contains(e.relatedTarget)) {
                            zone.classList.remove('drag-active');
                        }
                    });

                    zone.addEventListener('drop', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        zone.classList.remove('drag-active');

                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            const dataTransfer = new DataTransfer();
                            if (input.multiple) {
                                for (let i = 0; i < input.files.length; i++) {
                                    dataTransfer.items.add(input.files[i]);
                                }
                            }
                            for (let i = 0; i < files.length; i++) {
                                dataTransfer.items.add(files[i]);
                            }
                            input.files = dataTransfer.files;

                            const event = new Event('change', { bubbles: true });
                            input.dispatchEvent(event);
                        }
                    });
                });
            }

            setupFileInputs() {
                const form = document.getElementById(`${this.year}-year-form`);
                const inputs = [
                    { id: `photo_${this.year}`, isImage: true, multiple: false },
                    { id: `bac_attestation_${this.year}`, isImage: false, multiple: false },
                    { id: `motivation_letter_${this.year}`, isImage: false, multiple: false },
                    { id: `reports_${this.year}`, isImage: false, multiple: true },
                    { id: `additional_docs_${this.year}`, isImage: false, multiple: true }
                ];

                inputs.forEach(({ id, isImage, multiple }) => {
                    const input = form.querySelector(`#${id}`);
                    if (!input) return;

                    const preview = form.querySelector(`#${id}-preview`);
                    if (!preview) return;

                    input.addEventListener('change', (e) => {
                        const files = Array.from(e.target.files);

                        if (files.length > 0) {
                            if (!multiple) {
                                preview.innerHTML = '';
                            }

                            files.forEach((file, index) => {
                                this.createFilePreview(file, preview, isImage, id, index);
                            });

                            preview.classList.remove('hidden');
                        } else {
                            preview.classList.add('hidden');
                        }
                    });
                });
            }

            createFilePreview(file, container, isImage, inputId, fileIndex) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileId = `file-${inputId}-${Date.now()}-${fileIndex}`;

                const { icon, color } = this.getFileIcon(file.type);

                const fileElement = document.createElement('div');
                fileElement.className = 'file-item bg-white rounded-lg p-4 file-preview shadow-sm';
                fileElement.id = fileId;

                fileElement.innerHTML = `
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 pt-1">
                            <svg class="h-6 w-6 ${color}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${this.getFileIconSVG(icon)}
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate" title="${file.name}">
                                ${file.name}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                ${fileSize} MB • ${this.getFileType(file.type)}
                            </div>
                            <div class="progress-bar mt-2">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                            <div class="file-status text-xs mt-1 text-gray-600">
                                <svg class="inline h-3 w-3 text-gray-400 animate-spin mr-1" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Chargement en cours...
                            </div>
                        </div>
                        <button type="button" class="delete-file text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 -mt-1 -mr-1" title="Supprimer">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    ${isImage ? '<div class="file-image-preview mt-3"></div>' : ''}
                `;

                container.appendChild(fileElement);

                const deleteBtn = fileElement.querySelector('.delete-file');
                deleteBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.removeFile(inputId, fileIndex);
                    fileElement.remove();

                    if (container.children.length === 0) {
                        container.classList.add('hidden');
                    }
                });

                this.simulateUpload(fileElement, file, isImage);
            }

            simulateUpload(fileElement, file, isImage) {
                const progressFill = fileElement.querySelector('.progress-fill');
                const statusElement = fileElement.querySelector('.file-status');

                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 15 + 5;

                    if (progress >= 100) {
                        progress = 100;
                        clearInterval(interval);

                        progressFill.style.width = '100%';
                        statusElement.innerHTML = `
                            <svg class="inline h-3 w-3 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="file-success">Chargement terminé</span>
                        `;

                        if (isImage && file.type.startsWith('image/')) {
                            this.showImagePreview(file, fileElement);
                        }
                    } else {
                        progressFill.style.width = `${progress}%`;
                    }
                }, 150);
            }

            showImagePreview(file, fileElement) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewContainer = fileElement.querySelector('.file-image-preview');
                    if (previewContainer) {
                        previewContainer.innerHTML = `
                            <img src="${e.target.result}" alt="Aperçu" class="max-h-40 rounded-md border border-gray-200 shadow-sm mx-auto">
                        `;
                    }
                };
                reader.readAsDataURL(file);
            }

            getFileIcon(fileType) {
                if (fileType.startsWith('image/')) {
                    return { icon: 'image', color: 'text-blue-500' };
                } else if (fileType === 'application/pdf') {
                    return { icon: 'document-text', color: 'text-red-500' };
                } else if (fileType.includes('word') || fileType.includes('document')) {
                    return { icon: 'document-text', color: 'text-blue-600' };
                } else {
                    return { icon: 'document', color: 'text-gray-500' };
                }
            }

            getFileIconSVG(icon) {
                const icons = {
                    'image': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />',
                    'document': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
                    'document-text': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />'
                };
                return icons[icon] || icons['document'];
            }

            getFileType(fileType) {
                if (fileType.startsWith('image/')) {
                    return 'Image';
                } else if (fileType === 'application/pdf') {
                    return 'PDF';
                } else if (fileType.includes('word') || fileType.includes('document')) {
                    return 'Document Word';
                } else {
                    return 'Fichier';
                }
            }

            removeFile(inputId, fileIndex) {
                const input = document.getElementById(inputId);
                if (input.multiple) {
                    const files = Array.from(input.files);
                    files.splice(fileIndex, 1);

                    const dataTransfer = new DataTransfer();
                    files.forEach(file => dataTransfer.items.add(file));
                    input.files = dataTransfer.files;
                } else {
                    input.value = '';
                }
            }
        }

        class FormSubmissionHandler {
            constructor(year) {
                this.year = year;
                this.form = document.getElementById(`${year}-year-form`);
                this.submitBtn = this.form.querySelector('.submit-button');
                this.init();
            }

            init() {
                this.form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleSubmit();
                });
            }

            async handleSubmit() {
                this.submitBtn.disabled = true;
                this.submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Envoi en cours...
                `;

                try {
                    const formData = new FormData(this.form);
                    const response = await fetch(this.form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            let errorMessages = [];
                            for (const [field, errors] of Object.entries(data.errors)) {
                                errorMessages.push(...errors);
                            }
                            throw new Error(errorMessages.join('\n'));
                        }
                        throw new Error(data.message || 'Erreur lors de la soumission');
                    }

                    this.showSuccessMessage();

                    if (data.submission_id) {
                        window.location.href = `/submission/success/${data.submission_id}`;
                    } else if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.href = '/';
                    }

                } catch (error) {
                    this.showErrorMessage(error.message);
                } finally {
                    this.submitBtn.disabled = false;
                    this.submitBtn.innerHTML = `
                        <svg class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Soumettre le dossier
                    `;
                }
            }

            showSuccessMessage() {
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center animate-fade-in-up';
                message.innerHTML = `
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Dossier soumis avec succès !
                `;

                document.body.appendChild(message);

                setTimeout(() => {
                    message.classList.add('animate-fade-out');
                    setTimeout(() => {
                        message.remove();
                    }, 300);
                }, 3000);
            }

            showErrorMessage(msg) {
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center animate-fade-in-up';
                message.innerHTML = `
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    ${msg}
                `;

                document.body.appendChild(message);

                setTimeout(() => {
                    message.classList.add('animate-fade-out');
                    setTimeout(() => {
                        message.remove();
                    }, 300);
                }, 3000);
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new YearSelectionManager();
        });
    </script>
@endsection
