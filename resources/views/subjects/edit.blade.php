<x-app-layout>
    <x-slot name="title">Edit Subject</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Subject') }}
            </h2>
            <a href="{{ route('subjects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('subjects.update', $subject) }}">
                        @csrf
                        @method('PUT')

                        <!-- Subject Code -->
                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Subject Code *</label>
                            <input type="text" name="code" id="code" value="{{ old('code', $subject->code) }}" required
                                   class="w-full border rounded px-3 py-2 @error('code') border-red-500 @enderror">
                            @error('code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Subject Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" required
                                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="mb-4">
                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <select name="department_id" id="department_id"
                                    class="w-full border rounded px-3 py-2 @error('department_id') border-red-500 @enderror">
                                <option value="">Select Department (optional)</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $subject->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} ({{ $dept->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $subject->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Program -->
                        <div class="mb-4">
                            <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">Program *</label>
                            <select name="program_id" id="program_id" required
                                    class="w-full border rounded px-3 py-2 @error('program_id') border-red-500 @enderror">
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ old('program_id', $subject->program_id) == $program->id ? 'selected' : '' }}>
                                        {{ $program->name }} ({{ $program->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('program_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <!-- Year Level -->
                            <div>
                                <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level *</label>
                                <select name="year_level" id="year_level" required
                                        class="w-full border rounded px-3 py-2 @error('year_level') border-red-500 @enderror">
                                    <option value="">Select</option>
                                    <option value="1st Year" {{ old('year_level', $subject->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('year_level', $subject->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_level', $subject->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_level', $subject->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                </select>
                                @error('year_level')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Semester -->
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester *</label>
                                <select name="semester" id="semester" required
                                        class="w-full border rounded px-3 py-2 @error('semester') border-red-500 @enderror">
                                    <option value="">Select</option>
                                    <option value="1st Semester" {{ old('semester', $subject->semester) == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd Semester" {{ old('semester', $subject->semester) == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="Summer" {{ old('semester', $subject->semester) == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                                @error('semester')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Units -->
                            <div>
                                <label for="units" class="block text-sm font-medium text-gray-700 mb-2">Units *</label>
                                <input type="number" name="units" id="units" value="{{ old('units', $subject->units) }}" required min="1" max="10"
                                       class="w-full border rounded px-3 py-2 @error('units') border-red-500 @enderror">
                                @error('units')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                                Update Subject
                            </button>
                            <a href="{{ route('subjects.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Prerequisites Card -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Prerequisites</h3>
                    <p class="text-sm text-gray-600 mb-4">Select subjects that must be completed before taking this course. This is based on completion, not grades.</p>
                    <form method="POST" action="{{ route('subjects.update', $subject) }}">
                        @csrf
                        @method('PUT')
                        <div id="prerequisites-container" class="border rounded p-4 bg-gray-50" style="height: 300px; overflow-y: auto;">
                            @forelse($subjects ?? [] as $prereq)
                                @if($prereq->id !== $subject->id)
                                    <label class="flex items-center mb-3 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                        <input type="checkbox" name="prerequisite_subject_ids[]" value="{{ $prereq->id }}"
                                               {{ in_array($prereq->id, old('prerequisite_subject_ids', $subject->prerequisite_subject_ids ?? [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-3 text-sm text-gray-700">{{ $prereq->code }} - {{ $prereq->name }} <span class="text-gray-500">({{ $prereq->year_level }})</span></span>
                                    </label>
                                @endif
                            @empty
                                <p class="text-sm text-gray-500">No other subjects available.</p>
                            @endforelse
                        </div>
                        @error('prerequisite_subject_ids')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <div class="mt-4 flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                Save Prerequisites
                            </button>
                            <a href="{{ route('subjects.edit', $subject) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded text-sm">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
