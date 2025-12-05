<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <div class="py-8">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-1">
                    @php
                        $hour = date('H');
                        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
                    @endphp
                    {{ $greeting }}, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-base text-gray-600">Here's what's happening with your student information system today.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Students Card -->
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-indigo-600 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Students</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $totalStudents ?? 0 }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        All enrolled students
                    </p>
                </div>

                <!-- Active Students Card -->
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-emerald-600 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Active Students</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $activeStudents ?? 0 }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Currently enrolled
                    </p>
                </div>

                <!-- Programs Card -->
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-purple-600 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Programs</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $totalPrograms ?? 0 }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Academic programs
                    </p>
                </div>

                <!-- Graduated Students Card -->
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-amber-600 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Graduated</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $graduatedStudents ?? 0 }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Completed studies
                    </p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Program Distribution Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        Students by Program
                    </h3>
                    <canvas id="programChart" class="max-h-64"></canvas>
                </div>

                <!-- Year Level Distribution Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        Students by Year Level
                    </h3>
                    <canvas id="yearLevelChart" class="max-h-64"></canvas>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-5">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <a href="{{ route('students.create') }}" class="group bg-gradient-to-br from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-white/20 backdrop-blur-sm group-hover:bg-white/30 rounded-lg transition-all">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-lg mb-1">Add New Student</h4>
                                <p class="text-sm text-indigo-100">Register a new student to the system</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('students.index') }}" class="group bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-white/20 backdrop-blur-sm group-hover:bg-white/30 rounded-lg transition-all">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-lg mb-1">View All Students</h4>
                                <p class="text-sm text-emerald-100">Browse and manage student records</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('reports.index') }}" class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-white/20 backdrop-blur-sm group-hover:bg-white/30 rounded-lg transition-all">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-lg mb-1">Generate Reports</h4>
                                <p class="text-sm text-purple-100">View analytics and export data</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Recent Activity
                </h3>
                @if($recentActivities->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($recentActivities as $activity)
                            <div class="py-4 hover:bg-gray-50 -mx-6 px-6 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-base font-medium text-gray-800">{{ $activity->description }}</p>
                                        <p class="text-sm text-gray-500 mt-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600 mr-2">
                                                {{ $activity->action }}
                                            </span>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="text-right ml-4">
                                        @if($activity->user)
                                            <p class="text-sm text-gray-500">{{ $activity->user->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('activities.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            View all activity →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">No recent activity yet</p>
                        <p class="text-sm text-gray-500 mt-1">Start by adding students to see activity here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Program Distribution Chart
        const programCtx = document.getElementById('programChart').getContext('2d');
        const programData = @json($programData);
        
        new Chart(programCtx, {
            type: 'doughnut',
            data: {
                labels: programData.map(p => p.name),
                datasets: [{
                    data: programData.map(p => p.count),
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',   // Indigo (primary variant)
                        'rgba(139, 92, 246, 0.8)',   // Violet (accent)
                        'rgba(236, 72, 153, 0.8)',   // Pink
                        'rgba(251, 146, 60, 0.8)',   // Orange
                        'rgba(34, 197, 94, 0.8)',    // Green (success variant)
                        'rgba(14, 165, 233, 0.8)',   // Sky blue
                        'rgba(168, 85, 247, 0.8)'    // Purple
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' students';
                            }
                        }
                    }
                }
            }
        });

        // Year Level Distribution Chart
        const yearLevelCtx = document.getElementById('yearLevelChart').getContext('2d');
        const yearLevelData = @json($yearLevelData);
        
        new Chart(yearLevelCtx, {
            type: 'bar',
            data: {
                labels: yearLevelData.map(y => y.year_level),
                datasets: [{
                    label: 'Number of Students',
                    data: yearLevelData.map(y => y.count),
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',   // Indigo - 1st Year
                        'rgba(34, 197, 94, 0.8)',    // Green - 2nd Year
                        'rgba(251, 146, 60, 0.8)',   // Orange - 3rd Year
                        'rgba(236, 72, 153, 0.8)',   // Pink - 4th Year
                        'rgba(168, 85, 247, 0.8)'    // Purple - 5th Year (if any)
                    ],
                    borderColor: [
                        'rgba(99, 102, 241, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(251, 146, 60, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(168, 85, 247, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' students';
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
