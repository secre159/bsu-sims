<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Students Management') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('students.import.form') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Import CSV
                </a>
                <a href="{{ route('students.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Add New Student
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search and Filter Section -->
                    <div class="mb-4">
                        <div class="flex gap-3">
                            <input type="text" id="searchInput" placeholder="Search by name or ID..." 
                                   class="border rounded px-3 py-2 flex-1" autocomplete="off">
                            <select id="programFilter" class="border rounded px-3 py-2">
                                <option value="">All Programs</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->code }}">
                                        {{ $program->code }}
                                    </option>
                                @endforeach
                            </select>
                            <select id="yearLevelFilter" class="border rounded px-3 py-2">
                                <option value="">All Year Levels</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                            <button id="clearFilters" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                                Clear
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Found: <span id="resultCount">{{ $students->count() }}</span> students</p>
                    </div>

                    <!-- Students Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Program</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Year Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->student_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->last_name }}, {{ $student->first_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->program->code ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->year_level }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($student->status == 'Active') bg-green-100 text-green-800
                                                @elseif($student->status == 'Graduated') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $student->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('students.subjects', $student) }}" class="text-green-600 hover:text-green-900 mr-3">Subjects</a>
                                            <a href="{{ route('students.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No students found. Add students using the button above.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function() {
        console.log('Student filter script loaded');
        
        function filterTable() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            const programFilter = $('#programFilter').val().toLowerCase();
            const yearLevelFilter = $('#yearLevelFilter').val().toLowerCase();
            let visibleCount = 0;
            
            $('tbody tr').each(function() {
                const row = $(this);
                
                // Skip the "no students found" row
                if (row.find('td').attr('colspan')) {
                    return;
                }
                
                const studentId = row.find('td:eq(0)').text().toLowerCase();
                const studentName = row.find('td:eq(1)').text().toLowerCase();
                const program = row.find('td:eq(2)').text().toLowerCase();
                const yearLevel = row.find('td:eq(3)').text().toLowerCase();
                
                const matchesSearch = searchTerm === '' || 
                                    studentId.includes(searchTerm) || 
                                    studentName.includes(searchTerm);
                const matchesProgram = programFilter === '' || program.includes(programFilter);
                const matchesYearLevel = yearLevelFilter === '' || yearLevel === yearLevelFilter.toLowerCase();
                
                if (matchesSearch && matchesProgram && matchesYearLevel) {
                    row.show();
                    visibleCount++;
                } else {
                    row.hide();
                }
            });
            
            $('#resultCount').text(visibleCount);
        }
        
        // Trigger filter on input
        $('#searchInput').on('keyup', filterTable);
        $('#programFilter, #yearLevelFilter').on('change', filterTable);
        
        // Clear filters
        $('#clearFilters').on('click', function() {
            $('#searchInput').val('');
            $('#programFilter').val('');
            $('#yearLevelFilter').val('');
            filterTable();
        });
    });
    </script>
</x-app-layout>
