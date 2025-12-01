<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Master List Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('reports.students') }}" class="flex gap-3">
                            <select name="program" class="border rounded px-3 py-2">
                                <option value="">All Programs</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ request('program') == $program->id ? 'selected' : '' }}>
                                        {{ $program->code }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year_level" class="border rounded px-3 py-2">
                                <option value="">All Year Levels</option>
                                <option value="1st Year" {{ request('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ request('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ request('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ request('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                            </select>
                            <select name="status" class="border rounded px-3 py-2">
                                <option value="">All Status</option>
                                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Graduated" {{ request('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                <option value="Dropped" {{ request('status') == 'Dropped' ? 'selected' : '' }}>Dropped</option>
                            </select>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Filter
                            </button>
                            <button type="button" onclick="window.print()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                Print
                            </button>
                        </form>
                    </div>

                    <!-- Report Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($students as $index => $student)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $student->student_id }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $student->last_name }}, {{ $student->first_name }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $student->program->code ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $student->year_level }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $student->status }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                            No students found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-sm text-gray-600">
                        <p><strong>Total Students:</strong> {{ $students->count() }}</p>
                        <p><strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
