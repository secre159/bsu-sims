<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Enter Grade') }}
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
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Academic Year</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $enrollment->academicYear->year_code }} ({{ $enrollment->academicYear->semester }})</p>
                        </div>
                    </div>

                    <!-- Grade Entry Form -->
                    <form method="POST" action="{{ route('chairperson.grades.update', $enrollment) }}" class="mt-8">
                        @csrf
                        @method('PATCH')

                        <!-- Grade Conversion Reference -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm font-medium text-blue-800 mb-2">Philippine Grading Scale:</p>
                            <div class="grid grid-cols-5 gap-2 text-xs text-blue-700">
                                <span>96-100 = 1.00</span>
                                <span>93-95 = 1.25</span>
                                <span>90-92 = 1.50</span>
                                <span>87-89 = 1.75</span>
                                <span>84-86 = 2.00</span>
                                <span>81-83 = 2.25</span>
                                <span>78-80 = 2.50</span>
                                <span>75-77 = 2.75</span>
                                <span>74 = 3.00</span>
                                <span class="text-red-600">Below 74 = 5.00</span>
                            </div>
                        </div>

                        <!-- Grade Input -->
                        <div class="mb-6">
                            <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">
                                Grade (1.00 - 5.00) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="grade" 
                                        name="grade"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm 
                                               focus:ring-brand-medium focus:border-brand-medium @error('grade') border-red-500 @enderror">
                                    <option value="">-- Select Grade --</option>
                                    <optgroup label="Passing Grades">
                                        <option value="1.00" {{ old('grade', $enrollment->grade) == '1.00' ? 'selected' : '' }}>1.00 - Excellent (96-100)</option>
                                        <option value="1.25" {{ old('grade', $enrollment->grade) == '1.25' ? 'selected' : '' }}>1.25 - Excellent (93-95)</option>
                                        <option value="1.50" {{ old('grade', $enrollment->grade) == '1.50' ? 'selected' : '' }}>1.50 - Very Good (90-92)</option>
                                        <option value="1.75" {{ old('grade', $enrollment->grade) == '1.75' ? 'selected' : '' }}>1.75 - Very Good (87-89)</option>
                                        <option value="2.00" {{ old('grade', $enrollment->grade) == '2.00' ? 'selected' : '' }}>2.00 - Good (84-86)</option>
                                        <option value="2.25" {{ old('grade', $enrollment->grade) == '2.25' ? 'selected' : '' }}>2.25 - Good (81-83)</option>
                                        <option value="2.50" {{ old('grade', $enrollment->grade) == '2.50' ? 'selected' : '' }}>2.50 - Satisfactory (78-80)</option>
                                        <option value="2.75" {{ old('grade', $enrollment->grade) == '2.75' ? 'selected' : '' }}>2.75 - Satisfactory (75-77)</option>
                                        <option value="3.00" {{ old('grade', $enrollment->grade) == '3.00' ? 'selected' : '' }}>3.00 - Passing (74)</option>
                                    </optgroup>
                                    <optgroup label="Special Grades">
                                        <option value="IP" {{ old('grade', $enrollment->grade) == 'IP' ? 'selected' : '' }}>IP - In Progress</option>
                                        <option value="INC" {{ old('grade', $enrollment->grade) == 'INC' ? 'selected' : '' }}>INC - Incomplete</option>
                                    </optgroup>
                                    <optgroup label="Failing Grade">
                                        <option value="5.00" {{ old('grade', $enrollment->grade) == '5.00' ? 'selected' : '' }}>5.00 - Failed (Below 74)</option>
                                    </optgroup>
                                </select>
                                @error('grade')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Current Grade: <strong>{{ $enrollment->grade ? (is_numeric($enrollment->grade) ? number_format($enrollment->grade, 2) : $enrollment->grade) : 'Not set' }}</strong></p>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                Remarks (Optional)
                            </label>
                            <textarea id="remarks" 
                                      name="remarks" 
                                      rows="3"
                                      class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm 
                                             focus:ring-brand-medium focus:border-brand-medium @error('remarks') border-red-500 @enderror"
                                      placeholder="Add any comments or notes about this grade">{{ old('remarks', $enrollment->remarks) }}</textarea>
                            @error('remarks')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Reason for Change -->
                        <div class="mb-6">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Entry/Modification <span class="text-red-500">*</span>
                            </label>
                            <select id="reason" 
                                    name="reason"
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm 
                                           focus:ring-brand-medium focus:border-brand-medium @error('reason') border-red-500 @enderror">
                                <option value="">-- Select a reason --</option>
                                <option value="Initial Entry" {{ old('reason') == 'Initial Entry' ? 'selected' : '' }}>Initial Grade Entry</option>
                                <option value="Correction" {{ old('reason') == 'Correction' ? 'selected' : '' }}>Grade Correction</option>
                                <option value="Re-evaluation" {{ old('reason') == 'Re-evaluation' ? 'selected' : '' }}>Re-evaluation</option>
                                <option value="Grade Appeal" {{ old('reason') == 'Grade Appeal' ? 'selected' : '' }}>Grade Appeal Resolution</option>
                                <option value="Other" {{ old('reason') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('reason')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Grade History -->
                        @if($enrollment->gradeHistories->count() > 0)
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-3">Previous Changes:</p>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    @foreach($enrollment->gradeHistories->sortByDesc('created_at')->take(5) as $history)
                                        <div class="text-sm">
                                            <span class="text-gray-600">
                                                {{ $history->old_grade ? (is_numeric($history->old_grade) ? number_format($history->old_grade, 2) : $history->old_grade) : 'N/A' }} 
                                                <span class="text-gray-400">→</span> 
                                                {{ is_numeric($history->new_grade) ? number_format($history->new_grade, 2) : $history->new_grade }}
                                            </span>
                                            <span class="text-xs text-gray-500 ml-2">({{ $history->reason }})</span>
                                            <span class="text-xs text-gray-400 ml-2">{{ $history->created_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            <a href="{{ route('chairperson.grades.index') }}" 
                               class="text-gray-700 hover:text-gray-900 font-medium">
                                ← Back to Grades
                            </a>
                            <div class="flex gap-3">
                                <a href="{{ route('chairperson.grades.index') }}" 
                                   class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                    Save Grade
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
