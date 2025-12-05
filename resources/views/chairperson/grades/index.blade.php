<x-app-layout>
    <x-slot name="title">Grade Entry</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Grade Entry') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage student grades by student or subject</p>
            </div>
            <a href="{{ route('chairperson.grade-import.create') }}" class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Import Excel
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-lg shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-indigo-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Students with Grades</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $students->count() }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-purple-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Active Subjects</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $subjects->count() }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900">
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <a href="{{ route('chairperson.grades.index', ['view' => 'students']) }}" 
                               class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all {{ $view === 'students' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                <svg class="w-5 h-5 mr-2 {{ $view === 'students' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                By Student
                                <span class="ml-2 {{ $view === 'students' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }} py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ $students->count() }}</span>
                            </a>
                            <a href="{{ route('chairperson.grades.index', ['view' => 'subjects']) }}" 
                               class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all {{ $view === 'subjects' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                <svg class="w-5 h-5 mr-2 {{ $view === 'subjects' ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                By Subject
                                <span class="ml-2 {{ $view === 'subjects' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }} py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ $subjects->count() }}</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Search -->
                    <div class="mb-6">
                        <div class="relative max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="searchInput" placeholder="Search by name, ID, or code..." 
                                   class="pl-10 border border-gray-300 rounded-lg px-4 py-2.5 w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" autocomplete="off">
                        </div>
                    </div>

                    @if($view === 'students')
                        <!-- Students View -->
                        <p class="text-sm text-gray-500 mb-4">Click on a student to view and enter grades for their enrolled subjects.</p>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
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
                                                        <div class="h-2 rounded-full {{ $progress == 100 ? 'bg-emerald-500' : 'bg-indigo-500' }}" style="width: {{ $progress }}%"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-600">{{ $student->graded_subjects }}/{{ $student->total_subjects }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('chairperson.grades.student', $student) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md">
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
                        
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
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
                                                        <div class="h-2 rounded-full {{ $progress == 100 ? 'bg-emerald-500' : 'bg-purple-500' }}" style="width: {{ $progress }}%"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-600">{{ $subject->graded_count }}/{{ $subject->enrollments_count }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($subject->enrollments_count > 0)
                                                    <a href="{{ route('chairperson.grades.subject', $subject) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all shadow-sm hover:shadow-md">
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
