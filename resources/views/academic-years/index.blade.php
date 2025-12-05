<x-app-layout>
    <x-slot name="title">Academic Years</x-slot>
    <style>
        dialog[open] {
            display: flex !important;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 50;
        }
        
        dialog {
            border: none !important;
            padding: 0 !important;
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 28rem;
            width: 90%;
        }
        
        dialog::backdrop {
            background: rgba(0, 0, 0, 0.5);
        }
        
        .transition-btn {
            background-color: #9333ea !important;
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            border: none !important;
        }
        
        .transition-btn:hover {
            background-color: #7e22ce !important;
        }
    </style>
    
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Academic Years') }}
            </h2>
            <div class="flex gap-2">
                <button onclick="document.getElementById('transitionModal').showModal()" class="transition-btn">
                    Transition Students
                </button>
                <a href="{{ route('academic-years.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded cursor-pointer">
                    Add New Academic Year
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded border border-green-400">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Instructions Card -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2">Academic Years Management</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li><strong>Set Current:</strong> Click "Set Current" to mark an academic year/semester as the active one.</li>
                            <li><strong>Transition Students:</strong> Use this at the end of each semester to calculate standings and auto-enroll promoted students into the next semester.</li>
                            <li><strong>During transition:</strong> Grades are evaluated → standing is updated (good, irregular, retained, probation) → enrollments for the next semester are created → the next semester is set as current.</li>
                            <li><strong>Edit/Delete:</strong> Modify academic year dates or remove records from the Actions column.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($academicYears->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700">
                                <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 border-b-2 border-indigo-700">
                                    <tr>
                                        <th class="px-4 py-3 font-bold text-white">Year Code</th>
                                        <th class="px-4 py-3 font-bold text-white">Semester</th>
                                        <th class="px-4 py-3 font-bold text-white">Start Date</th>
                                        <th class="px-4 py-3 font-bold text-white">End Date</th>
                                        <th class="px-4 py-3 font-bold text-white">Registration</th>
                                        <th class="px-4 py-3 font-bold text-white">Current</th>
                                        <th class="px-4 py-3 font-bold text-center text-white">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($academicYears as $year)
                                        <tr class="border-b hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 font-medium">{{ $year->year_code }}</td>
                                            <td class="px-4 py-3">{{ $year->semester }}</td>
                                            <td class="px-4 py-3">{{ $year->start_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">{{ $year->end_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">
                                                @if ($year->registration_start_date && $year->registration_end_date)
                                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                        @php
                                                            $startDate = is_string($year->registration_start_date) ? \Carbon\Carbon::parse($year->registration_start_date) : $year->registration_start_date;
                                                            $endDate = is_string($year->registration_end_date) ? \Carbon\Carbon::parse($year->registration_end_date) : $year->registration_end_date;
                                                        @endphp
                                                        {{ $startDate->format('M d') }} - {{ $endDate->format('M d') }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-500">Not set</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if ($year->is_current)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        ✓ Current
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex gap-2 justify-center flex-wrap">
                                                    @if (!$year->is_current)
                                                        <form action="{{ route('academic-years.set-current', $year) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-blue-600 hover:text-blue-900 hover:underline text-xs font-semibold cursor-pointer" title="Set as current">
                                                                Set Current
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('academic-years.edit', $year) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline text-xs font-semibold">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('academic-years.destroy', $year) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 hover:underline text-xs font-semibold cursor-pointer">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $academicYears->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No academic years</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new academic year.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transition Students Modal -->
    <dialog id="transitionModal" class="rounded-lg shadow-2xl max-w-md w-96 p-0">
        <div class="bg-white rounded-lg p-6">
            <h2 class="text-xl font-bold mb-2 text-gray-900">Transition Students to Next Semester</h2>
            <p class="text-sm text-gray-600 mb-4">Calculate academic standing and auto-enroll students based on their grades.</p>
            
            <form method="POST" action="{{ route('semester-transition.execute') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Current Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="current_year_id" required class="w-full border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-600">
                        <option value="">-- Select Current Semester --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">
                                {{ $year->year_code }} ({{ $year->semester }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Next Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="next_year_id" required class="w-full border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-600">
                        <option value="">-- Select Next Semester --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">
                                {{ $year->year_code }} ({{ $year->semester }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded p-3 text-sm text-yellow-800">
                    ⚠ This will calculate academic standing and create new enrollments. This cannot be undone.
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('transitionModal').close()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded font-medium">
                        Execute Transition
                    </button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>
