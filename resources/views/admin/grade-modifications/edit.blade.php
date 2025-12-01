<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modify Grade') }}
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
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Student GPA</p>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ number_format($enrollment->student->gpa, 2) }} ({{ $enrollment->student->academic_standing }})
                            </p>
                        </div>
                    </div>

                    <!-- Grade Modification Form -->
                    <form method="POST" action="{{ route('admin.grade-modifications.update', $enrollment) }}" class="mt-8">
                        @csrf
                        @method('PATCH')

                        <!-- Grade Input -->
                        <div class="mb-6">
                            <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">
                                New Grade (0-100) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="grade" 
                                       name="grade" 
                                       step="0.01"
                                       min="0" 
                                       max="100" 
                                       value="{{ old('grade', $enrollment->grade) }}"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm 
                                              focus:ring-brand-medium focus:border-brand-medium @error('grade') border-red-500 @enderror"
                                       placeholder="Enter new grade">
                                @error('grade')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Current: <strong>{{ number_format($enrollment->grade, 2) }}</strong></p>
                        </div>

                        <!-- Reason for Modification -->
                        <div class="mb-6">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Modification <span class="text-red-500">*</span>
                            </label>
                            <textarea id="reason" 
                                      name="reason" 
                                      rows="4"
                                      class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm 
                                             focus:ring-brand-medium focus:border-brand-medium @error('reason') border-red-500 @enderror"
                                      placeholder="Explain the reason for this grade modification...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Recent History -->
                        @if($histories->count() > 0)
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-3">Recent Modifications ({{ $histories->count() }}):</p>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    @foreach($histories->take(5) as $history)
                                        <div class="text-sm border-l-2 border-brand-medium pl-3">
                                            <span class="text-gray-600">
                                                {{ $history->old_grade ? number_format($history->old_grade, 2) : 'N/A' }} 
                                                <span class="text-gray-400">→</span> 
                                                {{ number_format($history->new_grade, 2) }}
                                            </span>
                                            <span class="text-xs text-gray-500 ml-2">by {{ $history->user->name }}</span>
                                            <div class="text-xs text-gray-400 mt-1">{{ $history->created_at->format('M d, Y H:i') }}</div>
                                            <div class="text-xs text-gray-600 mt-1">{{ $history->reason }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($histories->count() > 5)
                                    <p class="text-xs text-gray-500 mt-2">... and {{ $histories->count() - 5 }} more</p>
                                @endif
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.grade-modifications.history', $enrollment) }}" 
                                   class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                    View Full History
                                </a>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.grade-modifications.index') }}" 
                                   class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                    Update Grade
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
