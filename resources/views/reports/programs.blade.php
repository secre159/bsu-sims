<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Students by Program Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center no-print">
                        <h3 class="text-lg font-semibold">Students Grouped by Program</h3>
                        <button type="button" onclick="window.print()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Print Report
                        </button>
                    </div>

                    @foreach($programs as $program)
                        <div class="mb-8">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-3">
                                <h4 class="font-bold text-lg">{{ $program->code }} - {{ $program->name }}</h4>
                                <p class="text-sm text-gray-600">Total Students: {{ $program->students_count }}</p>
                            </div>

                            @if($program->students->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200 mb-4">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Year Level</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($program->students as $student)
                                            <tr>
                                                <td class="px-4 py-2 text-sm">{{ $student->student_id }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $student->last_name }}, {{ $student->first_name }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $student->year_level }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $student->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-gray-500 italic ml-4">No students enrolled in this program.</p>
                            @endif
                        </div>
                    @endforeach

                    <div class="mt-6 pt-4 border-t text-sm text-gray-600">
                        <p><strong>Total Programs:</strong> {{ $programs->count() }}</p>
                        <p><strong>Total Students:</strong> {{ $programs->sum('students_count') }}</p>
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
