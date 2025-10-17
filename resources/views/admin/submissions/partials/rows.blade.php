@foreach($submissions as $submission)
<tr class="submission-row" data-status="{{ $submission->status }}" data-id="{{ $submission->id }}">
    <td class="px-4 py-4 whitespace-nowrap">
        <input type="checkbox" class="row-select h-4 w-4 text-primary-600 border-gray-300 rounded" value="{{ $submission->id }}">
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
        #{{ $submission->id }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900 dark:text-white">
            {{ $submission->first_name }} {{ $submission->last_name }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
        {{ $submission->submission_id }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
        {{ $submission->email }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($submission->status === 'pending')
            <span class="status-badge px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200">
                En attente
            </span>
        @elseif($submission->status === 'approved')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
                Approuvé
            </span>
        @else
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200">
                Rejeté
            </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
        {{ $submission->created_at->format('d/m/Y H:i') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <div class="flex space-x-2">
            <a href="{{ route('admin.submissions.show', $submission) }}"
               class="action-btn bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30"
               title="Voir détails">
                <i class="fas fa-eye"></i>
            </a>
            <button @click.prevent="deleteAction='{{ route('admin.submissions.destroy', $submission) }}'; showDeleteModal=true;"
                    class="action-btn bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30"
                    title="Supprimer">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>
</tr>
@endforeach 