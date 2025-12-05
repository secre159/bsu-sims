<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('chairperson.grades.index', ['view' => 'subjects']) }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← Back to Subjects
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-1">
                    {{ $subject->code }} - {{ $subject->name }}
                </h2>
            </div>
            <div class="text-right text-sm text-gray-600">
                <div><strong>Units:</strong> {{ $subject->units }}</div>
                <div><strong>Students:</strong> {{ $enrollments->count() }}</div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Bulk Grade Entry</h3>
                    <p class="text-sm text-gray-500 mb-4">Enter grades for all students enrolled in this subject. Leave blank to skip.</p>
                    
                    <!-- Grade Conversion Reference -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-blue-800 mb-2">Philippine Grading Scale Reference:</p>
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
                            <span class="text-red-600">Below 74 = 5.00 (Failed)</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('chairperson.grades.bulk-update', $subject) }}">
                        @csrf

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-emerald-700 to-emerald-600 text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Student ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Student Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase">Program</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">Current Grade</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold uppercase">New Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($enrollments as $index => $enrollment)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                {{ $enrollment->student->student_id }}
                                                <input type="hidden" name="grades[{{ $index }}][enrollment_id]" value="{{ $enrollment->id }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $enrollment->student->program->code ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($enrollment->grade !== null)
                                                    @if(is_numeric($enrollment->grade))
                                                        <span class="font-semibold text-emerald-700">{{ number_format($enrollment->grade, 2) }}</span>
                                                    @else
                                                        <span class="font-semibold text-orange-600">{{ $enrollment->grade }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400 italic">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <select name="grades[{{ $index }}][grade]"
                                                        class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                                    <option value="">--</option>
                                                    <option value="1.00" {{ old("grades.{$index}.grade", $enrollment->grade) == '1.00' ? 'selected' : '' }}>1.00</option>
                                                    <option value="1.25" {{ old("grades.{$index}.grade", $enrollment->grade) == '1.25' ? 'selected' : '' }}>1.25</option>
                                                    <option value="1.50" {{ old("grades.{$index}.grade", $enrollment->grade) == '1.50' ? 'selected' : '' }}>1.50</option>
                                                    <option value="1.75" {{ old("grades.{$index}.grade", $enrollment->grade) == '1.75' ? 'selected' : '' }}>1.75</option>
                                                    <option value="2.00" {{ old("grades.{$index}.grade", $enrollment->grade) == '2.00' ? 'selected' : '' }}>2.00</option>
                                                    <option value="2.25" {{ old("grades.{$index}.grade", $enrollment->grade) == '2.25' ? 'selected' : '' }}>2.25</option>
                                                    <option value="2.50" {{ old("grades.{$index}.grade", $enrollment->grade) == '2.50' ? 'selected' : '' }}>2.50</option>
                                                    <option value="2.75" {{ old("grades.{$index}.grade", $enrollment->grade) == '2.75' ? 'selected' : '' }}>2.75</option>
                                                    <option value="3.00" {{ old("grades.{$index}.grade", $enrollment->grade) == '3.00' ? 'selected' : '' }}>3.00</option>
                                                    <option value="5.00" {{ old("grades.{$index}.grade", $enrollment->grade) == '5.00' ? 'selected' : '' }}>5.00</option>
                                                    <option value="IP" {{ old("grades.{$index}.grade", $enrollment->grade) == 'IP' ? 'selected' : '' }}>IP</option>
                                                    <option value="INC" {{ old("grades.{$index}.grade", $enrollment->grade) == 'INC' ? 'selected' : '' }}>INC</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Reason for grade entry -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Grade Entry/Modification <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="reason" 
                                   id="reason" 
                                   value="{{ old('reason', 'End of semester grade entry') }}"
                                   required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="e.g., End of semester grade entry, Final exam results">
                            <p class="mt-1 text-xs text-gray-500">This will be recorded in the grade history for audit purposes.</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-between items-center">
                            <a href="{{ route('chairperson.grades.index', ['view' => 'subjects']) }}" 
                               class="text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded font-medium transition">
                                Save All Grades
                            </button>
                        </div>
                    </form>

                    <!-- Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-emerald-700">{{ $enrollments->count() }}</div>
                                <div class="text-sm text-gray-600">Total Students</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-600">{{ $enrollments->whereNotNull('grade')->count() }}</div>
                                <div class="text-sm text-gray-600">Graded</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-yellow-600">{{ $enrollments->whereNull('grade')->count() }}</div>
                                <div class="text-sm text-gray-600">Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
