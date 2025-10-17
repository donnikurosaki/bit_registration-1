@extends('layouts.app')

@section('title', 'À Propos de Notre Plateforme Éducative')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                Transformez votre apprentissage
            </h1>
            <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
                Un Etablissement innovant pour une éducation sans limites
            </p>
        </div>

        <!-- Mission Section -->
        <div class="bg-white shadow-xl rounded-3xl overflow-hidden mb-16">
            <div class="grid md:grid-cols-2">
                <div class="p-10 md:p-12 lg:p-16 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
                    <h2 class="text-3xl font-bold mb-6">Notre Mission</h2>
                    <p class="text-lg mb-6">
                        Formez une nouvelle génération de leader pour un burkina plus dynamique
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="ml-3 text-blue-100">
                                Accès illimité à des milliers de ressources
                            </p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="ml-3 text-blue-100">
                                Enseignants experts dans leur domaine
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-10 md:p-12 lg:p-16">
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                        <img src="/images/about/Picture10.jpg" alt="Étudiants en apprentissage" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-white shadow-xl rounded-3xl p-8 mb-16">
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="p-6">
                    <div class="text-5xl font-extrabold text-blue-600 mb-2" id="students-count">500+</div>
                    <p class="text-gray-600">Étudiants actifs</p>
                </div>
                <div class="p-6 border-l border-r border-gray-200">
                    <div class="text-5xl font-extrabold text-blue-600 mb-2" id="courses-count">200+</div>
                    <p class="text-gray-600">Cours disponibles</p>
                </div>
                <div class="p-6">
                    <div class="text-5xl font-extrabold text-blue-600 mb-2" id="countries-count">1</div>
                    <p class="text-gray-600">Pays représentés</p>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        {{-- <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-12">Notre Équipe Pédagogique</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="h-48 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                        <img src="/images/team1.jpg" alt="Dr. Sarah Johnson" class="h-40 w-40 rounded-full object-cover border-4 border-white">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">Dr. Sarah Johnson</h3>
                        <p class="text-blue-600">Directrice Pédagogique</p>
                        <p class="mt-2 text-gray-600">PhD en Sciences de l'Éducation</p>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="h-48 bg-gradient-to-r from-purple-400 to-purple-600 flex items-center justify-center">
                        <img src="/images/team2.jpg" alt="Prof. Jean Dupont" class="h-40 w-40 rounded-full object-cover border-4 border-white">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">Prof. Jean Dupont</h3>
                        <p class="text-purple-600">Expert en Mathématiques</p>
                        <p class="mt-2 text-gray-600">15 ans d'expérience</p>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="h-48 bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                        <img src="/images/team3.jpg" alt="Dr. Amina Bah" class="h-40 w-40 rounded-full object-cover border-4 border-white">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">Dr. Amina Bah</h3>
                        <p class="text-green-600">Spécialiste en IA</p>
                        <p class="mt-2 text-gray-600">Chercheuse en EdTech</p>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition hover:-translate-y-2 hover:shadow-2xl">
                    <div class="h-48 bg-gradient-to-r from-yellow-400 to-yellow-600 flex items-center justify-center">
                        <img src="/images/team4.jpg" alt="Prof. Carlos Mendez" class="h-40 w-40 rounded-full object-cover border-4 border-white">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">Prof. Carlos Mendez</h3>
                        <p class="text-yellow-600">Expert en Langues</p>
                        <p class="mt-2 text-gray-600">Polyglotte (5 langues)</p>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Testimonials -->
        {{-- <div class="bg-white shadow-xl rounded-3xl p-10 mb-16">
            <h2 class="text-3xl font-bold text-center mb-12">Ce que disent nos étudiants</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <img src="/images/student1.jpg" alt="Marie K." class="h-12 w-12 rounded-full object-cover">
                        <div class="ml-4">
                            <h4 class="font-bold">Marie K.</h4>
                            <p class="text-blue-600 text-sm">Étudiante en Informatique</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">
                        "Cette plateforme a révolutionné ma façon d'apprendre. Les cours sont interactifs et les professeurs toujours disponibles."
                    </p>
                    <div class="mt-4 flex text-yellow-400">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <img src="/images/student2.jpg" alt="Thomas L." class="h-12 w-12 rounded-full object-cover">
                        <div class="ml-4">
                            <h4 class="font-bold">Thomas L.</h4>
                            <p class="text-blue-600 text-sm">Étudiant en Commerce</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">
                        "La qualité des cours est exceptionnelle. J'ai pu obtenir ma certification en seulement 3 mois grâce à cette plateforme."
                    </p>
                    <div class="mt-4 flex text-yellow-400">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection

@push('scripts')
<script>
// Animation des statistiques
function animateValue(id, start, end, duration) {
    const obj = document.getElementById(id);
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.innerHTML = Math.floor(progress * (end - start) + start).toLocaleString();
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

document.addEventListener('DOMContentLoaded', function() {
    animateValue('students-count', 0, 12500, 2000);
    animateValue('courses-count', 0, 350, 1500);
    animateValue('countries-count', 0, 45, 1000);
});
</script>
@endpush
