<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Subjects Management') }}
            </h2>
            <a href="{{ route('subjects.create') }}" class="bg-brand-medium hover:bg-brand-deep text-white px-4 py-2 rounded transition">
                Add New Subject
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search and Filter Section -->
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                            <input type="text" id="searchInput" placeholder="Search by code or name..." 
                                   class="border rounded px-3 py-2 lg:col-span-2" autocomplete="off">
                            <select id="departmentFilter" class="border rounded">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->code }}</option>
                                @endforeach
                            </select>
                            <select id="yearLevelFilter" class="border rounded">
                                <option value="">All Year Levels</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                            <select id="semesterFilter" class="border rounded">
                                <option value="">All Semesters</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="mt-2 flex gap-2">
                            <button id="clearFilters" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded transition text-sm">
                                Clear Filters
                            </button>
                            <p class="text-sm text-gray-500 ml-auto">Found: <span id="resultCount">{{ $subjects->count() }}</span> subjects</p>
                        </div>
                    </div>

                    <!-- Hierarchical Department View -->
                    <div class="space-y-4" id="departmentContainer">
                        @forelse($departments as $department)
                            <div class="border rounded-lg overflow-hidden department-section" data-dept-id="{{ $department->id }}">
                                <!-- Department Header -->
                                <div class="bg-gradient-to-r from-brand-deep to-brand-medium hover:from-brand-dark hover:to-brand-deep text-white px-6 py-4 cursor-pointer flex items-center justify-between transition" onclick="toggleDepartment(this)">
                                    <div class="flex items-center gap-3">
                                        <svg class="department-toggle w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        <div>
                                            <h3 class="font-bold text-lg">{{ $department->name }}</h3>
                                            <p class="text-xs opacity-75">Code: {{ $department->code }} • {{ $department->programs->sum(fn($p) => $p->subjects->count()) }} subjects</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Department Content -->
                                <div class="department-content hidden bg-gray-50">
                                    @forelse($department->programs as $program)
                                        <div class="border-t program-item" data-program-id="{{ $program->id }}">
                                            <!-- Program Header -->
                                            <div class="bg-brand-light hover:bg-brand-lighter px-6 py-3 cursor-pointer flex items-center justify-between transition" onclick="toggleProgram(event)">
                                                <div class="flex items-center gap-3 flex-1">
                                                    <svg class="program-toggle w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">{{ $program->code }} - {{ $program->name }}</h4>
                                                        <p class="text-xs text-gray-600">{{ $program->subjects->count() }} subjects</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Subjects Table -->
                                            <div class="program-subjects hidden">
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-sm">
                                                        <thead class="bg-gray-200 text-gray-700">
                                                            <tr>
                                                                <th class="px-6 py-2 text-left font-semibold">Code</th>
                                                                <th class="px-6 py-2 text-left font-semibold">Subject Name</th>
                                                                <th class="px-6 py-2 text-left font-semibold">Year Level</th>
                                                                <th class="px-6 py-2 text-left font-semibold">Semester</th>
                                                                <th class="px-6 py-2 text-center font-semibold">Units</th>
                                                                <th class="px-6 py-2 text-left font-semibold">Status</th>
                                                                <th class="px-6 py-2 text-left font-semibold">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @forelse($program->subjects as $subject)
                                                                <tr class="subject-row hover:bg-gray-50 transition" data-subject-code="{{ $subject->code }}" data-subject-name="{{ $subject->name }}" data-year-level="{{ $subject->year_level }}" data-semester="{{ $subject->semester }}">
                                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded">
                                                                            {{ $subject->code }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-3">{{ $subject->name }}</td>
                                                                    <td class="px-6 py-3 whitespace-nowrap">{{ $subject->year_level }}</td>
                                                                    <td class="px-6 py-3 whitespace-nowrap">{{ $subject->semester }}</td>
                                                                    <td class="px-6 py-3 whitespace-nowrap text-center">{{ $subject->units }}</td>
                                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                                        <span class="px-2 py-1 text-xs rounded-full {{ $subject->is_active ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                            {{ $subject->is_active ? 'Offered' : 'Archived' }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-3 whitespace-nowrap text-sm">
                                                                        <a href="{{ route('subjects.edit', $subject) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                                        <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to {{ $subject->is_active ? "archive" : "restore" }} this subject?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="{{ $subject->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                                                                {{ $subject->is_active ? 'Archive' : 'Restore' }}
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                                        No subjects in this program.
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-6 py-4 text-gray-500 text-center">
                                            No programs in this department.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-center text-gray-500 bg-white rounded-lg border">
                                No departments available.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDepartment(header) {
            const content = header.nextElementSibling;
            const toggle = header.querySelector('.department-toggle');
            
            content.classList.toggle('hidden');
            toggle.classList.toggle('rotate-90');
        }

        function toggleProgram(event) {
            const header = event.currentTarget;
            const subjects = header.nextElementSibling;
            const toggle = header.querySelector('.program-toggle');
            
            subjects.classList.toggle('hidden');
            toggle.classList.toggle('rotate-90');
            
            event.stopPropagation();
        }

        $(document).ready(function() {
            function filterSubjects() {
                const searchTerm = $('#searchInput').val().toLowerCase();
                const deptFilter = $('#departmentFilter').val();
                const yearLevelFilter = $('#yearLevelFilter').val().toLowerCase();
                const semesterFilter = $('#semesterFilter').val().toLowerCase();
                let totalVisible = 0;
                const hasActiveSearch = searchTerm || deptFilter || yearLevelFilter || semesterFilter;

                // Filter department sections
                $('.department-section').each(function() {
                    const deptId = $(this).data('dept-id');
                    const deptContent = $(this).find('.department-content');
                    const deptToggle = $(this).find('.department-toggle');
                    let deptHasVisible = false;

                    // Check if department matches filter
                    if (deptFilter && deptId != deptFilter) {
                        $(this).hide();
                        return;
                    }

                    // Filter programs and subjects within this department
                    $(this).find('.program-item').each(function() {
                        const progContent = $(this).find('.program-subjects');
                        const progToggle = $(this).find('.program-toggle');
                        let progHasVisible = false;

                        $(this).find('.subject-row').each(function() {
                            const code = $(this).data('subject-code').toLowerCase();
                            const name = $(this).data('subject-name').toLowerCase();
                            const yearLevel = $(this).data('year-level').toLowerCase();
                            const semester = $(this).data('semester').toLowerCase();

                            const matchesSearch = searchTerm === '' || code.includes(searchTerm) || name.includes(searchTerm);
                            const matchesYear = yearLevelFilter === '' || yearLevel === yearLevelFilter;
                            const matchesSem = semesterFilter === '' || semester === semesterFilter;

                            if (matchesSearch && matchesYear && matchesSem) {
                                $(this).show();
                                progHasVisible = true;
                                deptHasVisible = true;
                                totalVisible++;
                            } else {
                                $(this).hide();
                            }
                        });

                        // Show/hide program based on subject visibility
                        if (progHasVisible) {
                            $(this).show();
                            // Auto-expand program when search is active
                            if (hasActiveSearch) {
                                progContent.removeClass('hidden');
                                progToggle.addClass('rotate-90');
                            }
                        } else {
                            $(this).hide();
                        }
                    });

                    // Show/hide department based on content
                    if (deptHasVisible) {
                        $(this).show();
                        // Auto-expand department when search is active
                        if (hasActiveSearch) {
                            deptContent.removeClass('hidden');
                            deptToggle.addClass('rotate-90');
                        }
                    } else {
                        $(this).hide();
                    }
                });

                $('#resultCount').text(totalVisible);
            }

            $('#searchInput').on('keyup', filterSubjects);
            $('#departmentFilter, #yearLevelFilter, #semesterFilter').on('change', filterSubjects);

            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                $('#departmentFilter').val('');
                $('#yearLevelFilter').val('');
                $('#semesterFilter').val('');
                // Collapse all sections
                $('.department-content').addClass('hidden');
                $('.program-subjects').addClass('hidden');
                $('.department-toggle').removeClass('rotate-90');
                $('.program-toggle').removeClass('rotate-90');
                filterSubjects();
            });
        });
    </script>
</x-app-layout>
