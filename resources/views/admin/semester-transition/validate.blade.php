<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transition Validation Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <strong>Step 2 of 3:</strong> Validation Report<br>
                            Transitioning from <strong>{{ $currentYear->year }}</strong> to <strong>{{ $nextYear->year }}</strong>
                        </p>
                    </div>

                    <!-- Status Indicator -->
                    @if($validation['ready'])
                        <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-sm text-green-800">
                                <strong>✓ Ready to Proceed:</strong> All validations passed. No blocking issues found.
                            </p>
                        </div>
                    @else
                        <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200">
                            <p class="text-sm text-red-800 font-semibold mb-2">
                                <strong>✗ Validation Failed:</strong> Blocking issues must be resolved before proceeding.
                            </p>
                            @if(!empty($validation['errors']))
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($validation['errors'] as $error)
                                        <li class="text-red-700">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <!-- Statistics Grid -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Student Distribution</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-2xl font-bold text-gray-800">{{ $validation['statistics']['total_students'] }}</p>
                                <p class="text-xs text-gray-600 mt-1">Total Students</p>
                            </div>
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-2xl font-bold text-green-800">{{ $validation['statistics']['promoted_normal'] }}</p>
                                <p class="text-xs text-green-600 mt-1">Promoted</p>
                            </div>
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-2xl font-bold text-blue-800">{{ $validation['statistics']['promoted_irregular'] }}</p>
                                <p class="text-xs text-blue-600 mt-1">Irregular</p>
                            </div>
                            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                <p class="text-2xl font-bold text-yellow-800">{{ $validation['statistics']['retained'] }}</p>
                                <p class="text-xs text-yellow-600 mt-1">Retained</p>
                            </div>
                            <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                <p class="text-2xl font-bold text-red-800">{{ $validation['statistics']['probation'] }}</p>
                                <p class="text-xs text-red-600 mt-1">Probation</p>
                            </div>
                        </div>
                    </div>

                    <!-- Warnings -->
                    @if(!empty($validation['warnings']))
                        <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-sm font-semibold text-yellow-800 mb-3">⚠ Warnings (Non-blocking):</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($validation['warnings'] as $warning)
                                    <li class="text-yellow-700 text-sm">{{ $warning }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- What Will Happen -->
                    <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <p class="text-sm font-semibold text-purple-800 mb-2">What will happen:</p>
                        <ul class="list-disc list-inside space-y-2 text-sm text-purple-700">
                            <li><strong>Promoted Students:</strong> Auto-enrolled in next year/level subjects</li>
                            <li><strong>Irregular Students:</strong> Auto-enrolled in next level + retake failed subjects</li>
                            <li><strong>Retained Students:</strong> Re-enrolled in same level subjects</li>
                            <li><strong>Probation Students:</strong> No auto-enrollment (manual intervention needed)</li>
                            <li><strong>Academic Year:</strong> {{ $currentYear->year }} marked as archived (read-only)</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t">
                        <a href="{{ route('semester-transition.index') }}" 
                           class="text-gray-700 hover:text-gray-900 font-medium">
                            ← Back
                        </a>
                        <div class="flex gap-3">
                            <a href="{{ route('semester-transition.index') }}" 
                               class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                Cancel
                            </a>
                            @if($validation['ready'])
                                <form method="GET" action="{{ route('semester-transition.confirm') }}" class="inline">
                                    <button type="submit" 
                                            class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                        Continue to Confirmation →
                                    </button>
                                </form>
                            @else
                                <button disabled
                                        class="px-6 py-2 bg-gray-400 text-gray-200 rounded-lg font-medium cursor-not-allowed">
                                    Continue to Confirmation → (Blocked)
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
