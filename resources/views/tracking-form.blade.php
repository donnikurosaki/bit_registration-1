@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-12 animate-fade-in-down">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">
                Suivi de Dossier
            </h1>
            <p class="mt-3 text-xl text-gray-500">
                Consultez l'avancement de votre candidature
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in-up">
            <div class="px-6 py-8">
                <form action="{{ route('filetracking.search') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="submission_id" class="block text-sm font-medium text-gray-700">
                            Numéro de référence
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="submission_id" 
                                id="submission_id" 
                                class="focus:ring-accent focus:border-accent block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                placeholder="Exemple: SUB-ABCD1234"
                                required
                            >
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Saisissez le numéro de référence qui vous a été communiqué lors de votre soumission.
                        </p>
                        @error('submission_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none transition-transform transform hover:-translate-y-0.5" 
                            style="background-color: #f72585;"
                        >
                            Consulter mon dossier
                        </button>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-6 py-4">
                <div class="text-center text-sm text-gray-600">
                    <p>
                        Vous n'avez pas encore soumis de dossier ? 
                        <a href="{{ route('submission.create') }}" class="font-medium text-accent hover:text-accent/80" style="color: #f72585;">
                            Déposez votre candidature
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

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
</style>
@endsection 