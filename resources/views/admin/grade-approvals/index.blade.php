<x-app-layout>
    <x-slot name="title">Grade Approvals</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grade Approval Queue') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Status Overview -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                            <p class="text-sm text-yellow-700">Pending Review</p>
                            <p class="text-2xl font-bold text-yellow-800">{{ $batches->total() }}</p>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <p class="text-sm text-blue-700">Total Records</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $batches->sum('total_records') }}</p>
                        </div>
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                            <p class="text-sm text-green-700">Ready to Approve</p>
                            <p class="text-2xl font-bold text-green-800">{{ $batches->where('error_count', 0)->count() }}</p>
                        </div>
                    </div>

                    <!-- Batches Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">File Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Chairperson</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Total/Success/Error</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($batches as $batch)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ Str::limit($batch->file_name, 30) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $batch->chairperson->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="font-semibold">{{ $batch->total_records }}</span>
                                            <span class="text-gray-400">/</span>
                                            <span class="text-green-600 font-semibold">{{ $batch->success_count }}</span>
                                            <span class="text-gray-400">/</span>
                                            <span class="text-red-600 font-semibold">{{ $batch->error_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($batch->error_count > 0)
                                                <span class="px-3 py-1 text-xs rounded-full font-medium bg-yellow-100 text-yellow-800">
                                                    Has Errors
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs rounded-full font-medium bg-blue-100 text-blue-800">
                                                    Ready
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $batch->submitted_at ? $batch->submitted_at->format('M d, Y H:i') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.grade-approvals.show', $batch) }}" 
                                               class="text-brand-deep hover:text-brand-medium font-medium">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No pending batches for approval.
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
