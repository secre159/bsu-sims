<x-app-layout>
    <x-slot name="title">Irregular Students Report</x-slot>
    <div class="py-8">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Irregular Students</h1>
                        <p class="mt-1 text-sm text-gray-600">Students with failed, incomplete (INC), or in-progress (IP) grades in {{ Auth::user()->department->name }}</p>
                    </div>
                    <a href="{{ route('chairperson.reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Reports
                    </a>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-amber-900">{{ $students->count() }} {{ Str::plural('Student', $students->count()) }} Need Attention</h3>
                        <p class="text-sm text-amber-700">These students have grades that require intervention or follow-up</p>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="space-y-4">
                @forelse($students as $student)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Student Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg mr-4">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ? substr($student->middle_name, 0, 1) . '.' : '' }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $student->student_id }} • {{ $student->program->code ?? 'N/A' }} • {{ $student->year_level }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($student->status == 'Active')
                                        <span class="px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">Active</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">{{ $student->status }}</span>
                                    @endif
                                    @if($student->gwa)
                                        <span class="px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full">GWA: {{ number_format($student->gwa, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Problem Subjects -->
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Subjects Needing Attention</h4>
                            <div class="space-y-3">
                                @foreach($student->enrollments as $enrollment)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <span class="font-semibold text-gray-900">{{ $enrollment->subject->code }}</span>
                                                <span class="text-gray-600">{{ $enrollment->subject->name }}</span>
                                                @if($enrollment->academicYear)
                                                    <span class="text-xs text-gray-500">
                                                        ({{ $enrollment->academicYear->year_code }} - {{ $enrollment->academicYear->semester }})
                                                    </span>
                                                @endif
                                            </div>
                                            @if($enrollment->remarks)
                                                <p class="text-sm text-gray-600 mt-1">{{ $enrollment->remarks }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <!-- Grade Badge -->
                                            @if($enrollment->grade == '5.00' || $enrollment->status == 'Failed')
                                                <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-800 rounded-full">
                                                    Failed ({{ $enrollment->grade ?? '5.00' }})
                                                </span>
                                            @elseif($enrollment->grade == 'INC')
                                                <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full">
                                                    Incomplete
                                                </span>
                                            @elseif($enrollment->grade == 'IP')
                                                <span class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded-full">
                                                    In Progress
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-800 rounded-full">
                                                    {{ $enrollment->grade ?? $enrollment->status }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Contact Info -->
                            @if($student->contact_number || $student->email_address)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Contact Information</h4>
                                    <div class="flex gap-6 text-sm text-gray-600">
                                        @if($student->contact_number)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $student->contact_number }}
                                            </div>
                                        @endif
                                        @if($student->email_address)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $student->email_address }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-emerald-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Great News!</h3>
                        <p class="text-gray-600 mb-1">No irregular students found in your department</p>
                        <p class="text-sm text-gray-500">All students are progressing well with their subjects</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
