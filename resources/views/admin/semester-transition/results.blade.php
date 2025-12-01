<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transition Completed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Success Message -->
                    @if($results['success'])
                        <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-sm font-semibold text-green-800 mb-2">
                                ✓ Semester Transition Completed Successfully!
                            </p>
                            <p class="text-sm text-green-700">
                                {{ $results['message'] }}
                            </p>
                        </div>
                    @else
                        <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200">
                            <p class="text-sm font-semibold text-red-800 mb-2">
                                ✗ Transition Completed with Errors
                            </p>
                            <p class="text-sm text-red-700 mb-2">
                                {{ $results['message'] }}
                            </p>
                            @if(!empty($results['errors']))
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($results['errors'] as $error)
                                        <li class="text-red-700 text-sm">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <!-- Transition Summary -->
                    <div class="mb-6 space-y-4">
                        <h3 class="text-lg font-semibold">Transition Summary</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Archived Year</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $currentYear->year }}</p>
                                <p class="text-xs text-gray-500 mt-2">Status: Read-only</p>
                            </div>
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-xs text-green-600 uppercase tracking-wide">Active Year</p>
                                <p class="text-2xl font-bold text-green-800 mt-1">{{ $nextYear->year }}</p>
                                <p class="text-xs text-green-500 mt-2">Status: Active</p>
                            </div>
                        </div>
                    </div>

                    <!-- Processing Results -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Processing Results</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-xs text-blue-600 uppercase tracking-wide">Students Processed</p>
                                <p class="text-3xl font-bold text-blue-800 mt-1">{{ $results['students_processed'] }}</p>
                            </div>
                            <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <p class="text-xs text-purple-600 uppercase tracking-wide">Enrollments Created</p>
                                <p class="text-3xl font-bold text-purple-800 mt-1">{{ $results['enrollments_created'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- What Happened -->
                    <div class="mb-6 p-4 bg-info-50 rounded-lg border border-info-200">
                        <p class="text-sm font-semibold text-info-800 mb-3">What was completed:</p>
                        <ul class="list-disc list-inside space-y-2 text-sm text-info-700">
                            <li>Academic standing calculated for all {{ $results['students_processed'] }} students</li>
                            <li>Students categorized as Promoted, Irregular, Retained, or Probation</li>
                            <li>New enrollments created for {{ $nextYear->year }}</li>
                            <li>Failed subjects identified for retake enrollment</li>
                            <li>Academic standing logs created for audit trail</li>
                            <li>{{ $currentYear->year }} marked as archived (read-only)</li>
                            <li>{{ $nextYear->year }} set as active academic year</li>
                        </ul>
                    </div>

                    <!-- Next Steps -->
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-sm font-semibold text-yellow-800 mb-3">Next Steps:</p>
                        <ul class="list-disc list-inside space-y-2 text-sm text-yellow-700">
                            <li>Review student academic standing in student profiles</li>
                            <li>Identify and contact students marked as Probation for intervention</li>
                            <li>Verify that irregular students are properly enrolled in retake courses</li>
                            <li>Communicate academic standing to students</li>
                            <li>Begin grade entry process for {{ $nextYear->year }}</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t">
                        <a href="{{ route('dashboard') }}" 
                           class="text-gray-700 hover:text-gray-900 font-medium">
                            Back to Dashboard
                        </a>
                        <div class="flex gap-3">
                            <a href="{{ route('students.index') }}" 
                               class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                                View Students
                            </a>
                            <a href="{{ route('semester-transition.index') }}" 
                               class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                New Transition
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
