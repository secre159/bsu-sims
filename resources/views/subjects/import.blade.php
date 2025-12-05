<x-app-layout>
    <x-slot name="title">Import Subjects</x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Import Subjects</h1>
                <p class="text-gray-600">Upload a CSV or Excel file to bulk import subjects with prerequisites</p>
            </div>

            <!-- Download Template Button -->
            <div class="mb-6">
                <a href="{{ route('subjects.import.template') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download CSV Template
                </a>
            </div>

            <!-- Instructions Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    File Format Instructions
                </h3>
                <div class="text-sm text-blue-800 space-y-2">
                    <p><strong>Required Columns (in this order):</strong></p>
                    <ol class="list-decimal list-inside ml-4 space-y-1">
                        <li><strong>Code</strong> - Subject code (e.g., CS101, MATH201)</li>
                        <li><strong>Name</strong> - Full subject name</li>
                        <li><strong>Description</strong> - Subject description (optional)</li>
                        <li><strong>Units</strong> - Credit units (1-10)</li>
                        <li><strong>Program Code</strong> - Must match existing program code (e.g., BSIT, BSEd)</li>
                        <li><strong>Year Level</strong> - Must be: 1st Year, 2nd Year, 3rd Year, or 4th Year</li>
                        <li><strong>Semester</strong> - Must be: 1st Semester, 2nd Semester, or Summer</li>
                        <li><strong>Prerequisites</strong> - Comma-separated subject codes (e.g., CS101,MATH101) or leave empty</li>
                    </ol>
                    <p class="mt-3"><strong>Important Notes:</strong></p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>First row must be the header row</li>
                        <li>Subject codes must be unique</li>
                        <li>Prerequisite subjects must be included in the same import file or already exist</li>
                        <li>Programs must exist before importing subjects</li>
                    </ul>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <form action="{{ route('subjects.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Select File (CSV or Excel)
                        </label>
                        <input type="file" 
                               name="file" 
                               accept=".csv,.xlsx"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                      file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sample CSV Preview -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Sample CSV Format:</p>
                        <pre class="text-xs text-gray-600 overflow-x-auto">Code,Name,Description,Units,Program Code,Year Level,Semester,Prerequisites
CS101,Introduction to Programming,Basic programming concepts,3,BSIT,1st Year,1st Semester,
CS102,Data Structures,Arrays and linked lists,3,BSIT,1st Year,2nd Semester,CS101
MATH101,Calculus 1,Differential calculus,3,BSIT,1st Year,1st Semester,
CS201,Object-Oriented Programming,OOP principles,3,BSIT,2nd Year,1st Semester,"CS101,CS102"</pre>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('subjects.index') }}" 
                           class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium rounded-lg 
                                       hover:from-indigo-700 hover:to-indigo-800 focus:ring-4 focus:ring-indigo-300 transition-all">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload and Import
                        </button>
                    </div>
                </form>
            </div>

            <!-- Error Display -->
            @if(session('import_errors'))
                <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-red-900 mb-3">Import Errors:</h3>
                    <ul class="list-disc list-inside text-sm text-red-800 space-y-1 max-h-64 overflow-y-auto">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
