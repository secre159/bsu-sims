<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Archives') }}
            </h2>
            <a href="{{ route('archive.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                Archive School Year
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Enhanced Info Box with Description and Instructions - 2 Column Layout -->
            <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-4" x-data="{ showDescription: false, showInstructions: false }">
                <!-- Description Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-indigo-500 rounded-xl shadow-sm overflow-hidden">
                    <button @click="showDescription = !showDescription" class="w-full p-6 flex items-center justify-between hover:bg-blue-100/50 transition-colors duration-200">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="font-bold text-gray-800 text-lg">📚 About Student Archives</h3>
                        </div>
                        <svg :class="showDescription ? 'rotate-180' : ''" class="w-5 h-5 text-indigo-600 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="showDescription" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="px-6 pb-6">
                        <div class="flex items-start">
                            <div class="flex-1">
                            <p class="text-sm text-gray-700 leading-relaxed mb-2">
                                The Archive System is a comprehensive data preservation tool designed to maintain historical student records for long-term storage and compliance. 
                                This feature creates permanent snapshots of student data at specific points in time (by school year and semester), enabling your institution to:
                            </p>
                            <ul class="text-sm text-gray-700 space-y-1 ml-4">
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2">•</span>
                                    <span><strong>Preserve historical records</strong> for reporting, audits, and compliance requirements</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2">•</span>
                                    <span><strong>Clean active student lists</strong> at the end of academic periods while retaining data</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2">•</span>
                                    <span><strong>Restore student records</strong> if needed for re-enrollment or data recovery</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2">•</span>
                                    <span><strong>Maintain audit trails</strong> with timestamps and reasons for archiving</span>
                                </li>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions Card -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl shadow-sm overflow-hidden">
                    <button @click="showInstructions = !showInstructions" class="w-full p-6 flex items-center justify-between hover:bg-green-100/50 transition-colors duration-200">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <h3 class="font-bold text-gray-800 text-lg">📋 How to Use Archives</h3>
                        </div>
                        <svg :class="showInstructions ? 'rotate-180' : ''" class="w-5 h-5 text-green-600 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="showInstructions" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="px-6 pb-6">
                        <div class="flex items-start">
                            <div class="flex-1">
                            
                            <div class="space-y-3">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-7 h-7 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3 mt-0.5">
                                            1
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm">Create Archive</h4>
                                            <p class="text-sm text-gray-600 mt-1">Click "Archive School Year" button, select the school year and semester, optionally provide a reason, then submit to create a snapshot of all current students.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-7 h-7 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3 mt-0.5">
                                            2
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm">View Archives</h4>
                                            <p class="text-sm text-gray-600 mt-1">Browse archived school years below. Each card shows the total number of archived students. Click "View Archive" to see detailed student records.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-7 h-7 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3 mt-0.5">
                                            3
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm">Restore Students</h4>
                                            <p class="text-sm text-gray-600 mt-1">Inside an archive, you can restore individual students back to the active student list by clicking the "Restore" button next to their record.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-7 h-7 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3 mt-0.5">
                                            4
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm">Delete Archives</h4>
                                            <p class="text-sm text-gray-600 mt-1">When an archive is no longer needed (after data retention period expires), use the delete button (trash icon) to permanently remove the entire archive batch.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-3">
                                <p class="text-xs text-amber-800 flex items-start">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span><strong>Tip:</strong> We recommend archiving at the end of each semester or academic year. When creating an archive, you can optionally delete active students to start fresh for the new period.</span>
                                </p>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        Archived School Years
                    </h3>

                    @if($archives->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($archives as $archive)
                                <div class="archive-card group bg-white border border-gray-200 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:border-indigo-300">
                                    <div class="flex items-start justify-between mb-6">
                                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <h4 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $archive->archived_school_year }}</h4>
                                    <p class="text-base text-gray-600 mb-4 font-medium">{{ $archive->archived_semester }}</p>
                                    
                                    <div class="flex items-center bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl px-4 py-3 mb-6">
                                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3 shadow-sm">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-2xl font-bold text-indigo-600">{{ $archive->total_students }}</p>
                                            <p class="text-xs text-gray-600 font-medium">Archived Students</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-3">
                                        <a href="{{ route('archive.show', [$archive->archived_school_year, $archive->archived_semester]) }}" 
                                           class="flex-1 text-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                            View Archive
                                        </a>
                                        <form action="{{ route('archive.destroy', [$archive->archived_school_year, $archive->archived_semester]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to permanently delete this archive? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 font-semibold py-3 px-5 rounded-xl border-2 border-red-200 hover:border-red-300 transition-all duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No Archives Yet</h3>
                            <p class="text-gray-500 mb-6">Start by archiving a school year to preserve records</p>
                            <a href="{{ route('archive.create') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create First Archive
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
