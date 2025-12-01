<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Map Import Columns') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">File: {{ $fileName }}</h3>
                    
                    <p class="text-gray-600 mb-6">
                        We detected {{ count($headers) }} column(s) in your file. Please confirm which columns contain the Student ID, Subject Code, and Grade.
                    </p>

                    <!-- Auto-detected columns info -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm font-medium text-blue-900 mb-2">Auto-detected Mapping:</p>
                        <div class="text-sm text-blue-800 space-y-1">
                            @if(isset($autoDetectedMapping['student_id']))
                                <div>Student ID: <strong>{{ $headers[$autoDetectedMapping['student_id']] ?? 'Column ' . $autoDetectedMapping['student_id'] }}</strong></div>
                            @else
                                <div class="text-yellow-700">⚠ Student ID: Not detected</div>
                            @endif
                            
                            @if(isset($autoDetectedMapping['subject_code']))
                                <div>Subject Code: <strong>{{ $headers[$autoDetectedMapping['subject_code']] ?? 'Column ' . $autoDetectedMapping['subject_code'] }}</strong></div>
                            @else
                                <div class="text-yellow-700">⚠ Subject Code: Not detected</div>
                            @endif
                            
                            @if(isset($autoDetectedMapping['grade']))
                                <div>Grade: <strong>{{ $headers[$autoDetectedMapping['grade']] ?? 'Column ' . $autoDetectedMapping['grade'] }}</strong></div>
                            @else
                                <div class="text-yellow-700">⚠ Grade: Not detected</div>
                            @endif
                        </div>
                    </div>

                    <!-- Column mapping form -->
                    <form method="POST" action="{{ route('chairperson.grade-import.preview') }}" class="space-y-6">
                        @csrf

                        <!-- Student ID Column -->
                        <div>
                            <label for="student_id_column" class="block text-sm font-medium text-gray-700 mb-2">
                                Student ID Column <span class="text-red-500">*</span>
                            </label>
                            <select id="student_id_column" 
                                    name="student_id_column" 
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-brand-medium focus:border-brand-medium @error('student_id_column') border-red-500 @enderror"
                                    required>
                                <option value="">-- Select Column --</option>
                                @foreach($headers as $index => $header)
                                    <option value="{{ $index }}" {{ isset($autoDetectedMapping['student_id']) && $autoDetectedMapping['student_id'] == $index ? 'selected' : '' }}>
                                        {{ $header }} (Column {{ $index }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id_column')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Subject Code Column -->
                        <div>
                            <label for="subject_code_column" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject Code Column <span class="text-red-500">*</span>
                            </label>
                            <select id="subject_code_column" 
                                    name="subject_code_column" 
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-brand-medium focus:border-brand-medium @error('subject_code_column') border-red-500 @enderror"
                                    required>
                                <option value="">-- Select Column --</option>
                                @foreach($headers as $index => $header)
                                    <option value="{{ $index }}" {{ isset($autoDetectedMapping['subject_code']) && $autoDetectedMapping['subject_code'] == $index ? 'selected' : '' }}>
                                        {{ $header }} (Column {{ $index }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_code_column')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Grade Column -->
                        <div>
                            <label for="grade_column" class="block text-sm font-medium text-gray-700 mb-2">
                                Grade Column <span class="text-red-500">*</span>
                            </label>
                            <select id="grade_column" 
                                    name="grade_column" 
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-brand-medium focus:border-brand-medium @error('grade_column') border-red-500 @enderror"
                                    required>
                                <option value="">-- Select Column --</option>
                                @foreach($headers as $index => $header)
                                    <option value="{{ $index }}" {{ isset($autoDetectedMapping['grade']) && $autoDetectedMapping['grade'] == $index ? 'selected' : '' }}>
                                        {{ $header }} (Column {{ $index }})
                                    </option>
                                @endforeach
                            </select>
                            @error('grade_column')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Info box -->
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-sm text-green-800">
                                <strong>Grade Format Accepted:</strong><br>
                                • Percentage (0-100) - will be converted to Philippine scale<br>
                                • Philippine scale (1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00)<br>
                                • Special grades: IP (In Progress) or INC (Incomplete)
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            <a href="{{ route('chairperson.grade-import.create') }}" 
                               class="text-gray-700 hover:text-gray-900 font-medium">
                                ← Back
                            </a>
                            <div class="flex gap-3">
                                <a href="{{ route('chairperson.grade-import.create') }}" 
                                   class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                    Preview Data →
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
