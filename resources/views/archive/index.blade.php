<x-app-layout>
    <x-slot name="title">Student Archives</x-slot>

    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Premium Header with Gradient -->
            <div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
                <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
                
                <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-white/20 backdrop-blur-sm rounded-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-white drop-shadow-lg">Student Archives</h1>
                        </div>
                        <p class="text-orange-100 ml-14">Preserve historical records and manage semester snapshots</p>
                    </div>
                    <a href="{{ route('archive.create') }}" 
                       class="inline-flex items-center px-5 py-3 bg-white hover:bg-orange-50 text-orange-700 font-semibold rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Archive School Year
                    </a>
                </div>
            </div>
            <!-- Tabbed Info Panel -->
            <div class="mb-8 bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" x-data="{ activeTab: 'about' }">
                <!-- Tab Buttons -->
                <div class="flex border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <button @click="activeTab = 'about'" 
                            :class="activeTab === 'about' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300'"
                            class="flex-1 py-4 px-6 text-center border-b-2 font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <div :class="activeTab === 'about' ? 'bg-gradient-to-br from-indigo-500 to-indigo-600' : 'bg-gray-400'" 
                             class="p-1.5 rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span>📚 About Archives</span>
                    </button>
                    <button @click="activeTab = 'guide'" 
                            :class="activeTab === 'guide' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300'"
                            class="flex-1 py-4 px-6 text-center border-b-2 font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <div :class="activeTab === 'guide' ? 'bg-gradient-to-br from-emerald-500 to-emerald-600' : 'bg-gray-400'" 
                             class="p-1.5 rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <span>📋 How to Use</span>
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="p-8">
                    <!-- About Tab -->
                    <div x-show="activeTab === 'about'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0">
                        <p class="text-sm text-gray-700 leading-relaxed mb-4">
                            The Archive System is a comprehensive data preservation tool designed to maintain historical student records for long-term storage and compliance. 
                            This feature creates permanent snapshots of student data at specific points in time (by school year and semester), enabling your institution to:
                        </p>
                        <ul class="text-sm text-gray-700 space-y-3 ml-2">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span><strong class="text-indigo-700">Preserve historical records</strong> for reporting, audits, and compliance requirements</span>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span><strong class="text-indigo-700">Clean active student lists</strong> at the end of academic periods while retaining data</span>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span><strong class="text-indigo-700">Restore student records</strong> if needed for re-enrollment or data recovery</span>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span><strong class="text-indigo-700">Maintain audit trails</strong> with timestamps and reasons for archiving</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Guide Tab -->
                    <div x-show="activeTab === 'guide'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="space-y-4">
                            <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">
                                    1
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm mb-1">Create Archive</h4>
                                    <p class="text-sm text-gray-700">Click "Archive School Year" button, select the school year and semester, optionally provide a reason, then submit to create a snapshot of all current students.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">
                                    2
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm mb-1">View Archives</h4>
                                    <p class="text-sm text-gray-700">Browse archived school years below. Each card shows the total number of archived students. Click "View Archive" to see detailed student records.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">
                                    3
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm mb-1">Restore Students</h4>
                                    <p class="text-sm text-gray-700">Inside an archive, you can restore individual students back to the active student list by clicking the "Restore" button next to their record.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">
                                    4
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm mb-1">Delete Archives</h4>
                                    <p class="text-sm text-gray-700">When an archive is no longer needed (after data retention period expires), use the delete button (trash icon) to permanently remove the entire archive batch.</p>
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl">
                                <p class="text-xs text-amber-900 flex items-start gap-2">
                                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span><strong>Tip:</strong> We recommend archiving at the end of each semester or academic year. When creating an archive, you can optionally delete active students to start fresh for the new period.</span>
                                </p>
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
