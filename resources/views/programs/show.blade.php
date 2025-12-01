<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Program Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('programs.edit', $program) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Edit
                </a>
                <a href="{{ route('programs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Program Info Card -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl mb-6">
                <div class="p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $program->name }}</h3>
                            <p class="text-lg text-gray-600">{{ $program->code }}</p>
                        </div>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($program->is_active) bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $program->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if($program->description)
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Description</h4>
                            <p class="text-gray-600">{{ $program->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-4xl font-bold text-indigo-600">{{ $program->students_count }}</p>
                            <p class="text-sm text-gray-600 mt-1">Total Students</p>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-green-600">{{ $activeStudents }}</p>
                            <p class="text-sm text-gray-600 mt-1">Active Students</p>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-purple-600">{{ $graduatedStudents }}</p>
                            <p class="text-sm text-gray-600 mt-1">Graduated</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Enrolled Students
                    </h3>

                    @if($students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Student ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Year Level</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->student_id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($student->photo_path)
                                                        <img src="{{ asset('storage/' . $student->photo_path) }}" 
                                                             alt="{{ $student->first_name }}"
                                                             class="w-10 h-10 rounded-full object-cover mr-3">
                                                    @else
                                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center mr-3">
                                                            <span class="text-white font-semibold text-sm">
                                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $student->last_name }}, {{ $student->first_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $student->year_level }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'Active' => 'bg-green-100 text-green-800',
                                                        'Graduated' => 'bg-blue-100 text-blue-800',
                                                        'On Leave' => 'bg-yellow-100 text-yellow-800',
                                                        'Dropped' => 'bg-red-100 text-red-800',
                                                        'Transferred' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                    $color = $statusColors[$student->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                    {{ $student->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('students.show', $student) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($students->hasPages())
                            <div class="mt-6">
                                {{ $students->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No Students Enrolled</h3>
                            <p class="text-gray-500">This program doesn't have any enrolled students yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
