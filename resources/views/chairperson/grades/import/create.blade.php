<x-app-layout>
    <x-slot name="title">Import Grades</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Upload Grade File') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Import grades from Excel or CSV file</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Pending Batches Alert -->
            @if($pendingBatchesCount > 0)
                <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-amber-800 font-semibold">Import Limit</p>
                            <p class="text-sm text-amber-700 mt-1">
                                You have <strong>{{ $pendingBatchesCount }}</strong> pending batch(es). 
                                You can create up to <strong>{{ 3 - $pendingBatchesCount }}</strong> more before reaching the limit.
                                <a href="{{ route('chairperson.grade-batches.index') }}" class="underline hover:text-amber-900">View batches</a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Download Template Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-emerald-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Download Pre-filled Template</h3>
                            <p class="text-sm text-gray-600">Get a CSV template with student names already filled in - just add grades!</p>
                        </div>
                    </div>

                    <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-emerald-800">
                                <p class="font-semibold mb-1">Recommended Workflow:</p>
                                <ol class="list-decimal list-inside space-y-1">
                                    <li>Select a subject below</li>
                                    <li>Download the pre-filled template</li>
                                    <li>Open in Excel and fill in the grades</li>
                                    <li>Upload the completed file using the form below</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="template_subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                Select Subject
                            </label>
                            <select id="template_subject" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                                    onchange="updateDownloadButton()">
                                <option value="">-- Choose a subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" 
                                id="downloadTemplateBtn"
                                disabled
                                onclick="downloadTemplate()"
                                class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-lg font-medium transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Template for Selected Subject
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upload Form Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900">
                    <!-- Instructions -->
                    <div class="mb-8 p-4 bg-indigo-50 border-l-4 border-indigo-500 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-indigo-800 font-semibold mb-2">File Format Requirements:</p>
                                <ul class="text-sm text-indigo-700 space-y-1">
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Format: Excel (.xlsx, .xls) or CSV
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Maximum file size: 10 MB
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Required columns: Student ID, Subject Code, Grade
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Grade range: 0 to 100
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <form method="POST" action="{{ route('chairperson.grade-import.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- File Input -->
                        <div>
                            <label for="file" class="block text-sm font-semibold text-gray-700 mb-3">
                                Select File <span class="text-red-500">*</span>
                            </label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 transition-all bg-gray-50 hover:bg-indigo-50">
                                <input type="file" 
                                       id="file" 
                                       name="file"
                                       accept=".xlsx,.xls,.csv"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       onchange="updateFileName(this)">
                                <div class="pointer-events-none">
                                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mt-3 text-sm font-medium text-gray-700">Click to upload or drag and drop</p>
                                    <p class="mt-1 text-xs text-gray-500">Excel (.xlsx, .xls) or CSV files</p>
                                </div>
                            </div>
                            <p id="fileName" class="text-sm text-gray-700 mt-3 font-medium"></p>
                            @error('file')
                                <span class="text-red-500 text-sm flex items-center mt-2">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- File Preview (Placeholder) -->
                        <div id="filePreview" class="hidden p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-2">File Preview:</p>
                            <div id="previewContent" class="text-sm text-gray-600"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <a href="{{ route('chairperson.grades.index') }}" 
                               class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Grades
                            </a>
                            <div class="flex gap-3">
                                <a href="{{ route('chairperson.grades.index') }}" 
                                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-all shadow-sm hover:shadow">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        id="submitBtn"
                                        disabled
                                        class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg font-medium transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Upload & Preview
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sample File Section -->
            <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-purple-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Expected File Format</h3>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Subject Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">STU-001</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">CS101</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">85.50</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">STU-002</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">CS101</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">92.00</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">STU-003</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">CS102</td>
                                    <td class="px-6 py-3 text-sm text-gray-700">78.75</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function updateDownloadButton() {
        const select = document.getElementById('template_subject');
        const downloadBtn = document.getElementById('downloadTemplateBtn');
        
        if (select.value) {
            downloadBtn.disabled = false;
        } else {
            downloadBtn.disabled = true;
        }
    }
    
    function downloadTemplate() {
        const select = document.getElementById('template_subject');
        const subjectId = select.value;
        
        if (subjectId) {
            window.location.href = `{{ route('chairperson.grade-import.download-template', ':subject') }}`.replace(':subject', subjectId);
        }
    }
    
    function updateFileName(input) {
        const submitBtn = document.getElementById('submitBtn');
        const fileName = document.getElementById('fileName');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
            fileName.innerHTML = `
                <div class="flex items-center p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-emerald-800">${file.name}</p>
                        <p class="text-xs text-emerald-600">${fileSize} MB</p>
                    </div>
                </div>
            `;
            submitBtn.disabled = false;
        } else {
            fileName.innerHTML = '';
            submitBtn.disabled = true;
        }
    }
    </script>
</x-app-layout>
