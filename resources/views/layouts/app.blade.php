<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Language" content="{{ app()->getLocale() }}">
    <title>BIT - {{ $title ?? __('Home') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#4361ee',
                            dark: '#3a56d4'
                        },
                        secondary: {
                            DEFAULT: '#3f37c9',
                            dark: '#3730a3'
                        },
                        accent: {
                            DEFAULT: '#f72585',
                            dark: '#e5177b'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <!-- Font Inter via Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Fichiers CSS personnalisés -->
    @stack('styles')

    <!-- Script pour s'assurer que la langue est correctement définie -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si l'attribut lang est correct
            if (document.documentElement.lang !== '{{ app()->getLocale() }}') {
                document.documentElement.lang = '{{ app()->getLocale() }}';
            }
        });
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        @hasSection('hide_navbar')
            {{-- La navbar ne sera pas affichée --}}
        @else
            <nav class="bg-white shadow-lg" x-data="{ isOpen: false }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                                <img class="h-8 w-auto" src="/images/logo-institut.png" alt="Logo Institut">
                                {{-- <img class="h-8 w-auto" src="http://bit.bf/wp-content/uploads/2018/10/logo-bit-3_student_png-signetbit.png" alt="Logo Institut"> --}}
                            </a>
                            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <!-- Liens de navigation desktop -->
                                <a href="{{ route('home') }}"
                                    class="{{ request()->routeIs('home') ? 'border-accent text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    {{ __('Accueil') }}
                                </a>
                                <a href="{{ route('about') }}"
                                    class="{{ request()->routeIs('about') ? 'border-accent text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    {{ __('A propos') }}
                                </a>
                                <a href="{{ route('submission.create') }}"
                                    class="{{ request()->routeIs('submission') ? 'border-accent text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    {{ __('Admission') }}
                                </a>
                                @guest
                                <a href="{{ route('filetracking') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    {{ __('Suivre mon dossier') }}
                                </a>
                                @else
                                    @if(auth()->user()->role !== 'admin')
                                    <a href="{{ route('filetracking') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        {{ __('Suivre mon dossier') }}
                                    </a>
                                    @endif
                                @endguest
                                <a href="{{ route('contact') }}"
                                    class="{{ request()->routeIs('contact') ? 'border-accent text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    {{ __('Contact / Aide') }}
                                </a>
                                {{-- <a href="{{ route('test.lang') }}">
                                    Test lang
                                </a>
                                <a href="{{ route('example.translation') }}">
                                    Example
                                </a> --}}
                            </div>
                        </div>

                        <!-- Section droite (profil, notifications) -->
                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <!-- Sélecteur de langue -->
                            <div class="mr-3 relative" x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center px-2 py-1 border border-gray-200 text-sm font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-primary transition duration-150 ease-in-out">
                                    <span>{{ __('Language') }}: {{ app()->getLocale() === 'fr' ? 'FR' : (app()->getLocale() === 'en' ? 'EN' : 'DE') }}</span>
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open"
                                     @click.away="open = false"
                                     class="origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                     role="menu"
                                     aria-orientation="vertical"
                                     aria-labelledby="language-menu">
                                    <div class="py-1" role="none">
                                        <form method="POST" action="{{ route('language.switch.post') }}">
                                            @csrf
                                            <input type="hidden" name="locale" value="fr">
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm {{ app()->getLocale() === 'fr' ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}" role="menuitem">{{ __('French') }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('language.switch.post') }}">
                                            @csrf
                                            <input type="hidden" name="locale" value="en">
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm {{ app()->getLocale() === 'en' ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}" role="menuitem">{{ __('English') }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('language.switch.post') }}">
                                            @csrf
                                            <input type="hidden" name="locale" value="de">
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm {{ app()->getLocale() === 'de' ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}" role="menuitem">{{ __('German') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition duration-150 ease-in-out">
                                        <i class="fas fa-tachometer-alt mr-2"></i>
                                        {{ __('Dashboard') }}
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        {{ __('Déconnexion') }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition duration-150 ease-in-out">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    {{ __('Connexion') }}
                                </a>
                            @endauth
                        </div>

                        <!-- Bouton mobile menu -->
                        <div class="flex items-center sm:hidden">
                            <button @click="isOpen = !isOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary">
                                <span class="sr-only">Ouvrir le menu principal</span>
                                <svg x-show="!isOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg x-show="isOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menu mobile -->
                <div x-show="isOpen" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <a href="{{ route('home') }}"
                            class="{{ request()->routeIs('home') ? 'bg-primary-50 border-accent text-gray-900' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            {{ __('Accueil') }}
                        </a>
                        <a href="{{ route('about') }}"
                            class="{{ request()->routeIs('about') ? 'bg-primary-50 border-accent text-gray-900' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            {{ __('A propos') }}
                        </a>
                        <a href="{{ route('submission') }}"
                            class="{{ request()->routeIs('submission') ? 'bg-primary-50 border-accent text-gray-900' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            {{ __('Admission') }}
                        </a>
                        <a href={{ route('filetracking') }} class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            {{ __('Suivre mon dossier') }}
                        </a>
                        <a href="{{ route('contact') }}"
                            class="{{ request()->routeIs('contact') ? 'bg-primary-50 border-accent text-gray-900' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            {{ __('Contact / Aide') }}
                        </a>

                        <!-- Sélecteur de langue mobile -->
                        <div class="border-t border-gray-200 pt-2">
                            <div class="px-3 py-1 text-xs font-semibold text-gray-500">{{ __('Language') }}: {{ app()->getLocale() === 'fr' ? 'FR' : (app()->getLocale() === 'en' ? 'EN' : 'DE') }}</div>
                            <form method="POST" action="{{ route('language.switch.post') }}" class="block">
                                @csrf
                                <input type="hidden" name="locale" value="fr">
                                <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium {{ app()->getLocale() === 'fr' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">{{ __('French') }}</button>
                            </form>
                            <form method="POST" action="{{ route('language.switch.post') }}" class="block">
                                @csrf
                                <input type="hidden" name="locale" value="en">
                                <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium {{ app()->getLocale() === 'en' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">{{ __('English') }}</button>
                            </form>
                            <form method="POST" action="{{ route('language.switch.post') }}" class="block">
                                @csrf
                                <input type="hidden" name="locale" value="de">
                                <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium {{ app()->getLocale() === 'de' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">{{ __('German') }}</button>
                            </form>
                        </div>
                    </div>

                    <!-- Menu mobile profil -->
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="flex items-center justify-center">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition duration-150 ease-in-out">
                                        <i class="fas fa-tachometer-alt mr-2"></i>
                                        {{ __('Dashboard') }}
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition duration-150 ease-in-out">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    {{ __('Login') }}
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>
        @endif

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
