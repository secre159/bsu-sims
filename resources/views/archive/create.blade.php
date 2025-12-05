<x-app-layout>
    <x-slot name="title">Create Archive</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Archive') }}
            </h2>
            <a href="{{ route('archive.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                ← Back to Archives
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Enhanced Warning & Instructions Box - 2 Column Layout -->
            <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-4" x-data="{ showInfo: false, showWarning: false }">
                <!-- What This Does Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-indigo-500 rounded-xl shadow-sm overflow-hidden">
                    <button @click="showInfo = !showInfo" class="w-full p-6 flex items-center justify-between hover:bg-blue-100/50 transition-colors duration-200">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="font-bold text-gray-800 text-base">📦 What This Will Do</h3>
                        </div>
                        <svg :class="showInfo ? 'rotate-180' : ''" class="w-5 h-5 text-indigo-600 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="showInfo" 
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
                                Creating an archive will take a complete snapshot of <strong>all current students</strong> in the system and permanently store them for the specified school year and semester. This includes:
                            </p>
                            <ul class="text-sm text-gray-700 space-y-1 ml-4">
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2">✓</span>
                                    <span>Full student details (name, ID, contact info, etc.)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2">✓</span>
                                    <span>Program enrollment and year level at time of archiving</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2">✓</span>
                                    <span>Student status (Active, Graduated, Inactive, etc.)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2">✓</span>
                                    <span>Timestamp and admin who created the archive</span>
                                </li>
                            </ul>
                            <p class="text-sm text-gray-700 mt-3">
                                Archived data is stored separately and can be viewed or restored at any time from the Archives page.
                            </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notice Card -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-orange-500 rounded-xl shadow-sm overflow-hidden">
                    <button @click="showWarning = !showWarning" class="w-full p-6 flex items-center justify-between hover:bg-orange-100/50 transition-colors duration-200">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-orange-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <h3 class="font-bold text-gray-800 text-base">⚠️ Important Notice</h3>
                        </div>
                        <svg :class="showWarning ? 'rotate-180' : ''" class="w-5 h-5 text-orange-600 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="showWarning" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="px-6 pb-6">
                        <div class="flex items-start">
                            <div class="flex-1">
                            <div class="space-y-2">
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    <strong>Before proceeding:</strong> Archiving creates a permanent snapshot of <strong>all {{ $stats['total'] }} students</strong> currently in the system. Review the statistics below carefully.
                                </p>
                                <div class="bg-white rounded-lg p-3 border border-orange-200">
                                    <p class="text-sm text-gray-800 font-semibold mb-1">Optional: Delete After Archive</p>
                                    <p class="text-sm text-gray-700">
                                        If you check the "Delete students after archiving" option, <strong class="text-red-600">all active students will be permanently removed</strong> from the system after the archive is created. 
                                        Use this only if you want to clear the student list for a new enrollment period. The archived data will still be accessible.
                                    </p>
                                </div>
                                <p class="text-xs text-gray-600 italic mt-2">
                                    💡 Tip: Most institutions archive at the end of each semester or academic year to maintain historical records.
                                </p>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        Archive School Year
                    </h3>

                    <!-- Current Stats -->
                    <div class="mb-8 bg-gradient-to-br from-indigo-50 via-purple-50 to-blue-50 rounded-2xl border-2 border-indigo-200 overflow-hidden shadow-lg">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                            <h4 class="font-bold text-white text-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Current Student Statistics
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-3 gap-6">
                                <div class="stat-card bg-white rounded-xl p-5 shadow-md border-l-4 border-indigo-500 transform hover:scale-105 transition-transform duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total</span>
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-4xl font-bold text-indigo-600 mb-1">{{ $stats['total'] }}</p>
                                    <p class="text-xs text-gray-500 font-medium">Students to archive</p>
                                </div>
                                <div class="stat-card bg-white rounded-xl p-5 shadow-md border-l-4 border-green-500 transform hover:scale-105 transition-transform duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Active</span>
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-4xl font-bold text-green-600 mb-1">{{ $stats['active'] }}</p>
                                    <p class="text-xs text-gray-500 font-medium">Currently enrolled</p>
                                </div>
                                <div class="stat-card bg-white rounded-xl p-5 shadow-md border-l-4 border-purple-500 transform hover:scale-105 transition-transform duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Graduated</span>
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-4xl font-bold text-purple-600 mb-1">{{ $stats['graduated'] }}</p>
                                    <p class="text-xs text-gray-500 font-medium">Completed studies</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('archive.store') }}" class="space-y-6">
                        @csrf

                        <!-- School Year -->
                        <div>
                            <label for="school_year" class="block text-sm font-semibold text-gray-700 mb-2">
                                School Year <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="school_year" 
                                   id="school_year" 
                                   value="{{ old('school_year') }}"
                                   placeholder="e.g., 2023-2024"
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200 @error('school_year') border-red-500 @enderror">
                            @error('school_year')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semester -->
                        <div>
                            <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <select name="semester" 
                                    id="semester" 
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200 @error('semester') border-red-500 @enderror">
                                <option value="">Select Semester</option>
                                <option value="1st Semester" {{ old('semester') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd Semester" {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Archive Reason -->
                        <div>
                            <label for="archive_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                                Archive Reason (Optional)
                            </label>
                            <textarea name="archive_reason" 
                                      id="archive_reason" 
                                      rows="3"
                                      placeholder="Enter reason for archiving (e.g., End of Academic Year 2023-2024)"
                                      class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">{{ old('archive_reason') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">This will help you remember why this archive was created</p>
                        </div>

                        <!-- Delete After Archive Option -->
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <input type="checkbox" 
                                       name="delete_after_archive" 
                                       id="delete_after_archive" 
                                       value="1"
                                       {{ old('delete_after_archive') ? 'checked' : '' }}
                                       class="mt-1 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <label for="delete_after_archive" class="ml-3 flex-1">
                                    <span class="block text-sm font-semibold text-gray-800">Delete students after archiving</span>
                                    <span class="block text-sm text-gray-600 mt-1">
                                        <strong>Warning:</strong> This will permanently remove all current students from the active list after creating the archive. Use this option only if you want a fresh start for the new school year.
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('archive.index') }}" 
                               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 font-semibold">
                                Create Archive
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
