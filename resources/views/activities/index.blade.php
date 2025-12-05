<x-app-layout>
    <x-slot name="title">Activity Log</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Box -->
            <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-indigo-500 p-6 rounded-xl shadow-sm">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-indigo-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-800">Audit Trail</h3>
                        <p class="text-sm text-gray-600 mt-1">Track all student record changes including who made the change and when.</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('activities.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Action</label>
                        <select name="action" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Actions</option>
                            <optgroup label="Student Management">
                                <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                                <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                                <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                            </optgroup>
                            <optgroup label="Grade Management">
                                <option value="grade_entered" {{ request('action') == 'grade_entered' ? 'selected' : '' }}>Grade Entered</option>
                                <option value="grade_modified" {{ request('action') == 'grade_modified' ? 'selected' : '' }}>Grade Modified</option>
                                <option value="bulk_grade_update" {{ request('action') == 'bulk_grade_update' ? 'selected' : '' }}>Bulk Grade Update</option>
                                <option value="grade_batch_submitted" {{ request('action') == 'grade_batch_submitted' ? 'selected' : '' }}>Batch Submitted</option>
                                <option value="grade_batch_approved" {{ request('action') == 'grade_batch_approved' ? 'selected' : '' }}>Batch Approved</option>
                                <option value="grade_batch_rejected" {{ request('action') == 'grade_batch_rejected' ? 'selected' : '' }}>Batch Rejected</option>
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Activity Timeline -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Recent Activities
                    </h3>

                    @forelse($activities as $activity)
                        <div class="flex gap-4 pb-6 mb-6 border-b border-gray-200 last:border-0">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                @if($activity->action === 'created')
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                @elseif($activity->action === 'updated')
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                @elseif($activity->action === 'deleted')
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </div>
                                @elseif(str_contains($activity->action, 'grade'))
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $activity->description }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            by <span class="font-medium">{{ $activity->user->name }}</span>
                                            · {{ $activity->created_at->diffForHumans() }}
                                            · {{ $activity->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($activity->action === 'created') bg-green-100 text-green-800
                                        @elseif($activity->action === 'updated') bg-blue-100 text-blue-800
                                        @elseif($activity->action === 'deleted') bg-red-100 text-red-800
                                        @elseif(str_contains($activity->action, 'approved')) bg-emerald-100 text-emerald-800
                                        @elseif(str_contains($activity->action, 'rejected')) bg-red-100 text-red-800
                                        @elseif(str_contains($activity->action, 'grade')) bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                    </span>
                                </div>

                                @if($activity->properties && isset($activity->properties['old']) && isset($activity->properties['new']))
                                    <details class="mt-2">
                                        <summary class="text-xs text-indigo-600 cursor-pointer hover:text-indigo-800 font-medium">View Changes</summary>
                                        <div class="mt-2 bg-gray-50 rounded-lg p-3 text-xs">
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <p class="font-semibold text-gray-700 mb-1">Before:</p>
                                                    <pre class="text-gray-600 overflow-auto">{{ json_encode($activity->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-700 mb-1">After:</p>
                                                    <pre class="text-gray-600 overflow-auto">{{ json_encode($activity->properties['new'], JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </details>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No Activities Found</h3>
                            <p class="text-gray-500">There are no logged activities yet</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($activities->hasPages())
                        <div class="mt-6">
                            {{ $activities->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
