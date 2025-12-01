<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('students.subjects', $student) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Subjects
                </a>
                <a href="{{ route('students.id-card', $student) }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                    Generate ID Card
                </a>
                <a href="{{ route('students.edit', $student ?? 1) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Edit
                </a>
                <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-semibold">Student Information</h3>
                        @if($student->photo_path)
                            <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Student Photo" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="col-span-2 border-b pb-4">
                            <h4 class="font-semibold text-gray-700 mb-3">Personal Information</h4>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Student ID</p>
                            <p class="font-medium">{{ $student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Full Name</p>
                            <p class="font-medium">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ? substr($student->middle_name, 0, 1) . '.' : '' }} {{ $student->suffix }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Birthdate</p>
                            <p class="font-medium">{{ $student->birthdate ? $student->birthdate->format('F d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Gender</p>
                            <p class="font-medium">{{ $student->gender }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Contact Number</p>
                            <p class="font-medium">{{ $student->contact_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">{{ $student->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600">Address</p>
                            <p class="font-medium">{{ $student->address ?? 'N/A' }}</p>
                        </div>

                        <!-- Academic Information -->
                        <div class="col-span-2 border-b pb-4 mt-4">
                            <h4 class="font-semibold text-gray-700 mb-3">Academic Information</h4>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Program</p>
                            <p class="font-medium">{{ $student->program->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Year Level</p>
                            <p class="font-medium">{{ $student->year_level }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-medium">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($student->status == 'Active') bg-green-100 text-green-800
                                    @elseif($student->status == 'Graduated') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $student->status }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Enrollment Date</p>
                            <p class="font-medium">{{ $student->enrollment_date ? $student->enrollment_date->format('F d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Academic Year</p>
                            <p class="font-medium">{{ $student->academicYear->year_code ?? 'N/A' }}</p>
                        </div>

                        @if($student->notes)
                            <div class="col-span-2 mt-4">
                                <p class="text-sm text-gray-600">Notes</p>
                                <p class="font-medium">{{ $student->notes }}</p>
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
