<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grade Entry') }}
            </h2>
            <a href="{{ route('chairperson.grade-import.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Import Excel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <a href="{{ route('chairperson.grades.index', ['view' => 'students']) }}" 
                               class="py-4 px-1 border-b-2 font-medium text-sm {{ $view === 'students' ? 'border-brand-medium text-brand-deep' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                By Student
                                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $students->count() }}</span>
                            </a>
                            <a href="{{ route('chairperson.grades.index', ['view' => 'subjects']) }}" 
                               class="py-4 px-1 border-b-2 font-medium text-sm {{ $view === 'subjects' ? 'border-brand-medium text-brand-deep' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                By Subject
                                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $subjects->count() }}</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Search -->
                    <div class="mb-6">
                        <input type="text" id="searchInput" placeholder="Search..." 
                               class="border border-gray-300 rounded px-3 py-2 w-full max-w-md" autocomplete="off">
                    </div>

                    @if($view === 'students')
                        <!-- Students View -->
                        <p class="text-sm text-gray-500 mb-4">Click on a student to view and enter grades for their enrolled subjects.</p>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Student ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Student Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Program</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Year Level</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Subjects</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Progress</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($students as $student)
                                        <tr class="hover:bg-gray-50 searchable-row">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $student->student_id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $student->last_name }}, {{ $student->first_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $student->program->code ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $student->year_level }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                {{ $student->total_subjects }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $progress = $student->total_subjects > 0 ? ($student->graded_subjects / $student->total_subjects) * 100 : 0;
                                                @endphp
                                                <div class="flex items-center justify-center gap-2">
                                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                                        <div class="h-2 rounded-full {{ $progress == 100 ? 'bg-green-500' : 'bg-brand-medium' }}" style="width: {{ $progress }}%"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-600">{{ $student->graded_subjects }}/{{ $student->total_subjects }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('chairperson.grades.student', $student) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-brand-medium text-white rounded hover:bg-brand-deep transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Manage Grades
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                No students found for your department.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Subjects View -->
                        <p class="text-sm text-gray-500 mb-4">Click on a subject to enter grades for all enrolled students at once.</p>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subject Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subject Name</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Units</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Students</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Progress</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($subjects as $subject)
                                        <tr class="hover:bg-gray-50 searchable-row">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $subject->code }}</td>
                                            <td class="px-6 py-4 text-sm">{{ $subject->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $subject->units }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                {{ $subject->enrollments_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $progress = $subject->enrollments_count > 0 ? ($subject->graded_count / $subject->enrollments_count) * 100 : 0;
                                                @endphp
                                                <div class="flex items-center justify-center gap-2">
                                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                                        <div class="h-2 rounded-full {{ $progress == 100 ? 'bg-green-500' : 'bg-brand-medium' }}" style="width: {{ $progress }}%"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-600">{{ $subject->graded_count }}/{{ $subject->enrollments_count }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($subject->enrollments_count > 0)
                                                    <a href="{{ route('chairperson.grades.subject', $subject) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-brand-medium text-white rounded hover:bg-brand-deep transition">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Enter Grades
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-xs">No students enrolled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                                No subjects found for your department.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.searchable-row').forEach(function(row) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
    </script>
</x-app-layout>
