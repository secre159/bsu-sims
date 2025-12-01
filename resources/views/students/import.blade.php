<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Import Students') }}
            </h2>
            <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                ← Back to Students
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Instructions Card -->
            <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-indigo-500 p-6 rounded-xl shadow-sm">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-indigo-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2">How to Import Students</h3>
                        <ol class="list-decimal list-inside text-sm text-gray-600 space-y-1">
                            <li>Download the CSV template below</li>
                            <li>Fill in student information following the format</li>
                            <li>Save your file as CSV format</li>
                            <li>Upload the file using the form below</li>
                        </ol>
                        <div class="mt-4">
                            <a href="{{ route('students.import.template') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download CSV Template
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CSV Format Info -->
            <div class="mb-6 bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    CSV Format Requirements
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700">Column</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700">Required</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-700">Example</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-2 font-mono text-indigo-600">student_id</td>
                                <td class="px-4 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-4 py-2">2024-0001</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2 font-mono text-indigo-600">last_name</td>
                                <td class="px-4 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-4 py-2">Dela Cruz</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-mono text-indigo-600">first_name</td>
                                <td class="px-4 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-4 py-2">Juan</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2 font-mono text-indigo-600">middle_name</td>
                                <td class="px-4 py-2">No</td>
                                <td class="px-4 py-2">Santos</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-mono text-indigo-600">email</td>
                                <td class="px-4 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-4 py-2">juan@student.bsu.edu.ph</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2 font-mono text-indigo-600">contact_number</td>
                                <td class="px-4 py-2">No</td>
                                <td class="px-4 py-2">09123456789</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-mono text-indigo-600">program_code</td>
                                <td class="px-4 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-4 py-2">
                                    @foreach($programs as $program)
                                        <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded mr-1 mb-1">{{ $program->code }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2 font-mono text-indigo-600">year_level</td>
                                <td class="px-4 py-2"><span class="text-red-600">Yes</span></td>
                                <td class="px-4 py-2">1st Year, 2nd Year, 3rd Year, 4th Year</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-mono text-indigo-600">status</td>
                                <td class="px-4 py-2">No</td>
                                <td class="px-4 py-2">Active (default), Inactive, Graduated</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload CSV File
                </h3>

                <form method="POST" action="{{ route('students.import') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Select CSV File <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" 
                                   name="csv_file" 
                                   accept=".csv,.txt"
                                   required
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                        </div>
                        @error('csv_file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Maximum file size: 2MB. Accepted formats: .csv, .txt</p>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('students.index') }}" 
                           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Import Students
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
