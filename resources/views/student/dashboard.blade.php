@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
<!-- Header with emerald gradient matching navigation -->
<div class="relative overflow-hidden shadow-md" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
    <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
    
    <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center ring-4 ring-white/30">
                    <span class="text-3xl font-bold text-white">{{ strtoupper(substr($student->first_name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $student->first_name }} {{ $student->last_name }}</h1>
                    <p class="text-sm text-emerald-100 mt-1 font-medium">{{ $student->student_id }} • {{ $student->program->name ?? 'N/A' }}</p>
                    <p class="text-xs text-emerald-200 mt-0.5">{{ $student->year_level }} Year • {{ $student->status }}</p>
                </div>
            </div>
            <div class="text-right bg-white/10 backdrop-blur-sm rounded-xl px-6 py-4 border border-white/20">
                <p class="text-xs text-emerald-200 uppercase tracking-wide font-semibold">Cumulative GWA</p>
                @if($overallGwa && $overallGwa > 0)
                    <p class="text-4xl font-bold text-white mt-1">{{ number_format($overallGwa, 2) }}</p>
                    <p class="text-xs text-emerald-200 mt-1">All Semesters</p>
                @else
                    <p class="text-sm text-white/80 italic mt-2">Not yet available</p>
                    <p class="text-xs text-emerald-200 mt-1">Complete at least one semester</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-100 min-h-screen">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Current Semester</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $enrollmentCount }}</p>
                        <p class="text-sm text-gray-600 mt-1">Subjects</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Units Enrolled</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUnits }}</p>
                        <p class="text-sm text-gray-600 mt-1">This Semester</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-emerald-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Completed</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $completedUnits }}</p>
                        <p class="text-sm text-gray-600 mt-1">Units</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-amber-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Current Semester</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $currentSemesterGwa ? number_format($currentSemesterGwa, 2) : '—' }}</p>
                        <p class="text-sm text-gray-600 mt-1">This Term GWA</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>


        <!-- Enrollments by Year Level -->
        @if($enrollmentsByYearLevel->isNotEmpty())
            @foreach($enrollmentsByYearLevel as $yearLevel => $semesters)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8 border-2 border-gray-200">
                <!-- Year Level Header -->
                <div class="relative overflow-hidden px-8 py-5" style="background: linear-gradient(135deg, #1e40af, #1e3a8a, #312e81);">
                    <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white opacity-10"></div>
                    <div class="absolute -left-10 -bottom-10 h-28 w-28 rounded-full bg-white opacity-10"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $yearLevel }}</h2>
                            <p class="text-sm text-blue-200 mt-1">Academic Progress</p>
                        </div>
                        @php
                            $totalSubjects = $semesters->flatten()->count();
                            $completedSubjects = $semesters->flatten()->where('status', 'Completed')->count();
                        @endphp
                        <div class="text-right">
                            <p class="text-3xl font-bold text-white">{{ $totalSubjects }}</p>
                            <p class="text-sm text-blue-200">{{ Str::plural('Subject', $totalSubjects) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Semesters within this year level -->
                <div class="p-6 space-y-6">
                    @foreach($semesters as $semesterLabel => $enrollments)
                    <div class="bg-white rounded-xl overflow-hidden shadow-md border-2 border-gray-200 hover:border-emerald-400 transition-all duration-200">
                        <div class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                <h3 class="text-base font-semibold text-white">{{ $semesterLabel }}</h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-white/20 rounded-full text-xs text-white font-medium">{{ $enrollments->count() }} {{ Str::plural('subject', $enrollments->count()) }}</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50">
                            <div class="overflow-x-auto">
                    <table class="w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Name</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($enrollments as $enrollment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $enrollment->subject->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $enrollment->subject->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">{{ $enrollment->subject->units }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($enrollment->grade)
                                        @if(is_numeric($enrollment->grade))
                                            <span class="px-3 py-1 inline-flex text-sm font-bold rounded-lg
                                                @if($enrollment->grade <= 1.75) bg-emerald-100 text-emerald-800
                                                @elseif($enrollment->grade <= 2.5) bg-blue-100 text-blue-800
                                                @elseif($enrollment->grade < 4.0) bg-amber-100 text-amber-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ number_format($enrollment->grade, 2) }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-sm font-bold rounded-lg bg-gray-100 text-gray-800">
                                                {{ $enrollment->grade }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $remark = '';
                                        if ($enrollment->status === 'Dropped') {
                                            $remark = 'Dropped';
                                            $remarkColor = 'text-gray-600';
                                        } elseif ($enrollment->status === 'Failed' || ($enrollment->grade && is_numeric($enrollment->grade) && $enrollment->grade >= 4.0)) {
                                            $remark = 'Failed';
                                            $remarkColor = 'text-red-600 font-semibold';
                                        } elseif ($enrollment->grade === 'INC') {
                                            $remark = 'Incomplete';
                                            $remarkColor = 'text-amber-600 font-semibold';
                                        } elseif ($enrollment->grade === 'IP') {
                                            $remark = 'In Progress';
                                            $remarkColor = 'text-blue-600';
                                        } elseif ($enrollment->status === 'Completed' && $enrollment->grade && is_numeric($enrollment->grade)) {
                                            $remark = 'Passed';
                                            $remarkColor = 'text-emerald-600 font-semibold';
                                        } elseif ($enrollment->status === 'Enrolled') {
                                            $remark = 'Ongoing';
                                            $remarkColor = 'text-blue-600';
                                        } else {
                                            $remark = '—';
                                            $remarkColor = 'text-gray-400';
                                        }
                                    @endphp
                                    <span class="{{ $remarkColor }}">
                                        {{ $remark }}
                                    </span>
                                    @if($enrollment->remarks)
                                        <span class="text-xs text-gray-500 block mt-1">{{ $enrollment->remarks }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
            </div>
            @endforeach
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative overflow-hidden px-6 py-4" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
                    <h3 class="text-lg font-semibold text-white">Enrollments</h3>
                </div>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2 text-sm font-medium text-gray-500">No enrollments found</p>
                </div>
            </div>
        @endif

        <!-- Grade Legend -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Grade Legend</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-sm font-bold rounded-lg bg-emerald-100 text-emerald-800">1.0 - 1.75</span>
                    <span class="text-sm text-gray-600">Excellent</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-sm font-bold rounded-lg bg-blue-100 text-blue-800">2.0 - 2.5</span>
                    <span class="text-sm text-gray-600">Good</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-sm font-bold rounded-lg bg-amber-100 text-amber-800">2.75 - 3.0</span>
                    <span class="text-sm text-gray-600">Fair</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-sm font-bold rounded-lg bg-red-100 text-red-800">4.0 - 5.0</span>
                    <span class="text-sm text-gray-600">Failed</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
