<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header with gradient -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-brand-deep to-brand-medium bg-clip-text text-transparent mb-2">BSU-Bokod SIMS Dashboard</h2>
                        <p class="text-gray-600">Welcome back! Here's what's happening today.</p>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Students Card -->
                        <div class="group relative bg-gradient-to-br from-brand-medium to-brand-deep p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-white text-opacity-90 text-sm font-medium mb-1">Total Students</div>
                            <div class="text-4xl font-bold text-white">{{ $totalStudents ?? 0 }}</div>
                            <div class="absolute bottom-0 right-0 p-4 opacity-10">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Active Students Card -->
                        <div class="group relative bg-gradient-to-br from-brand-light to-brand-medium p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-white text-opacity-90 text-sm font-medium mb-1">Active Students</div>
                            <div class="text-4xl font-bold text-white">{{ $activeStudents ?? 0 }}</div>
                            <div class="absolute bottom-0 right-0 p-4 opacity-10">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Programs Card -->
                        <div class="group relative bg-gradient-to-br from-brand-primary to-brand-deep p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-white text-opacity-90 text-sm font-medium mb-1">Programs</div>
                            <div class="text-4xl font-bold text-white">{{ $totalPrograms ?? 0 }}</div>
                            <div class="absolute bottom-0 right-0 p-4 opacity-10">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Graduated Students Card -->
                        <div class="group relative bg-gradient-to-br from-gold-accent to-amber-600 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-white text-opacity-90 text-sm font-medium mb-1">Graduated</div>
                            <div class="text-4xl font-bold text-white">{{ $graduatedStudents ?? 0 }}</div>
                            <div class="absolute bottom-0 right-0 p-4 opacity-10">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Program Distribution Chart -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-brand-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                </svg>
                                Students by Program
                            </h3>
                            <canvas id="programChart" class="max-h-64"></canvas>
                        </div>

                        <!-- Year Level Distribution Chart -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-brand-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Students by Year Level
                            </h3>
                            <canvas id="yearLevelChart" class="max-h-64"></canvas>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-brand-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Quick Actions
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('students.create') }}" class="group relative bg-white border-2 border-brand-pale hover:border-brand-medium p-6 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                                <div class="flex items-start space-x-4">
                                    <div class="p-3 bg-brand-pale group-hover:bg-brand-medium rounded-lg transition-colors duration-300">
                                        <svg class="w-6 h-6 text-brand-deep group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1">Add New Student</h4>
                                        <p class="text-sm text-gray-600">Register a new student</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('students.index') }}" class="group relative bg-white border-2 border-brand-pale hover:border-brand-deep p-6 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                                <div class="flex items-start space-x-4">
                                    <div class="p-3 bg-brand-pale group-hover:bg-brand-deep rounded-lg transition-colors duration-300">
                                        <svg class="w-6 h-6 text-brand-deep group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1">View All Students</h4>
                                        <p class="text-sm text-gray-600">Manage student records</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('reports.index') }}" class="group relative bg-white border-2 border-brand-pale hover:border-brand-medium p-6 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
                                <div class="flex items-start space-x-4">
                                    <div class="p-3 bg-brand-pale group-hover:bg-brand-medium rounded-lg transition-colors duration-300">
                                        <svg class="w-6 h-6 text-brand-medium group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1">Generate Reports</h4>
                                        <p class="text-sm text-gray-600">View and export data</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-gradient-to-br from-brand-pale to-brand-light/30 rounded-2xl shadow-sm p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-brand-deep" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Recent Activity
                        </h3>
                        <div class="bg-white rounded-xl border border-gray-200">
                            @if($recentActivities->count() > 0)
                                <div class="divide-y divide-gray-200">
                                    @foreach($recentActivities as $activity)
                                        <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                    <span class="inline-block bg-brand-pale text-brand-primary px-2 py-1 rounded mr-2">{{ $activity->action }}</span>
                                                        {{ $activity->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <div class="text-right ml-4">
                                                    @if($activity->user)
                                                        <p class="text-xs text-gray-600">{{ $activity->user->name }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="p-4 border-t border-gray-200">
                                    <a href="{{ route('activities.index') }}" class="text-sm text-brand-deep hover:text-brand-medium font-medium">View all activity →</a>
                                </div>
                            @else
                                <div class="flex items-center justify-center py-8">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 font-medium">No recent activity yet</p>
                                        <p class="text-sm text-gray-400 mt-1">Start by adding students to see activity here</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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
                                        'rgba(30, 126, 52, 0.8)',
                                        'rgba(76, 175, 80, 0.8)',
                                        'rgba(165, 214, 167, 0.8)',
                                        'rgba(45, 80, 22, 0.8)',
                                        'rgba(100, 190, 100, 0.8)',
                                        'rgba(56, 142, 60, 0.8)',
                                        'rgba(124, 179, 66, 0.8)'
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
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
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
                    backgroundColor: 'rgba(76, 175, 80, 0.8)',
                    borderColor: 'rgba(30, 126, 52, 1)',
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
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        },
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
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
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
