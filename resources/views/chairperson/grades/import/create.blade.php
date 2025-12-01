<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Upload Grade File') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Instructions -->
                    <div class="mb-8 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <p class="text-sm text-blue-700 font-medium">File Format Requirements:</p>
                        <ul class="text-sm text-blue-600 mt-2 space-y-1">
                            <li>• Format: Excel (.xlsx, .xls) or CSV</li>
                            <li>• Maximum file size: 10 MB</li>
                            <li>• Required columns: Student ID, Subject Code, Grade</li>
                            <li>• Grade range: 0 to 100</li>
                        </ul>
                    </div>

                    <!-- Upload Form -->
                    <form method="POST" action="{{ route('chairperson.grade-import.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- File Input -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                Select File <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="file" 
                                       id="file" 
                                       name="file"
                                       accept=".xlsx,.xls,.csv"
                                       class="block w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer
                                              hover:border-brand-medium focus:border-brand-medium focus:outline-none
                                              @error('file') border-red-500 @enderror"
                                       onchange="updateFileName(this)">
                                <p class="text-xs text-gray-500 mt-2">Click to select file or drag and drop</p>
                            </div>
                            <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                            @error('file')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- File Preview (Placeholder) -->
                        <div id="filePreview" class="hidden p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-2">File Preview:</p>
                            <div id="previewContent" class="text-sm text-gray-600"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            <a href="{{ route('chairperson.grades.index') }}" 
                               class="text-gray-700 hover:text-gray-900 font-medium">
                                ← Back to Grades
                            </a>
                            <div class="flex gap-3">
                                <a href="{{ route('chairperson.grades.index') }}" 
                                   class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        id="submitBtn"
                                        disabled
                                        class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                    Upload & Preview
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sample File Section -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Expected File Format</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Student ID</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Subject Code</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="bg-gray-50">
                                    <td class="px-4 py-2 text-gray-600">STU-001</td>
                                    <td class="px-4 py-2 text-gray-600">CS101</td>
                                    <td class="px-4 py-2 text-gray-600">85.50</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-gray-600">STU-002</td>
                                    <td class="px-4 py-2 text-gray-600">CS101</td>
                                    <td class="px-4 py-2 text-gray-600">92.00</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="px-4 py-2 text-gray-600">STU-003</td>
                                    <td class="px-4 py-2 text-gray-600">CS102</td>
                                    <td class="px-4 py-2 text-gray-600">78.75</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function updateFileName(input) {
        const submitBtn = document.getElementById('submitBtn');
        const fileName = document.getElementById('fileName');
        
        if (input.files && input.files[0]) {
            fileName.textContent = 'Selected: ' + input.files[0].name;
            submitBtn.disabled = false;
        } else {
            fileName.textContent = '';
            submitBtn.disabled = true;
        }
    }
    </script>
</x-app-layout>
