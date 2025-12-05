<x-app-layout>
    <x-slot name="title">Modify Grades</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modify Grades') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.grade-modifications.index') }}" class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                    Search Student
                                </label>
                                <input type="text" name="search" id="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Student ID, Last Name, First Name"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-brand-medium focus:border-brand-medium">
                            </div>
                            <div>
                                <label for="from_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    From Date
                                </label>
                                <input type="date" name="from_date" id="from_date" 
                                       value="{{ request('from_date') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-brand-medium focus:border-brand-medium">
                            </div>
                            <div>
                                <label for="to_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    To Date
                                </label>
                                <input type="date" name="to_date" id="to_date" 
                                       value="{{ request('to_date') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-brand-medium focus:border-brand-medium">
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-medium hover:to-brand-light text-white rounded-lg font-medium">
                                Search
                            </button>
                            <a href="{{ route('admin.grade-modifications.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Graded Enrollments</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Approved</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">History</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($enrollments as $enrollment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $enrollment->student->student_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $enrollment->subject->code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                            {{ number_format($enrollment->grade, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $enrollment->approved_at ? $enrollment->approved_at->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-medium">
                                                {{ $enrollment->gradeHistories->count() }} change{{ $enrollment->gradeHistories->count() !== 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('admin.grade-modifications.edit', $enrollment) }}" 
                                               class="text-brand-deep hover:text-brand-medium font-medium">
                                                Edit
                                            </a>
                                            <a href="{{ route('admin.grade-modifications.history', $enrollment) }}" 
                                               class="text-blue-600 hover:text-blue-900 font-medium">
                                                History
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No graded enrollments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $enrollments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
