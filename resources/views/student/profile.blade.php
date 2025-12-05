@extends('layouts.student')

@section('title', 'My Profile')

@section('content')
<!-- Header with Photo -->
<div class="relative overflow-hidden shadow-md" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
    <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
    
    <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center gap-6">
            @if($student->photo_path)
                <img src="{{ asset('storage/' . $student->photo_path) }}" 
                     alt="{{ $student->first_name }}" 
                     class="w-24 h-24 rounded-full object-cover border-4 border-white/30">
            @else
                <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/30">
                    <span class="text-4xl font-bold text-white">{{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}</span>
                </div>
            @endif
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}{{ $student->suffix ? ' ' . $student->suffix : '' }}</h1>
                <p class="text-lg text-emerald-100 mt-1">{{ $student->student_id }}</p>
                <div class="flex items-center gap-4 mt-2">
                    <span class="px-3 py-1 bg-white/20 text-white text-sm font-medium rounded-full">{{ $student->status }}</span>
                    @if($student->gwa && $student->gwa > 0)
                        <span class="px-3 py-1 bg-white/20 text-white text-sm font-medium rounded-full">GWA: {{ number_format($student->gwa, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-100 min-h-screen">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
                <h2 class="text-lg font-semibold text-white">Personal Information</h2>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Last Name</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->last_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">First Name</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->first_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Middle Name</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->middle_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Suffix</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->suffix ?? 'N/A' }}</p>
                    </div>
                    @if($student->maiden_name)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Maiden Name</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->maiden_name }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Birthdate</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->birthdate ? $student->birthdate->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Place of Birth</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->place_of_birth ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Gender</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->gender ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Citizenship</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->citizenship ?? 'N/A' }}</p>
                    </div>
                    @if($student->ethnicity_tribal_affiliation)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Ethnicity/Tribal Affiliation</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->ethnicity_tribal_affiliation }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
                <h2 class="text-lg font-semibold text-white">Contact Information</h2>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Contact Number</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->contact_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->email ?? $student->email_address ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Home Address</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->home_address ?? $student->address ?? 'N/A' }}</p>
                    </div>
                    @if($student->address_while_studying)
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Address While Studying</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->address_while_studying }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Family Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
                <h2 class="text-lg font-semibold text-white">Family & Emergency Contact</h2>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Mother's Name</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->mother_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Mother's Contact Number</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->mother_contact_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Father's Name</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->father_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Father's Contact Number</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->father_contact_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Emergency Contact Person</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->emergency_contact_person ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Relationship</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->emergency_contact_relationship ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Emergency Contact Number</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->emergency_contact_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
                <h2 class="text-lg font-semibold text-white">Academic Information</h2>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Program</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->program->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Year Level</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->year_level }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->status }}</p>
                    </div>
                    @if($student->student_type)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Student Type</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->student_type }}</p>
                    </div>
                    @endif
                    @if($student->degree)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Degree</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->degree }}</p>
                    </div>
                    @endif
                    @if($student->major)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Major</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->major }}</p>
                    </div>
                    @endif
                    @if($student->section)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Section</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->section }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Attendance Type</label>
                        @php
                            $attendanceType = $student->attendance_type ?: ($student->is_irregular ? 'Irregular' : 'Regular');
                        @endphp
                        <p class="text-base font-semibold {{ $attendanceType === 'Irregular' ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $attendanceType }}
                        </p>
                    </div>
                    @if($student->curriculum_used)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Curriculum Used</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->curriculum_used }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Enrollment Date</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->enrollment_date ? $student->enrollment_date->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    @if($student->academicYear)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Academic Year Enrolled</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->academicYear->year_code }} - {{ $student->academicYear->semester }}</p>
                    </div>
                    @endif
                    @if($student->total_units_enrolled)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total Units Enrolled</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->total_units_enrolled }}</p>
                    </div>
                    @endif
                    @if($student->free_higher_education_benefit)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Free Higher Education Benefit</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->free_higher_education_benefit }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">GWA (Grade Weighted Average)</label>
                        @if($student->gwa && $student->gwa > 0)
                            <p class="text-base font-semibold text-indigo-600">{{ number_format($student->gwa, 2) }}</p>
                        @else
                            <p class="text-sm text-gray-500 italic">Not yet available - grades pending</p>
                        @endif
                    </div>
                    @if($student->academic_standing)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Academic Standing</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->academic_standing }}</p>
                    </div>
                    @endif
                    @if($student->notes)
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Notes</label>
                        <p class="text-base font-semibold text-gray-900">{{ $student->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-center">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
