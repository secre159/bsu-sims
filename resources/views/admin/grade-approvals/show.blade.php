<x-app-layout>
    <x-slot name="title">Review Grade Batch</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review Grade Batch') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Batch Information -->
            <div class="grid grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">File Name</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{ $batch->file_name }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Chairperson</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{ $batch->chairperson->name }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Records</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{ $batch->total_records }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Status</p>
                    <span class="inline-block px-3 py-1 text-xs rounded-full font-medium mt-1
                        @if($batch->status == 'submitted') bg-blue-100 text-blue-800
                        @elseif($batch->status == 'approved') bg-green-100 text-green-800
                        @elseif($batch->status == 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($batch->status) }}
                    </span>
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Import Records</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Details</th>
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
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            @if($record->error_message)
                                                <span class="text-red-600 font-medium">{{ $record->error_message }}</span>
                                            @else
                                                <span class="text-green-600">✓ Ready to apply</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No records in this batch.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Section -->
            @if($batch->status === 'submitted')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Approval Actions</h3>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Approve Section -->
                            <div class="border-l-4 border-green-500 pl-6">
                                <h4 class="font-semibold text-green-700 mb-2">Approve Batch</h4>
                                <p class="text-sm text-gray-600 mb-4">
                                    This will apply {{ $successCount }} grades and calculate GPA for affected students.
                                </p>
                                @if($errorCount > 0)
                                    <p class="text-sm text-red-600 mb-4">
                                        ⚠️ Cannot approve: {{ $errorCount }} record(s) have errors. Ask chairperson to fix.
                                    </p>
                                    <button disabled class="px-6 py-2 bg-gray-400 text-white rounded-lg font-medium cursor-not-allowed opacity-50">
                                        Approve (Errors Pending)
                                    </button>
                                @else
                                    <form action="{{ route('admin.grade-approvals.approve', $batch) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Approve this batch? This will apply all {{ $successCount }} grades.')"
                                                class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium">
                                            ✓ Approve Batch
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Reject Section -->
                            <div class="border-l-4 border-red-500 pl-6">
                                <h4 class="font-semibold text-red-700 mb-2">Reject Batch</h4>
                                <p class="text-sm text-gray-600 mb-4">
                                    Return to chairperson for corrections or further review.
                                </p>
                                <button onclick="openRejectModal()" 
                                        class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg font-medium">
                                    ✗ Reject Batch
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div id="rejectModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 max-w-md mx-auto">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject Batch</h3>
                        <form action="{{ route('admin.grade-approvals.reject', $batch) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Reason for Rejection
                                </label>
                                <textarea id="reason" name="reason" rows="4" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-brand-medium focus:border-brand-medium"
                                          placeholder="Explain why this batch is being rejected..."></textarea>
                                @error('reason')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="closeRejectModal()" 
                                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                                    Reject
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-blue-700 font-medium">
                        This batch has already been {{ $batch->status }}.
                    </p>
                </div>
            @endif

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('admin.grade-approvals.index') }}" 
                   class="text-gray-700 hover:text-gray-900 font-medium">
                    ← Back to Queue
                </a>
            </div>
        </div>
    </div>

    <script>
    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
    </script>
</x-app-layout>
