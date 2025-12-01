<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grade Import Batches') }}
            </h2>
            <a href="{{ route('chairperson.grade-import.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Import
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Batches Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">File Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Total Records</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Success/Error</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($batches as $batch)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $batch->file_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $batch->total_records }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="text-green-600 font-semibold">{{ $batch->success_count }}</span>
                                            <span class="text-gray-400">/</span>
                                            <span class="text-red-600 font-semibold">{{ $batch->error_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs rounded-full font-medium
                                                @if($batch->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($batch->status == 'submitted') bg-blue-100 text-blue-800
                                                @elseif($batch->status == 'approved') bg-green-100 text-green-800
                                                @elseif($batch->status == 'rejected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($batch->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $batch->submitted_at ? $batch->submitted_at->format('M d, Y H:i') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('chairperson.grade-batches.show', $batch) }}" 
                                               class="text-brand-deep hover:text-brand-medium font-medium">
                                                View
                                            </a>
                                            @if($batch->status == 'pending')
                                                <form action="{{ route('chairperson.grade-batches.destroy', $batch) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Delete this batch?')"
                                                            class="text-red-600 hover:text-red-900 font-medium">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No import batches yet. 
                                            <a href="{{ route('chairperson.grade-import.create') }}" class="text-brand-deep hover:text-brand-medium font-medium">
                                                Create one now
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $batches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
