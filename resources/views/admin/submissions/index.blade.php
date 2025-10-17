@extends('layouts.app')

@section('content')
<div x-data="{ showDeleteModal:false, deleteAction:'' }" class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestion des Soumissions</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gérez toutes les soumissions de programmes</p>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden fade-in">
        <!-- Card Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-lg font-semibold">Toutes les soumissions</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Affichage de <span id="display-count">{{ $submissions->count() }}</span> sur
                    <span id="total-count">{{ $submissions->total() }}</span> soumissions
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <div class="flex items-center bg-gray-100 dark:bg-gray-750 rounded-lg px-3 py-1.5">
                    <i class="fas fa-search text-gray-400 mr-2"></i>
                    <input id="search-input" type="text" placeholder="Rechercher..."
                           class="bg-transparent border-none focus:ring-0 text-sm w-40">
                </div>

                <div class="flex gap-1 bg-gray-100 dark:bg-gray-750 rounded-lg p-1">
                    <button class="status-filter px-3 py-1.5 rounded-md text-sm font-medium bg-white dark:bg-gray-700 shadow text-primary-600 dark:text-primary-400"
                            data-status="all">
                        Tous
                    </button>
                    <button class="status-filter px-3 py-1.5 rounded-md text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300"
                            data-status="pending">
                        En attente
                    </button>
                    <button class="status-filter px-3 py-1.5 rounded-md text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300"
                            data-status="approved">
                        Approuvé
                    </button>
                    <button class="status-filter px-3 py-1.5 rounded-md text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300"
                            data-status="rejected">
                        Rejeté
                    </button>
                </div>
            </div>
        </div>

        <!-- Submissions Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-750">
                    <tr>
                        <th scope="col" class="px-4 py-3">
                            <input type="checkbox" id="select-all" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nom & Prénom
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Submission ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Date de soumission
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="submissions-body">
                    @include('admin.submissions.partials.rows', ['submissions' => $submissions])
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Affichage <span class="font-medium">{{ $submissions->firstItem() }}</span>
                à <span class="font-medium">{{ $submissions->lastItem() }}</span>
                de <span class="font-medium">{{ $submissions->total() }}</span> résultats
            </p>

            <div class="flex gap-1">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75">
        <div @click.away="showDeleteModal=false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Confirmer la suppression</h3>
            <p class="text-gray-700 dark:text-gray-300 mb-6">Êtes-vous sûr de vouloir supprimer cette soumission&nbsp;? Cette action est irréversible.</p>
            <div class="flex justify-end gap-3">
                <button type="button" @click="showDeleteModal=false" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Annuler</button>
                <form :action="deleteAction" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .submission-card {
        transition: all 0.3s ease;
    }

    .submission-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .status-badge {
        transition: all 0.2s ease;
    }

    .status-badge:hover {
        transform: scale(1.05);
    }

    .modal-overlay {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .action-btn {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }

    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    .slide-up {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Active status filter */
    .status-filter.active {
        background-color: #3b82f6;
        color: white;
    }

    .dark .status-filter.active {
        background-color: #2563eb;
        color: white;
    }

    /* Table improvements */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background-color: #f9fafb;
    }

    .dark th {
        background-color: #1f2937;
    }

    tr:hover {
        background-color: #f8fafc;
    }

    .dark tr:hover {
        background-color: #374151;
    }
</style>
@endsection

@section('scripts')
<script>
    // Theme toggle
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
                this.innerHTML = '<i class="fas fa-sun text-yellow-500"></i>';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
                this.innerHTML = '<i class="fas fa-moon text-yellow-400"></i>';
            }
        });

        // Initialize theme based on preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            themeToggle.innerHTML = '<i class="fas fa-moon text-yellow-400"></i>';
        } else {
            document.documentElement.classList.remove('dark');
            themeToggle.innerHTML = '<i class="fas fa-sun text-yellow-500"></i>';
        }
    }

    // Simple modal helper (utilisé pour le modal de suppression)
    function closeModal() {
        document.querySelectorAll('[x-show]').forEach(el => el.classList.add('hidden'));
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // ----------------------
    // Logique AJAX & sélections
    // ----------------------
    let currentStatus = 'all';
    let searchQuery = '';

    // Fonction de récupération AJAX des soumissions
    async function fetchSubmissions() {
        const params = new URLSearchParams();
        if (searchQuery) params.append('search', searchQuery);
        if (currentStatus !== 'all') params.append('status', currentStatus);

        try {
            const response = await fetch(`{{ route('admin.submissions.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) {
                console.error('Erreur serveur', response.status);
                return;
            }
            const data = await response.json();
            document.getElementById('submissions-body').innerHTML = data.html;
            document.getElementById('display-count').textContent = data.display;
            document.getElementById('total-count').textContent = data.total;
            attachCheckboxEvents();
        } catch (error) {
            console.error(error);
        }
    }

    // Gestion du champ de recherche
    document.getElementById('search-input').addEventListener('keyup', function() {
        searchQuery = this.value;
        fetchSubmissions();
    });

    // Gestion des filtres de statut
    const statusFilters = document.querySelectorAll('.status-filter');
    statusFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            statusFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.dataset.status;
            fetchSubmissions();
        });
    });
    // Définir "Tous" comme actif au chargement
    document.querySelector('.status-filter[data-status="all"]').classList.add('active');

    // Gestion des cases à cocher
    const selectAll = document.getElementById('select-all');
    function attachCheckboxEvents() {
        const rowCheckboxes = document.querySelectorAll('.row-select');
        // Réinitialise le select-all
        selectAll.checked = false;
        if (!selectAll.dataset.initialized) {
            selectAll.addEventListener('change', function() {
                rowCheckboxes.forEach(cb => cb.checked = this.checked);
            });
            selectAll.dataset.initialized = 'true';
        }
    }
    // Initialisation
    attachCheckboxEvents();
</script>
@endsection
