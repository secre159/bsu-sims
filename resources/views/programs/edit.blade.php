<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Program') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('programs.update', $program) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Program Code -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Program Code</label>
                            <input type="text" name="code" value="{{ old('code', $program->code) }}" 
                                   class="w-full border rounded px-3 py-2 @error('code') border-red-500 @enderror" required>
                            @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Program Name -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Program Name</label>
                            <input type="text" name="name" value="{{ old('name', $program->name) }}" 
                                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Department -->
                        <div class="mb-4">
<label class="block text-sm font-medium mb-2">Department <span class="text-red-500">*</span></label>
                            <select name="department_id" class="w-full border rounded px-3 py-2 @error('department_id') border-red-500 @enderror">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $program->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }} ({{ $department->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Description (Optional)</label>
                            <textarea name="description" rows="3" 
                                      class="w-full border rounded px-3 py-2">{{ old('description', $program->description) }}</textarea>
                        </div>

                        <!-- Is Offered -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', $program->is_active) ? 'checked' : '' }} 
                                       class="rounded border-gray-300">
                                <span class="ml-2 text-sm">Offered Program</span>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                                Update Program
                            </button>
                            <a href="{{ route('programs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded inline-block">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
