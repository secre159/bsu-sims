<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grade History') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Student and Enrollment Information -->
                    <div class="mb-8 grid grid-cols-2 gap-6">
                        <div class="border-l-4 border-brand-deep pl-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Student ID</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $enrollment->student->student_id }}</p>
                        </div>
                        <div class="border-l-4 border-brand-medium pl-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Student Name</p>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}
                            </p>
                        </div>
                        <div class="border-l-4 border-green-600 pl-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Subject</p>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ $enrollment->subject->code }}: {{ $enrollment->subject->name }}
                            </p>
                        </div>
                        <div class="border-l-4 border-blue-600 pl-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Current Grade</p>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ $enrollment->grade ? number_format($enrollment->grade, 2) : 'Not set' }}
                            </p>
                        </div>
                    </div>

                    <!-- Grade History Timeline -->
                    @if($histories->count() > 0)
                        <div class="space-y-4">
                            @foreach($histories->sortByDesc('created_at') as $history)
                                <div class="border-l-4 border-brand-medium pl-4 py-3 bg-gray-50 rounded">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">
                                                Grade Change: 
                                                @if($history->old_grade)
                                                    <span class="text-red-600 font-semibold">{{ number_format($history->old_grade, 2) }}</span>
                                                @else
                                                    <span class="text-gray-400 italic">No grade</span>
                                                @endif
                                                <span class="text-gray-400">→</span>
                                                <span class="text-green-600 font-semibold">{{ number_format($history->new_grade, 2) }}</span>
                                            </p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                <span class="font-medium">Reason:</span> {{ $history->reason }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span class="font-medium">Changed by:</span> {{ $history->user->name }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">
                                                {{ $history->created_at->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $history->created_at->format('h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500">No grade history yet.</p>
                        </div>
                    @endif

                    <!-- Action Button -->
                    <div class="mt-8 pt-6 border-t">
                        <a href="{{ route('chairperson.grades.index') }}" 
                           class="text-gray-700 hover:text-gray-900 font-medium">
                            ← Back to Grades
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
