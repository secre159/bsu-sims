<x-app-layout>
    <x-slot name="title">Student Details</x-slot>
    <div class="py-8 bg-gray-50">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <a href="{{ route('students.index') }}" 
                               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back to Students
                            </a>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">Student Profile</h1>
                        <p class="mt-1 text-sm text-gray-600">Complete student information and academic records</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('students.subjects', $student) }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Manage Subjects
                        </a>
                        <a href="{{ route('students.edit', $student ?? 1) }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto">
            <!-- Student Profile Header Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <!-- Header with Photo -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            @if($student->photo_path)
                                <img src="{{ asset('storage/' . $student->photo_path) }}" 
                                     alt="{{ $student->first_name }} {{ $student->last_name }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-lg">
                                    <span class="text-4xl font-bold text-indigo-600">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h2 class="text-2xl font-bold text-white mb-1">
                                    {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ? substr($student->middle_name, 0, 1) . '.' : '' }} {{ $student->suffix }}
                                </h2>
                                <div class="flex items-center gap-3 text-white/90">
                                    <span class="text-lg font-medium">{{ $student->student_id }}</span>
                                    <span class="text-white/60">•</span>
                                    @if($student->status == 'Active')
                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-500 text-white text-sm font-semibold rounded-full">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Active
                                        </span>
                                    @elseif($student->status == 'Graduated')
                                        <span class="inline-flex items-center px-3 py-1 bg-purple-400 text-white text-sm font-semibold rounded-full">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
                                            </svg>
                                            Graduated
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-white/20 text-white text-sm font-semibold rounded-full">
                                            {{ $student->status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($student->gpa || $student->academic_standing)
                        <div class="text-right">
                            @if($student->gpa)
                            <div class="text-white/80 text-sm">GPA</div>
                            <div class="text-2xl font-bold text-white">{{ number_format($student->gpa, 2) }}</div>
                            @endif
                            @if($student->academic_standing)
                            <div class="mt-2">
                                <span class="px-3 py-1 bg-white/20 text-white text-xs font-semibold rounded-full">
                                    {{ ucfirst($student->academic_standing) }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="p-8">
                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Personal Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Birthdate</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->birthdate ? $student->birthdate->format('F d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Gender</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->gender }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Contact Number</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->contact_number ?? 'N/A' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->email_address ?? 'N/A' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg md:col-span-2">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Address</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-emerald-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Academic Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Program</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->program->name ?? 'N/A' }}</p>
                                @if($student->program)
                                    <p class="text-sm text-gray-600 mt-1">{{ $student->program->code }}</p>
                                @endif
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Year Level</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->year_level }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Enrollment Date</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->enrollment_date ? $student->enrollment_date->format('F d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Academic Year</p>
                                <p class="text-base font-semibold text-gray-900">{{ $student->academicYear->year_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        @if($student->notes)
                            <div class="mt-6 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs font-medium text-amber-800 uppercase tracking-wider mb-1">Notes</p>
                                        <p class="text-sm text-amber-900">{{ $student->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Enrolled Subjects Section -->
            @if($enrolledSubjects->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">Enrolled Subjects</h3>
                            <a href="{{ route('students.subjects', $student) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                                Manage Subjects →
                            </a>
                        </div>

                        @foreach($enrolledSubjects as $yearLevel => $enrollments)
                            <!-- Year Level Header -->
                            <div class="mb-4">
                                <div class="flex items-center mb-3">
                                    <div class="flex-1 border-t border-gray-300"></div>
                                    <span class="px-3 text-sm font-bold text-gray-600 uppercase">{{ $yearLevel }}</span>
                                    <div class="flex-1 border-t border-gray-300"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($enrollments->groupBy('subject.semester') as $semester => $semesterEnrollments)
                                        <div>
                                            <div class="text-xs font-semibold text-gray-500 mb-2">{{ $semester }}</div>
                                            @foreach($semesterEnrollments as $enrollment)
                                                @if($enrollment->subject)
                                                <div class="mb-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded">
                                                                    {{ $enrollment->subject->code }}
                                                                </span>
                                                                <span class="text-xs text-gray-600">{{ $enrollment->subject->units }} units</span>
                                                                @if($enrollment->academicYear)
                                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                                        {{ $enrollment->academicYear->year_code }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <h4 class="font-medium text-sm text-gray-900">{{ $enrollment->subject->name }}</h4>
                                                            
                                                            <div class="flex items-center gap-2 mt-1">
                                                                <span class="px-2 py-1 text-xs rounded-full
                                                                    @if($enrollment->status == 'Enrolled') bg-yellow-100 text-yellow-800
                                                                    @elseif($enrollment->status == 'Completed') bg-green-100 text-green-800
                                                                    @elseif($enrollment->status == 'Failed') bg-red-100 text-red-800
                                                                    @else bg-gray-100 text-gray-800 @endif">
                                                                    {{ $enrollment->status }}
                                                                </span>
                                                                @if($enrollment->grade)
                                                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded">
                                                                        Grade: {{ $enrollment->grade }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Summary -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-sm text-gray-600">Total Subjects</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $enrolledSubjects->flatten()->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Units</p>
                                    <p class="text-2xl font-bold text-indigo-600">{{ $enrolledSubjects->flatten()->sum('subject.units') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Currently Enrolled</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $enrolledSubjects->flatten()->where('status', 'Enrolled')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <p class="text-sm font-medium mb-2">No Enrolled Subjects</p>
                        <p class="text-xs mb-4">This student hasn't been enrolled in any subjects yet.</p>
                        <a href="{{ route('students.subjects', $student) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                            Enroll in Subjects →
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
