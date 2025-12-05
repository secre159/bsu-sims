@extends('layouts.student')

@section('content')
<!-- Header -->
<div class="relative overflow-hidden shadow-md" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
    <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
    
    <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-semibold text-white">My Enrollments</h1>
        <p class="text-sm text-emerald-100 mt-1">View your enrolled subjects by academic period</p>
    </div>
</div>

<div class="bg-gray-100 min-h-screen">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if($enrollments->count() > 0)
            @foreach($enrollments as $period => $periodEnrollments)
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="relative overflow-hidden px-6 py-4" style="background: linear-gradient(135deg, #047857, #0f766e, #115e59);">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white opacity-10"></div>
                        <div class="absolute -left-6 -bottom-6 h-20 w-20 rounded-full bg-white opacity-10"></div>
                        <h3 class="relative z-10 text-lg font-semibold text-white">{{ $period }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($periodEnrollments as $enrollment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $enrollment->subject->code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $enrollment->subject->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">{{ $enrollment->subject->units }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($enrollment->status === 'Enrolled') bg-blue-100 text-blue-800
                                            @elseif($enrollment->status === 'Completed') bg-emerald-100 text-emerald-800
                                            @elseif($enrollment->status === 'Failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $enrollment->status }}
                                        </span>
                                    </td>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-4 text-gray-500 text-lg">No enrollments found</p>
            </div>
        @endif

        <div class="flex justify-center mt-8">
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
