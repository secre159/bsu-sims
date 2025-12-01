<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('chairperson.grades.index', ['view' => 'students']) }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← Back to Students
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-1">
                    {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}
                </h2>
            </div>
            <div class="text-right text-sm text-gray-600">
                <div><strong>Student ID:</strong> {{ $student->student_id }}</div>
                <div><strong>Program:</strong> {{ $student->program->code ?? 'N/A' }} - {{ $student->year_level }}</div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Enrolled Subjects & Grades</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subject Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subject Name</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Units</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Current Grade</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($enrollments as $enrollment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{ $enrollment->subject->code }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ $enrollment->subject->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            {{ $enrollment->subject->units }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($enrollment->grade !== null)
                                                @if(is_numeric($enrollment->grade))
                                                    <span class="text-lg font-bold text-brand-deep">{{ number_format($enrollment->grade, 2) }}</span>
                                                @else
                                                    <span class="text-lg font-bold text-orange-600">{{ $enrollment->grade }}</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 italic">No grade</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-3 py-1 text-xs rounded-full font-medium
                                                @if($enrollment->status == 'Enrolled') bg-blue-100 text-blue-800
                                                @elseif($enrollment->status == 'Completed') bg-green-100 text-green-800
                                                @elseif($enrollment->status == 'Failed') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $enrollment->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('chairperson.grades.edit', $enrollment) }}" 
                                               class="text-brand-deep hover:text-brand-medium font-medium">
                                                {{ $enrollment->grade !== null ? 'Edit' : 'Enter Grade' }}
                                            </a>
                                            @if($enrollment->gradeHistories()->count() > 0)
                                                <a href="{{ route('chairperson.grades.history', $enrollment) }}" 
                                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                                    History
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-brand-deep">{{ $enrollments->count() }}</div>
                                <div class="text-sm text-gray-600">Total Subjects</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-600">{{ $enrollments->whereNotNull('grade')->count() }}</div>
                                <div class="text-sm text-gray-600">Graded</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-yellow-600">{{ $enrollments->whereNull('grade')->count() }}</div>
                                <div class="text-sm text-gray-600">Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
