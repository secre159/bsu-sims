@extends('layouts.student')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-brand-deep to-brand-medium bg-clip-text text-transparent mb-2">Welcome, {{ $student->first_name }}! 👋</h2>
            <p class="text-gray-600">Here's your academic dashboard for the current semester.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-brand-deep">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Student ID</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $student->student_id }}</p>
                    </div>
                    <div class="p-3 bg-brand-pale rounded-full">
                        <svg class="w-6 h-6 text-brand-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>


            <!-- Units Card -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-brand-medium">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Current Units</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUnits }}</p>
                    </div>
                    <div class="p-3 bg-brand-pale rounded-full">
                        <svg class="w-6 h-6 text-brand-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-brand-light">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Status</p>
                        <p class="text-lg font-bold">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold @if($student->status === 'Active') bg-brand-pale text-brand-primary @elseif($student->status === 'Graduated') bg-brand-light text-brand-deep @else bg-gray-100 text-gray-800 @endif">
                                {{ $student->status }}
                            </span>
                        </p>
                    </div>
                    <div class="p-3 bg-brand-pale rounded-full">
                        <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-8 border-t-4 border-brand-deep">
            <h3 class="text-lg font-bold text-brand-deep mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Full Name</p>
                    <p class="font-semibold text-gray-900">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Program</p>
                    <p class="font-semibold text-gray-900">{{ $student->program->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Year Level</p>
                    <p class="font-semibold text-gray-900">{{ $student->year_level }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="font-semibold text-gray-900">{{ $student->email }}</p>
                </div>
            </div>
        </div>

        <!-- Current Enrollments -->
        <div class="bg-white rounded-lg shadow p-6 mb-8 border-t-4 border-brand-medium">
            <h3 class="text-lg font-bold text-brand-deep mb-4">Current Enrollments ({{ $currentEnrollments->count() }})</h3>
            @if($currentEnrollments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Code</th>
                                <th class="px-4 py-3 text-left font-semibold">Course Name</th>
                                <th class="px-4 py-3 text-center font-semibold">Units</th>
                                <th class="px-4 py-3 text-center font-semibold">Status</th>
                                <th class="px-4 py-3 text-center font-semibold">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentEnrollments as $enrollment)
                            <tr class="border-b hover:bg-brand-pale transition-colors">
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $enrollment->subject->code }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $enrollment->subject->name }}</td>
                                <td class="px-4 py-3 text-center text-gray-700">{{ $enrollment->subject->units }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold @if($enrollment->status === 'Enrolled') bg-brand-pale text-brand-primary @else bg-brand-light text-brand-deep @endif">
                                        {{ $enrollment->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold {{ $enrollment->grade ? 'text-gray-900' : 'text-gray-500' }}">
                                    {{ $enrollment->grade ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center py-8">No current enrollments</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('student.profile') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center border-t-4 border-brand-deep">
                <div class="p-3 bg-brand-pale rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-brand-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">View Profile</h4>
                <p class="text-sm text-gray-600">Check your personal details</p>
            </a>

            <a href="{{ route('student.enrollments') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center border-t-4 border-brand-medium">
                <div class="p-3 bg-brand-pale rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-brand-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">View Enrollments</h4>
                <p class="text-sm text-gray-600">See all course enrollments</p>
            </a>

            <form method="POST" action="{{ route('student.logout') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center cursor-pointer border-t-4 border-status-red">
                @csrf
                <button type="submit" class="w-full h-full flex flex-col items-center justify-center">
                    <div class="p-3 bg-red-100 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-status-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Logout</h4>
                    <p class="text-sm text-gray-600">Sign out of your account</p>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
