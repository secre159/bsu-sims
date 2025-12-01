@extends('layouts.student')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">My Enrollments</h2>

        @if($enrollments->count() > 0)
            @foreach($enrollments as $period => $periodEnrollments)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $period }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Code</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Course Name</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Units</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($periodEnrollments as $enrollment)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $enrollment->subject->code }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $enrollment->subject->name }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ $enrollment->subject->units }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($enrollment->status === 'Enrolled') bg-blue-100 text-blue-800
                                            @elseif($enrollment->status === 'Completed') bg-green-100 text-green-800
                                            @elseif($enrollment->status === 'Dropped') bg-gray-100 text-gray-800
                                            @elseif($enrollment->status === 'Failed') bg-red-100 text-red-800
                                            @endif">
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
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600">No enrollments found</p>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('student.dashboard') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">← Back to Dashboard</a>
        </div>
    </div>
</div>
@endsection
