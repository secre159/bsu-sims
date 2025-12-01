<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Batch Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Batch Information -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">File Name</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{ $batch->file_name }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Status</p>
                    <span class="inline-block px-3 py-1 text-xs rounded-full font-medium mt-1
                        @if($batch->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($batch->status == 'submitted') bg-blue-100 text-blue-800
                        @elseif($batch->status == 'approved') bg-green-100 text-green-800
                        @elseif($batch->status == 'rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($batch->status) }}
                    </span>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Records</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{ $batch->total_records }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Success/Error</p>
                    <div class="mt-1">
                        <span class="text-lg font-semibold text-green-600">{{ $successCount }}</span>
                        <span class="text-gray-400">/</span>
                        <span class="text-lg font-semibold text-red-600">{{ $errorCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Records Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Import Records</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subject Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Error Message</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($records as $record)
                                    <tr class="hover:bg-gray-50 @if($record->status == 'error') bg-red-50 @elseif($record->status == 'matched') bg-green-50 @endif">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $record->student_id_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $record->subject_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                            {{ $record->grade ? number_format($record->grade, 2) : '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs rounded-full font-medium
                                                @if($record->status == 'matched') bg-green-100 text-green-800
                                                @elseif($record->status == 'error') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-red-600">
                                            {{ $record->error_message ?? '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No records in this batch yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('chairperson.grade-batches.index') }}" 
                   class="text-gray-700 hover:text-gray-900 font-medium">
                    ← Back to Batches
                </a>
                <div class="flex gap-3">
                    @if($batch->status == 'pending' && $errorCount < $batch->total_records)
                        <form action="{{ route('chairperson.grade-import.submit', $batch) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                Submit for Approval
                            </button>
                        </form>
                    @endif
                    
                    @if($batch->status == 'pending' && $errorCount > 0)
                        <form action="{{ route('chairperson.grade-batches.retry', $batch) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium">
                                Retry Failed Records
                            </button>
                        </form>
                    @endif
                    
                    @if($batch->status == 'pending')
                        <form action="{{ route('chairperson.grade-batches.destroy', $batch) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Delete this batch?')"
                                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
