<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Semester Transition') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <strong>Step 3 of 3:</strong> Final Confirmation<br>
                            Review the details below and confirm to proceed with the semester transition.
                        </p>
                    </div>

                    <!-- Critical Warning -->
                    <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200">
                        <p class="text-sm font-semibold text-red-800 mb-2">
                            ⚠ CRITICAL: This action cannot be easily undone!
                        </p>
                        <p class="text-sm text-red-700">
                            Once executed, academic standing will be calculated, enrollments created, and the current year archived. Please review all information carefully.
                        </p>
                    </div>

                    <!-- Transition Summary -->
                    <div class="mb-6 space-y-4">
                        <h3 class="text-lg font-semibold">Transition Details</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600 uppercase tracking-wide">From Academic Year</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $currentYear->year }}</p>
                                <p class="text-xs text-gray-500 mt-2">↓ (Will be archived)</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600 uppercase tracking-wide">To Academic Year</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $nextYear->year }}</p>
                                <p class="text-xs text-gray-500 mt-2">(New enrollments will be created)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student Distribution Confirmation -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Expected Student Distribution</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                                <p class="text-3xl font-bold text-gray-800">{{ $validation['statistics']['total_students'] }}</p>
                                <p class="text-sm text-gray-600 mt-2">Total</p>
                            </div>
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200 text-center">
                                <p class="text-3xl font-bold text-green-800">{{ $validation['statistics']['promoted_normal'] }}</p>
                                <p class="text-sm text-green-600 mt-2">Promoted</p>
                            </div>
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
                                <p class="text-3xl font-bold text-blue-800">{{ $validation['statistics']['promoted_irregular'] }}</p>
                                <p class="text-sm text-blue-600 mt-2">Irregular</p>
                            </div>
                            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 text-center">
                                <p class="text-3xl font-bold text-yellow-800">{{ $validation['statistics']['retained'] }}</p>
                                <p class="text-sm text-yellow-600 mt-2">Retained</p>
                            </div>
                            <div class="p-4 bg-red-50 rounded-lg border border-red-200 text-center">
                                <p class="text-3xl font-bold text-red-800">{{ $validation['statistics']['probation'] }}</p>
                                <p class="text-sm text-red-600 mt-2">Probation</p>
                            </div>
                        </div>
                    </div>

                    <!-- What Will Happen -->
                    <div class="mb-6 p-4 bg-info-50 rounded-lg border border-info-200">
                        <p class="text-sm font-semibold text-info-800 mb-3">The system will:</p>
                        <ol class="list-decimal list-inside space-y-2 text-sm text-info-700">
                            <li>Calculate academic standing for all {{ $validation['statistics']['total_students'] }} students</li>
                            <li>Create new enrollments based on student performance</li>
                            <li>Mark {{ $validation['statistics']['promoted_irregular'] }} students as IRREGULAR for retakes</li>
                            <li>Mark {{ $validation['statistics']['retained'] }} students as RETAINED for year repetition</li>
                            <li>Archive {{ $currentYear->year }} (read-only)</li>
                            <li>Set {{ $nextYear->year }} as the active academic year</li>
                        </ol>
                    </div>

                    <!-- Confirmation Checkbox -->
                    <form method="POST" action="{{ route('semester-transition.execute') }}" class="space-y-6">
                        @csrf

                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <label class="flex items-start">
                                <input type="checkbox" id="confirmed" name="confirmed" required 
                                       class="mt-1 w-4 h-4 text-brand-deep rounded border-gray-300">
                                <span class="ml-3 text-sm text-yellow-800">
                                    I have reviewed all information and understand this action will:
                                    <ul class="list-disc list-inside mt-2 space-y-1 ml-4">
                                        <li>Archive the current academic year</li>
                                        <li>Calculate academic standing for all students</li>
                                        <li>Create new enrollments for the next year</li>
                                    </ul>
                                </span>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            <a href="{{ route('semester-transition.index') }}" 
                               class="text-gray-700 hover:text-gray-900 font-medium">
                                ← Back to Start
                            </a>
                            <div class="flex gap-3">
                                <a href="{{ route('semester-transition.index') }}" 
                                   class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="executeBtn" disabled>
                                    Execute Transition (Point of No Return)
                                </button>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.getElementById('confirmed').addEventListener('change', function() {
                            document.getElementById('executeBtn').disabled = !this.checked;
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
