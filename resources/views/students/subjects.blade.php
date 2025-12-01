<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Subject Enrollment') }} - {{ $student->first_name }} {{ $student->last_name }}
            </h2>
            <a href="{{ route('students.show', $student) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Back to Student
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Student Info Card -->
            <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 p-6 rounded-xl shadow-sm">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-indigo-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $student->program->name ?? 'N/A' }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $student->year_level }} · {{ $student->student_id }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Available Subjects -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                        <h3 class="font-bold text-white text-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Available Subjects ({{ $availableSubjects->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        @forelse($availableSubjects as $subject)
                                        @php
                                            $isEnrolled = $enrolledSubjects->contains(function($enrollment) use ($subject) {
                                                return $enrollment->subject_id === $subject->id;
                                            });
                                        @endphp
                                        
                                        <div class="mb-4 p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-300 transition-colors duration-200">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded">
                                                            {{ $subject->code }}
                                                        </span>
                                                        <span class="text-xs text-gray-500">{{ $subject->units }} units</span>
                                                    </div>
                                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $subject->name }}</h4>
                                                    @if($subject->description)
                                                        <p class="text-sm text-gray-600 mb-2">{{ $subject->description }}</p>
                                                    @endif
                                                    <p class="text-xs text-gray-500">{{ $subject->semester }}</p>
                                                </div>
                                                
                                                @if($isEnrolled)
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                        Enrolled
                                                    </span>
                                                @else
                                                    <form method="POST" action="{{ route('students.subjects.enroll', $student) }}">
                                                        @csrf
                                                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                                        <button type="submit" 
                                                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-200">
                                                            Enroll
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Subjects Available</h3>
                                <p class="text-gray-500 text-sm">No subjects found for {{ $student->program->name }} - {{ $student->year_level }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Enrolled Subjects -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <h3 class="font-bold text-white text-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Enrolled Subjects ({{ $enrolledSubjects->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        @forelse($enrolledSubjects->groupBy('subject.year_level') as $yearLevel => $enrollments)
                            <!-- Year Level Header -->
                            <div class="mb-4">
                                <div class="flex items-center mb-3">
                                    <div class="flex-1 border-t border-gray-300"></div>
                                    <span class="px-3 text-sm font-bold text-gray-600 uppercase">{{ $yearLevel }}</span>
                                    <div class="flex-1 border-t border-gray-300"></div>
                                </div>

                                @foreach($enrollments->groupBy('subject.semester') as $semester => $semesterEnrollments)
                                    <!-- Semester Subheader -->
                                    <div class="mb-2 ml-2">
                                        <span class="text-xs font-semibold text-gray-500">{{ $semester }}</span>
                                    </div>

                                @foreach($semesterEnrollments as $enrollment)
                                        @if($enrollment->subject)
                                        <div class="mb-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                                            {{ $enrollment->subject->code }}
                                                        </span>
                                                        <span class="text-xs text-gray-600">{{ $enrollment->subject->units }} units</span>
                                                        @if($enrollment->academicYear)
                                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                                                {{ $enrollment->academicYear->year_code }}
                                                            </span>
                                                        @endif
                                                        <span class="px-2 py-1 text-xs rounded-full
                                                            @if($enrollment->status == 'Enrolled') bg-yellow-100 text-yellow-800
                                                            @elseif($enrollment->status == 'Completed') bg-green-100 text-green-800
                                                            @elseif($enrollment->status == 'Failed') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800 @endif">
                                                            {{ $enrollment->status }}
                                                        </span>
                                                    </div>
                                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $enrollment->subject->name }}</h4>
                                                    
                                                    @if($enrollment->grade)
                                                        <div class="mt-2">
                                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded">
                                                                Grade: {{ $enrollment->grade }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                @if($enrollment->status == 'Enrolled')
                                                    <form method="POST" action="{{ route('students.subjects.drop', [$student, $enrollment]) }}" 
                                                          onsubmit="return confirm('Are you sure you want to drop this subject?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold rounded-lg transition-colors duration-200">
                                                            Drop
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Not Enrolled Yet</h3>
                                <p class="text-gray-500 text-sm">Enroll in subjects from the left panel</p>
                            </div>
                        @endforelse

                        @if($enrolledSubjects->count() > 0)
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-700">Total Units Enrolled:</span>
                                    <span class="text-2xl font-bold text-indigo-600">
                                        {{ $enrolledSubjects->where('status', 'Enrolled')->sum(function($e) { return $e->subject->units; }) }}
                                    </span>
                                </div>
                                @if($currentAcademicYear)
                                    <p class="text-xs text-gray-500 text-center mt-2">Current Academic Year: {{ $currentAcademicYear->year_code }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
