<x-app-layout>
    <x-slot name="title">Semester Transition</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Semester Transition Wizard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <strong>Step 1 of 3:</strong> Select Academic Years<br>
                            This wizard will transition all students from the current academic year to the next, calculating their academic standing and creating appropriate enrollments.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('semester-transition.validate') }}" class="space-y-6">
                        @csrf

                        <!-- Current Year Selection -->
                        <div>
                            <label for="current_year_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Current Academic Year <span class="text-red-500">*</span>
                            </label>
                            <select id="current_year_id" 
                                    name="current_year_id" 
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-brand-medium focus:border-brand-medium @error('current_year_id') border-red-500 @enderror"
                                    required>
                                <option value="">-- Select Current Year --</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">
                                        {{ $year->year }}
                                        @if($year->is_current)
                                            (Current)
                                        @endif
                                        @if($year->is_archived)
                                            (Archived)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('current_year_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Next Year Selection -->
                        <div>
                            <label for="next_year_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Next Academic Year <span class="text-red-500">*</span>
                            </label>
                            <select id="next_year_id" 
                                    name="next_year_id" 
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-brand-medium focus:border-brand-medium @error('next_year_id') border-red-500 @enderror"
                                    required>
                                <option value="">-- Select Next Year --</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">
                                        {{ $year->year }}
                                        @if($year->is_archived)
                                            (Archived)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('next_year_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Warning Box -->
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-sm text-yellow-800">
                                <strong>⚠ Important:</strong> This operation will:
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    <li>Calculate academic standing for all students</li>
                                    <li>Archive the current academic year (read-only)</li>
                                    <li>Create new enrollments for next year based on student performance</li>
                                    <li>Mark students as: Promoted, Irregular, Retained, or Probation</li>
                                </ul>
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            <a href="{{ route('dashboard') }}" 
                               class="text-gray-700 hover:text-gray-900 font-medium">
                                ← Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                Validate & Continue →
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
