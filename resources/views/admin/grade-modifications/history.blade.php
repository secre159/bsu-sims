<x-app-layout>
    <x-slot name="title">Grade History</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grade Modification History') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Enrollment Information -->
                    <div class="mb-8 grid grid-cols-2 gap-6">
                        <div class="border-l-4 border-brand-deep pl-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Student</p>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ $enrollment->student->student_id }}: {{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}
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
                            <p class="text-lg font-semibold text-gray-800">{{ number_format($enrollment->grade, 2) }}</p>
                        </div>
                        <div class="border-l-4 border-purple-600 pl-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Changes</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $histories->count() }}</p>
                        </div>
                    </div>

                    <!-- Modification Timeline -->
                    @if($histories->count() > 0)
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Complete Audit Trail</h3>
                            
                            @foreach($histories as $index => $history)
                                <div class="relative">
                                    <!-- Timeline connector -->
                                    @if(!$loop->last)
                                        <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-gray-200"></div>
                                    @endif
                                    
                                    <!-- Timeline item -->
                                    <div class="flex gap-4">
                                        <!-- Timeline dot -->
                                        <div class="flex-shrink-0 relative z-10 flex items-center justify-center">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-brand-deep to-brand-medium flex items-center justify-center text-white font-semibold text-sm">
                                                {{ $histories->count() - $index }}
                                            </div>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="flex-grow pt-1 pb-4">
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">
                                                            Grade Change: 
                                                            @if($history->old_grade)
                                                                <span class="text-red-600 font-semibold">{{ number_format($history->old_grade, 2) }}</span>
                                                            @else
                                                                <span class="text-gray-400 italic">Initial Entry</span>
                                                            @endif
                                                            <span class="text-gray-400">→</span>
                                                            <span class="text-green-600 font-semibold">{{ number_format($history->new_grade, 2) }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-xs font-medium text-gray-500">
                                                            {{ $history->created_at->format('M d, Y') }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $history->created_at->format('H:i A') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3 space-y-2 pt-3 border-t border-gray-200">
                                                    <div class="text-xs">
                                                        <span class="font-medium text-gray-700">Changed by:</span>
                                                        <span class="text-gray-600">{{ $history->user->name }}</span>
                                                        @if($history->user->role)
                                                            <span class="text-gray-500">({{ ucfirst($history->user->role) }})</span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="text-xs">
                                                        <span class="font-medium text-gray-700">Reason:</span>
                                                        <p class="text-gray-600 mt-1 whitespace-pre-wrap">{{ $history->reason }}</p>
                                                    </div>
                                                </div>
                                            </div>
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
                            <p class="mt-2 text-gray-500">No modification history yet.</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t space-y-4">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.grade-modifications.index') }}" 
                               class="text-gray-700 hover:text-gray-900 font-medium">
                                ← Back to Modifications
                            </a>
                            <a href="{{ route('admin.grade-modifications.edit', $enrollment) }}" 
                               class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                Modify Grade
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
