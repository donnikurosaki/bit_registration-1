@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- En-tête -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Tableau de bord</h1>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Grid -->
        <!-- Actions Button -->
        <div class="mb-6 flex justify-end">
            <a href="{{ route('submission.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle soumission
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Submissions -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-medium text-gray-600">Total Soumissions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Submission::count() }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-full">
                    <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Approved Submissions -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-medium text-gray-600">Dossiers Approuvés</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Submission::where('status', 'approved')->count() }}</p>
                    @php
                        $totalSubmissions = \App\Models\Submission::count();
                        $approvedPercentage = $totalSubmissions > 0 ? round((\App\Models\Submission::where('status', 'approved')->count() / $totalSubmissions) * 100) : 0;
                    @endphp
                    <div class="flex items-center text-sm text-green-600">
                        <div class="w-24 h-1 rounded-full bg-gray-200">
                            <div class="h-1 rounded-full bg-green-500" style="width: {{ $approvedPercentage }}%"></div>
                        </div>
                        <span class="ml-2">{{ $approvedPercentage }}%</span>
                    </div>
                </div>
                <div class="p-4 bg-green-50 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Submissions -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-medium text-gray-600">En Attente</p> 
                    @if(\App\Models\Submission::where('status', 'pending')->count() > 0)
                        <span>
                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs text-yellow-700 bg-yellow-100">
                                <i class="fas fa-clock mr-1"></i> Nécessite attention
                            </div>
                        </span>
                    @endif
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Submission::where('status', 'pending')->count() }}</p>
                    <div class="space-y-3">
                        
                        @php
                            $totalSubmissions = \App\Models\Submission::count();
                            $approvedPercentage = $totalSubmissions > 0 ? round((\App\Models\Submission::where('status', 'pending')->count() / $totalSubmissions) * 100) : 0;
                        @endphp
                        <div class="flex items-center text-sm text-yellow-700">
                            <div class="w-24 h-1 rounded-full bg-gray-200">
                                <div class="h-1 rounded-full bg-yellow-700" style="width: {{ $approvedPercentage }}%"></div>
                            </div>
                            <span class="ml-2">{{ $approvedPercentage }}%</span>
                        </div>
                    </div>
                    
                </div>
                <div class="p-4 bg-yellow-50 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Rejected Submissions -->
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-medium text-gray-600">Dossiers Rejetés</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Submission::where('status', 'rejected')->count() }}</p>
                    @php
                        $totalSubmissions = \App\Models\Submission::count();
                        $rejectedPercentage = $totalSubmissions > 0 ? round((\App\Models\Submission::where('status', 'rejected')->count() / $totalSubmissions) * 100) : 0;
                    @endphp
                    <div class="flex items-center text-sm text-red-600">
                        <div class="w-24 h-1 rounded-full bg-gray-200">
                            <div class="h-1 rounded-full bg-red-500" style="width: {{ $rejectedPercentage }}%"></div>
                        </div>
                        <span class="ml-2">{{ $rejectedPercentage }}%</span>
                    </div>
                </div>
                <div class="p-4 bg-red-50 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Submissions Table -->
    <div class="bg-white shadow-sm rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Dernières Soumissions</h3>
                <p class="text-sm text-gray-500 mt-1">Les 5 demandes les plus récentes</p>
            </div>
            <a href="{{ route('admin.submissions.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-primary hover:text-primary-dark">
                Voir tout <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach(\App\Models\Submission::latest()->take(5)->get() as $submission)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"># {{ $submission->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->last_name }} {{ $submission->first_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $submission->submission_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $submission->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $submission->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($submission->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                @if($submission->status === 'pending')
                                    <i class="fas fa-clock mr-1"></i> En attente
                                @elseif($submission->status === 'approved')
                                    <i class="fas fa-check mr-1"></i> Approuvé
                                @else
                                    <i class="fas fa-times mr-1"></i> Rejeté
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $submission->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $submission->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.submissions.show', $submission) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg bg-primary text-white hover:bg-primary-dark transition-colors duration-200">
                                <i class="fas fa-eye mr-1"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </main>
</div>
@endsection

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('userMenu', {
            open: false
        })
</script>
@endpush
