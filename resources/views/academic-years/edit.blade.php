<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Academic Year') }}: {{ $academicYear->year_code }}
            </h2>
            <a href="{{ route('academic-years.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('academic-years.update', $academicYear) }}">
                        @csrf
                        @method('PUT')

                        <!-- Year Code and Semester Row -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <!-- Year Code -->
                            <div>
                                <label for="year_code" class="block text-sm font-medium text-gray-700 mb-2">Year Code *</label>
                                <input type="text" name="year_code" id="year_code" placeholder="e.g., 2024-2025-1" value="{{ old('year_code', $academicYear->year_code) }}" required
                                       class="w-full border rounded px-3 py-2 @error('year_code') border-red-500 @enderror">
                                @error('year_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Semester -->
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester *</label>
                                <select name="semester" id="semester" required class="w-full border rounded px-3 py-2 @error('semester') border-red-500 @enderror">
                                    <option value="">Select Semester</option>
                                    <option value="1st Semester" {{ old('semester', $academicYear->semester) == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd Semester" {{ old('semester', $academicYear->semester) == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="Summer" {{ old('semester', $academicYear->semester) == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                                @error('semester')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Main Dates Section -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                            <h3 class="font-semibold text-gray-800 mb-4">Semester Dates</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $academicYear->start_date?->format('Y-m-d')) }}" required
                                           class="w-full border rounded px-3 py-2 @error('start_date') border-red-500 @enderror">
                                    @error('start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $academicYear->end_date?->format('Y-m-d')) }}" required
                                           class="w-full border rounded px-3 py-2 @error('end_date') border-red-500 @enderror">
                                    @error('end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Registration Period Section -->
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded">
                            <h3 class="font-semibold text-gray-800 mb-4">Registration Period</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="registration_start_date" class="block text-sm font-medium text-gray-700 mb-2">Registration Start</label>
                                    <input type="date" name="registration_start_date" id="registration_start_date" value="{{ old('registration_start_date', $academicYear->registration_start_date?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('registration_start_date') border-red-500 @enderror">
                                    @error('registration_start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="registration_end_date" class="block text-sm font-medium text-gray-700 mb-2">Registration End</label>
                                    <input type="date" name="registration_end_date" id="registration_end_date" value="{{ old('registration_end_date', $academicYear->registration_end_date?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('registration_end_date') border-red-500 @enderror">
                                    @error('registration_end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="add_drop_deadline" class="block text-sm font-medium text-gray-700 mb-2">Add/Drop Deadline</label>
                                    <input type="date" name="add_drop_deadline" id="add_drop_deadline" value="{{ old('add_drop_deadline', $academicYear->add_drop_deadline?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('add_drop_deadline') border-red-500 @enderror">
                                    @error('add_drop_deadline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Classes Period Section -->
                        <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded">
                            <h3 class="font-semibold text-gray-800 mb-4">Classes Period</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="classes_start_date" class="block text-sm font-medium text-gray-700 mb-2">Classes Start</label>
                                    <input type="date" name="classes_start_date" id="classes_start_date" value="{{ old('classes_start_date', $academicYear->classes_start_date?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('classes_start_date') border-red-500 @enderror">
                                    @error('classes_start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="classes_end_date" class="block text-sm font-medium text-gray-700 mb-2">Classes End</label>
                                    <input type="date" name="classes_end_date" id="classes_end_date" value="{{ old('classes_end_date', $academicYear->classes_end_date?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('classes_end_date') border-red-500 @enderror">
                                    @error('classes_end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Midterm Period Section -->
                        <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded">
                            <h3 class="font-semibold text-gray-800 mb-4">Midterm Period</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="midterm_start_date" class="block text-sm font-medium text-gray-700 mb-2">Midterm Start</label>
                                    <input type="date" name="midterm_start_date" id="midterm_start_date" value="{{ old('midterm_start_date', $academicYear->midterm_start_date?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('midterm_start_date') border-red-500 @enderror">
                                    @error('midterm_start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="midterm_end_date" class="block text-sm font-medium text-gray-700 mb-2">Midterm End</label>
                                    <input type="date" name="midterm_end_date" id="midterm_end_date" value="{{ old('midterm_end_date', $academicYear->midterm_end_date?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('midterm_end_date') border-red-500 @enderror">
                                    @error('midterm_end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Exam Period Section -->
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded">
                            <h3 class="font-semibold text-gray-800 mb-4">Exam Period</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="exam_start_date" class="block text-sm font-medium text-gray-700 mb-2">Exam Start</label>
                                    <input type="date" name="exam_start_date" id="exam_start_date" value="{{ old('exam_start_date', $academicYear->exam_start_date?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('exam_start_date') border-red-500 @enderror">
                                    @error('exam_start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="exam_end_date" class="block text-sm font-medium text-gray-700 mb-2">Exam End</label>
                                    <input type="date" name="exam_end_date" id="exam_end_date" value="{{ old('exam_end_date', $academicYear->exam_end_date?->format('Y-m-d')) }}"
                                           class="w-full border rounded px-3 py-2 @error('exam_end_date') border-red-500 @enderror">
                                    @error('exam_end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                                Update Academic Year
                            </button>
                            <a href="{{ route('academic-years.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
