@extends('layouts.app')

@section('title', 'Contactez-Nous - Support et Aide')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                Nous sommes là pour vous aider
            </h1>
            <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
                Contactez notre équipe pour toute question ou assistance
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden">
                <div class="p-8 sm:p-10">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Votre nom</label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Sujet</label>
                            <select id="subject" name="subject" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500">
                                <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>Problème technique</option>
                                <option value="account" {{ old('subject') == 'account' ? 'selected' : '' }}>Question sur mon compte</option>
                                <option value="courses" {{ old('subject') == 'courses' ? 'selected' : '' }}>Information sur les cours</option>
                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Autre demande</option>
                            </select>
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea id="message" name="message" rows="4" required
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Info & FAQ -->
            <div class="space-y-8">
                <!-- Contact Info -->
                <div class="bg-white shadow-xl rounded-3xl overflow-hidden">
                    <div class="p-8 sm:p-10 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
                        <h2 class="text-2xl font-bold mb-6">Nos coordonnées</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-100">Email</p>
                                    <p class="text-lg">admissions@bit.bf</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-100">Adresse</p>
                                    <p class="text-lg">Burkina Institute of Technology, B.P. 322 Koudougou Burkina Faso</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="bg-white shadow-xl rounded-3xl overflow-hidden">
                    <div class="p-8 sm:p-10">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Questions fréquentes</h2>
                        <div class="space-y-4">
                            <!-- FAQ Item 1 -->
                            <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                    <span class="font-medium text-gray-900">Comment créer un compte ?</span>
                                    <svg :class="{'transform rotate-180': open}" class="h-5 w-5 text-gray-500 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-2 text-gray-600">
                                    <p>Cliquez sur "Connexion"  en haut à droite de la page puis sur "Creer un compte", remplissez le formulaire avec vos informations et validez votre email.</p>
                                </div>
                            </div>

                            <!-- FAQ Item 2 -->
                            <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                    <span class="font-medium text-gray-900">Comment accéder à mes cours ?</span>
                                    <svg :class="{'transform rotate-180': open}" class="h-5 w-5 text-gray-500 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-2 text-gray-600">
                                    <p>Une fois connecté, rendez-vous dans votre tableau de bord. Tous vos cours inscrits y sont listés. Cliquez sur un cours pour y accéder.</p>
                                </div>
                            </div>

                            <!-- FAQ Item 3 -->
                            <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                    <span class="font-medium text-gray-900">Quels sont les moyens de paiement acceptés ?</span>
                                    <svg :class="{'transform rotate-180': open}" class="h-5 w-5 text-gray-500 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-2 text-gray-600">
                                    <p>Nous acceptons les cartes de crédit (Visa, MasterCard), Par Liquidité et les virements bancaires.</p>
                                </div>
                            </div>

                            <!-- FAQ Item 4 -->
                            <div x-data="{ open: false }" class="border-b border-gray-200 pb-4">
                                <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                                    <span class="font-medium text-gray-900">Comment obtenir une attestation de réussite ?</span>
                                    <svg :class="{'transform rotate-180': open}" class="h-5 w-5 text-gray-500 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-2 text-gray-600">
                                    <p>Après avoir réussi tous les examens d'un cours, votre attestation sera automatiquement disponible dans votre espace "Certifications".</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <a href="#" class="text-blue-600 font-medium hover:text-blue-800">Voir toutes les questions →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
