<x-app-layout>
    <x-slot name="title">Year Levels Report</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Students by Year Level Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Students Grouped by Year Level</h3>
                        <a href="{{ route('reports.year-levels.pdf') }}" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>
                    </div>

                    @foreach($studentsByYear as $yearLevel => $students)
                        <div class="mb-8">
                            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-3">
                                <h4 class="font-bold text-lg">{{ $yearLevel }}</h4>
                                <p class="text-sm text-gray-600">Total Students: {{ $students->count() }}</p>
                            </div>

                            @if($students->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200 mb-4">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($students as $student)
                                            <tr>
                                                <td class="px-4 py-2 text-sm">{{ $student->student_id }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $student->last_name }}, {{ $student->first_name }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $student->program->code ?? 'N/A' }}</td>
                                                <td class="px-4 py-2 text-sm">
                                                    @php
                                                        $type = $student->attendance_type ?: ($student->is_irregular ? 'Irregular' : 'Regular');
                                                    @endphp
                                                    <span class="px-2 py-1 text-xs rounded {{ $type === 'Irregular' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ $type }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 text-sm">{{ $student->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-gray-500 italic ml-4">No students in this year level.</p>
                            @endif
                        </div>
                    @endforeach

                    <div class="mt-6 pt-4 border-t text-sm text-gray-600">
                        <p><strong>Total Students:</strong> {{ collect($studentsByYear)->flatten()->count() }}</p>
                        <p><strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</x-app-layout>
