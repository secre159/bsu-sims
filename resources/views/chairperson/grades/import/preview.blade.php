<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Preview Import Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-2">File: {{ $fileName }}</h3>
                    
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800 mb-3"><strong>Column Mapping:</strong></p>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Student ID:</span><br>
                                <strong>{{ $headers[$columnMapping['student_id']] ?? 'Column ' . $columnMapping['student_id'] }}</strong>
                            </div>
                            <div>
                                <span class="text-gray-600">Subject Code:</span><br>
                                <strong>{{ $headers[$columnMapping['subject_code']] ?? 'Column ' . $columnMapping['subject_code'] }}</strong>
                            </div>
                            <div>
                                <span class="text-gray-600">Grade:</span><br>
                                <strong>{{ $headers[$columnMapping['grade']] ?? 'Column ' . $columnMapping['grade'] }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Preview data table -->
                    <div class="mb-6 overflow-x-auto border rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Student ID</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Subject Code</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Grade (Raw)</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Grade (Normalized)</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($previewData as $index => $row)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-600">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-gray-900">{{ $row['student_id'] }}</td>
                                        <td class="px-4 py-3 text-gray-900">{{ $row['subject_code'] }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $row['grade_raw'] }}</td>
                                        <td class="px-4 py-3">
                                            @if($row['grade_normalized'])
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if(in_array($row['grade_normalized'], ['1.00', '1.25', '1.50', '1.75', '2.00', '2.25', '2.50', '2.75', '3.00']))
                                                        bg-green-100 text-green-800
                                                    @elseif($row['grade_normalized'] === '5.00')
                                                        bg-red-100 text-red-800
                                                    @else
                                                        bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ $row['grade_normalized'] }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($row['grade_error'])
                                                <span class="inline-block px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">
                                                    Error
                                                </span>
                                            @elseif($row['grade_normalized'])
                                                <span class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">
                                                    ✓ Valid
                                                </span>
                                            @else
                                                <span class="inline-block px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($row['grade_error'])
                                        <tr class="bg-red-50">
                                            <td colspan="6" class="px-4 py-2 text-sm text-red-700">
                                                <strong>Error:</strong> {{ $row['grade_error'] }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            Showing first {{ count($previewData) }} rows. Review the data above to ensure it's correct before proceeding.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t">
                        <a href="{{ route('chairperson.grade-import.back-to-mapping') }}" class="text-gray-700 hover:text-gray-900 font-medium">
                            ← Back to Mapping
                        </a>
                        <div class="flex gap-3">
                            <a href="{{ route('chairperson.grade-import.create') }}" 
                               class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                Cancel
                            </a>
                            <form method="POST" action="{{ route('chairperson.grade-import.process') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                    Import Grades →
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
