@extends('layouts.app')

@section('title', __('Home'))

@section('content')
<!-- Hero Section -->
<div class="relative bg-black overflow-hidden min-h-[500px]">
    <!-- Fond avec flou et effet glass -->
    <div class="absolute inset-0 backdrop-blur-md bg-white/10 z-0"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <!-- Container Glass Morphism -->
                <div class="bg-white/20 backdrop-blur-lg rounded-xl p-8 border border-white/30 shadow-2xl shadow-black/30 max-w-2xl">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl drop-shadow-lg">
                            <span class="block">Burkina Faso</span>
                            <span class="block text-accent">Institute of Technology</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-100 sm:mt-5 sm:text-lg md:mt-5 md:text-xl lg:mx-0 font-medium">
                            {{ __('Former une nouvelle génération de leaders.') }}
                        </p>
                        <div class="mt-8 sm:flex sm:justify-center lg:justify-start space-y-3 sm:space-y-0 sm:space-x-4">
                            <div class="rounded-lg shadow-lg">
                                <a href="{{ route('register') }}" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-accent hover:bg-accent-dark md:py-4 md:text-lg md:px-8 transition-all duration-300 transform hover:scale-105 hover:shadow-accent/30">
                                    {{ __("S'inscrire") }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                            <div class="rounded-lg shadow-lg">
                                <a href="{{ route('login') }}" class="flex items-center justify-center px-6 py-3 border border-white/30 text-base font-medium rounded-lg text-white bg-white/10 hover:bg-white/20 md:py-4 md:text-lg md:px-8 transition-all duration-300 backdrop-blur-sm">
                                    {{ __('Connexion') }}
                                    <i class="fas fa-user ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>s
    </div>

    <!-- Image de fond à droite (version améliorée) -->
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <div class="relative h-full w-full">
            <img class="h-full w-full object-cover brightness-75" src="/images/home/bkg.jpg" alt="Étudiants en fin de cycle">
            <!-- Overlay pour améliorer la lisibilité -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent lg:bg-gradient-to-r lg:from-black/80 lg:via-black/50 lg:to-transparent"></div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête Section -->
        <div class="text-center mb-16">
            <span class="inline-block px-3 py-1 text-sm font-semibold text-primary bg-primary/10 rounded-full uppercase tracking-wider">
                {{ __('Notre Mission') }}
            </span>
            <h2 class="mt-4 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                {{ __('BIT crée un impact transformateur') }}
            </h2>
            <div class="mt-6 max-w-3xl mx-auto">
                <p class="text-xl text-gray-600 leading-relaxed">
                    {{ __("Nous formons une nouvelle génération d'entrepreneurs prêts à conduire le changement. Notre objectif est de former les leaders et les professionnels de demain au Burkina Faso.") }}
                </p>
            </div>
        </div>

        <!-- Nos Offres -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="px-4 bg-white text-lg font-medium text-gray-900">
                    <i class="fas fa-star text-yellow-400 mr-2"></i> {{ __('Nos offres') }}
                </span>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Programme BIT Accelerator -->
            <div class="group relative bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-primary/30 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-accent"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-16 h-16 bg-primary/10 rounded-lg text-primary mb-6">
                        <i class="fas fa-rocket text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">BIT Accelerateur</h3>
                    <p class="text-gray-600 mb-6">
                        Le programme BIT Accelerator accompagne les étudiants motivés à devenir les leaders de demain. Une opportunité unique de développer et lancer leur propre entreprise au sein d'une communauté d'entrepreneurs engagés.
                    </p>
                    <div class="flex">
                        <a href="https://bit.bf/accelerator/" class="text-primary font-medium inline-flex items-center group">
                            En savoir plus
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 rounded-full bg-primary/5 group-hover:bg-primary/10 transition-all duration-500"></div>
            </div>

            <!-- Stages 100% Garantis -->
            <div class="group relative bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-secondary/30 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-secondary to-accent"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-16 h-16 bg-secondary/10 rounded-lg text-secondary mb-6">
                        <i class="fas fa-briefcase text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Stages 100% Garantis</h3>
                    <p class="text-gray-600 mb-6">
                        L'expérience pratique étant essentielle, nous garantissons à tous nos étudiants l'accès à des stages de qualité. Nous remercions nos partenaires entreprises pour leur confiance.
                    </p>
                    <div class="flex">
                        <a href="https://bit.bf/internships/" class="text-secondary font-medium inline-flex items-center group">
                            Découvrir
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 rounded-full bg-secondary/5 group-hover:bg-secondary/10 transition-all duration-500"></div>
            </div>

            <!-- Autonomisation des Femmes -->
            <div class="group relative bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-accent/30 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent to-primary"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-16 h-16 bg-accent/10 rounded-lg text-accent mb-6">
                        <i class="fas fa-female text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Autonomisation des Femmes</h3>
                    <p class="text-gray-600 mb-6">
                        Notre programme dédié renforce la confiance et l'ambition de nos étudiantes. Objectifs : renforcer leur perception sociale, développer leur confiance, maximiser leurs opportunités de carrière.
                    </p>
                    <div class="flex">
                        <a href="https://bit.bf/female-empowerment-at-bit/" class="text-accent font-medium inline-flex items-center group">
                            Explorer
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 rounded-full bg-accent/5 group-hover:bg-accent/10 transition-all duration-500"></div>
            </div>

            <!-- Excellence Académique -->
            <div class="group relative bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-primary/30 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-secondary"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-center w-16 h-16 bg-primary/10 rounded-lg text-primary mb-6">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Excellence Académique</h3>
                    <p class="text-gray-600 mb-6">
                        Nous encourageons nos étudiants à penser comme des entrepreneurs pour transformer leurs compétences techniques en opportunités. Notre objectif : former la future élite entrepreneuriale du Burkina Faso.
                    </p>
                    <div class="flex">
                        <a href="https://bit.bf/academics/" class="text-primary font-medium inline-flex items-center group">
                            Programme
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 rounded-full bg-primary/5 group-hover:bg-primary/10 transition-all duration-500"></div>
            </div>
        </div>
    </div>
</div>
<!-- Stats Section -->
<div class="bg-gray-50 pt-12 sm:pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Notre institut en chiffres
            </h2>
            <p class="mt-3 text-xl text-gray-500 sm:mt-4">
                Une communauté éducative dynamique et en pleine croissance.
            </p>
        </div>
    </div>
    <div class="mt-10 pb-12 bg-gray-50 sm:pb-16">
        <div class="relative">
            <div class="absolute inset-0 h-1/2 bg-gray-50"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <dl class="rounded-lg bg-white shadow-lg sm:grid sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                        <div class="flex flex-col p-6 text-center">
                            <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">
                                Étudiants actifs
                            </dt>
                            <dd class="order-1 text-5xl font-extrabold text-primary">
                                <span class="counter" data-target="500">500</span>+
                            </dd>
                        </div>
                        <div class="flex flex-col p-6 text-center">
                            <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">
                                Programmes
                            </dt>
                            <dd class="order-1 text-5xl font-extrabold text-secondary">
                                <span class="counter" data-target="200">200</span>+
                            </dd>
                        </div>
                        <div class="flex flex-col p-6 text-center">
                            <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">
                                Taux de réussite
                            </dt>
                            <dd class="order-1 text-5xl font-extrabold text-accent">
                                <span class="counter" data-target="98">98</span>%
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="relative py-16 sm:py-20 overflow-hidden bg-gray-50">
    <!-- Fond texturé subtil -->
    <div class="absolute inset-0 z-0 bg-[url('/images/home/bit_campus.jpg')] opacity-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- En-tête Section -->
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 text-sm font-semibold text-gray-600 bg-white/80 rounded-full uppercase tracking-wider shadow-sm backdrop-blur-lg border border-gray-100">
                <i class="fas fa-comments text-primary mr-2"></i> Témoignages
            </span>
            <h2 class="mt-6 text-4xl font-extrabold text-gray-900 sm:text-5xl">
                Ils parlent de <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-accent">BIT</span>
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-600">
                Découvrez les expériences authentiques de notre communauté
            </p>
        </div>

        <!-- Témoignages - Effet Glass Morphism -->
        <div class="mt-12 grid gap-8 md:grid-cols-3">
            <!-- Témoignage 1 -->
            <div class="group relative bg-white/80 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 border border-gray-100/50 backdrop-blur-md hover:backdrop-blur-lg">
                <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-quote-right text-2xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="absolute -top-5 -left-5 w-12 h-12 bg-accent rounded-lg flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                    <div class="pl-8">
                        <div class="text-gray-700 leading-relaxed italic">
                            <p class="before:content-['"'] after:content-['"']">
                                BIT allie parfaitement théorie et pratique. Les projets concrets nous préparent vraiment au monde professionnel. Une expérience transformative !
                            </p>
                        </div>
                        <footer class="mt-8 flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full border-2 border-white shadow-md" src="/images/testimonial/Patrick-Yanogo.png" alt="Patrick Yanogo">
                            </div>
                            <div class="ml-4">
                                <div class="text-base font-bold text-gray-900">Patrick Yanogo</div>
                                <div class="text-sm text-primary">Promotion 2023</div>
                            </div>
                        </footer>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-primary to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            </div>

            <!-- Témoignage 2 -->
            <div class="group relative bg-white/80 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 border border-gray-100/50 backdrop-blur-md hover:backdrop-blur-lg">
                <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full bg-secondary/10 flex items-center justify-center text-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-quote-right text-2xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="absolute -top-5 -left-5 w-12 h-12 bg-secondary rounded-lg flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-user-tie text-xl"></i>
                    </div>
                    <div class="pl-8">
                        <div class="text-gray-700 leading-relaxed italic">
                            <p class="before:content-['"'] after:content-['"']">
                                L'enseignement en anglais était un défi mais c'est aujourd'hui mon plus grand atout. Les entreprises internationales recherchent cette compétence.
                            </p>
                        </div>
                        <footer class="mt-8 flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full border-2 border-white shadow-md" src="/images/testimonial/Aissata-Semde.png" alt="Aissata Semde">
                            </div>
                            <div class="ml-4">
                                <div class="text-base font-bold text-gray-900">Aïssata Semdé</div>
                                <div class="text-sm text-secondary">Secrétaire du Doyen</div>
                            </div>
                        </footer>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-secondary to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            </div>

            <!-- Témoignage 3 -->
            <div class="group relative bg-white/80 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 border border-gray-100/50 backdrop-blur-md hover:backdrop-blur-lg">
                <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full bg-accent/10 flex items-center justify-center text-accent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-quote-right text-2xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="absolute -top-5 -left-5 w-12 h-12 bg-accent rounded-lg flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-flask text-xl"></i>
                    </div>
                    <div class="pl-8">
                        <div class="text-gray-700 leading-relaxed italic">
                            <p class="before:content-['"'] after:content-['"']">
                                L'accès aux cours edX et la qualité des enseignants font de BIT un environnement d'apprentissage exceptionnel. Les laboratoires sont remarquables.
                            </p>
                        </div>
                        <footer class="mt-8 flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full border-2 border-white shadow-md" src="/images/testimonial/Jeremie-Nana.png" alt="Jérémie Nana">
                            </div>
                            <div class="ml-4">
                                <div class="text-base font-bold text-gray-900">Jérémie Nana</div>
                                <div class="text-sm text-accent">Doctorant en IA</div>
                            </div>
                        </footer>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-accent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            </div>
        </div>

        <!-- Call-to-action -->
        <div class="mt-16 text-center">
            <a href="/temoignages" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-primary to-accent hover:from-primary-dark hover:to-accent-dark transition-all duration-300 transform hover:scale-105 group">
                <span class="relative">
                    Voir tous les témoignages
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-white/50 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </span>
                <i class="fas fa-chevron-right ml-2 transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
    </div>
</div>
<!-- CTA Section -->
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            <span class="block">Prêt à commencer?</span>
            <span class="block text-primary">Faites votre Admission dès aujourd'hui.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-accent hover:bg-accent-dark transition duration-300 transform hover:scale-105">
                    Créer un compte
                </a>
            </div>
            <div class="ml-3 inline-flex rounded-md shadow">
                <a href="" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-50 transition duration-300">
                    En savoir plus
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Animation des compteurs
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        counters.forEach(counter => {
            const animate = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const increment = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(animate, 1);
                } else {
                    counter.innerText = target;
                }
            };

            // Déclencher l'animation lors du défilement
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    animate();
                    observer.unobserve(counter);
                }
            });

            observer.observe(counter);
        });

        // Animation des cartes fonctionnalités
        const featureCards = document.querySelectorAll('.group');
        featureCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                const icon = card.querySelector('.pointer-events-none');
                icon.classList.add('animate-bounce');
            });

            card.addEventListener('mouseleave', () => {
                const icon = card.querySelector('.pointer-events-none');
                icon.classList.remove('animate-bounce');
            });
        });
    });
</script>
@endpush
@endsection
