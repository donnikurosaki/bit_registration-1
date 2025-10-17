@extends('layouts.app')

@section('title', 'Connexion')

@section('hide_navbar', true)

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Conteneur principal avec fond blanc et bordures -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-8 space-y-6">
            <!-- En-tête -->
            <div class="text-center">
                <a href='{{ route('home') }}'>
                    <img class="mx-auto h-10 w-auto" src="/images/logo-institut.png" alt="Logo Institut">
                </a>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">
                    Connexion à votre compte
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Ou
                    <a href="{{ route('register') }}" class="font-medium text-accent hover:text-accent-dark">
                        créez un nouveau compte
                    </a>
                </p>
            </div>

            <!-- Formulaire -->
            <form class="space-y-4" action="{{ route('login') }}" method="POST"> <!-- Réduit mt-8 à mt-6 et space-y-6 à space-y-4 -->
                @csrf

                <!-- Messages d'erreur -->
                @if ($errors->any())
                <div class="rounded-md bg-red-50 p-3"> <!-- Réduit p-4 à p-3 -->
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="rounded-md shadow-sm -space-y-px">
                    <!-- Email -->
                    <div>
                        <label for="email" class="sr-only">Adresse email</label>
                        <input id="email" name="email" type="email" required
                               class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm @error('email') border-red-500 @enderror"
                               placeholder="Adresse email"
                               value="{{ old('email') }}">
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="sr-only">Mot de passe</label>
                        <input id="password" name="password" type="password" required
                               class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                               placeholder="Mot de passe">
                    </div>
                </div>

                <!-- Options supplémentaires -->
                <div class="flex items-center justify-between text-sm"> <!-- Ajout de text-sm -->
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                               class="h-4 w-4 text-accent focus:ring-accent border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 text-gray-900">
                            Se souvenir de moi
                        </label>
                    </div>

                    <div>
                        <a href="" class="font-medium text-accent hover:text-accent-dark">
                            Mot de passe oublié ?
                        </a>
                    </div>
                </div>

                <!-- Bouton de connexion -->
                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-lock text-accent-light group-hover:text-accent-lighter"></i>
                        </span>
                        Se connecter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
