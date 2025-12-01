@extends('layouts.student')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">My Profile</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-gray-600 text-sm font-medium">Student ID</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->student_id }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-medium">Full Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-medium">Email</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->email }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-medium">Contact Number</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->contact_number }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-medium">Program</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->program->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-medium">Year Level</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->year_level }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-medium">Status</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->status }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm font-medium">Gender</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->gender }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-gray-600 text-sm font-medium">Address</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $student->address }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t">
                <a href="{{ route('student.dashboard') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
