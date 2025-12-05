<x-app-layout>
    <x-slot name="title">Chairperson Dashboard</x-slot>
    <div class="py-8">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-1">
                            @php
                                $hour = date('H');
                                $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
                            @endphp
                            {{ $greeting }}, {{ Auth::user()->name }}! 👋
                        </h1>
                        <p class="text-base text-gray-600">Welcome to your grade management dashboard.</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md px-6 py-3 border-l-4 border-indigo-600">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Your Department</p>
                        <p class="text-lg font-bold text-gray-900">{{ Auth::user()->department->code }}</p>
                        <p class="text-sm text-gray-600">{{ Auth::user()->department->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Students Card -->
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-indigo-600 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">My Students</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $totalStudents }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        In your department
                    </p>
                </div>

                <!-- Pending Grades Card -->
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-amber-600 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Pending Grades</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $pendingGrades }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Need attention
                    </p>
                </div>

                <!-- Completed Grades Card -->
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-emerald-600 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Completed</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $completedGrades }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Grades entered
                    </p>
                </div>

                <!-- Total Batches Card -->
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-purple-600 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Import Batches</p>
                            <h3 class="text-4xl font-bold text-gray-900">{{ $totalBatches }}</h3>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Total uploads
                    </p>
                </div>
            </div>

            <!-- Grade Completion Progress -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    Overall Grade Completion
                </h3>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <div class="w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                            @if($gradeCompletionPercentage > 0)
                                <div class="h-8 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold transition-all duration-500"
                                     style="width: {{ $gradeCompletionPercentage }}%">
                                    {{ $gradeCompletionPercentage }}%
                                </div>
                            @else
                                <div class="h-8 flex items-center justify-center text-gray-500 text-sm font-semibold">
                                    0% - No grades entered yet
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900">{{ $completedGrades }}/{{ $pendingGrades + $completedGrades }}</p>
                        <p class="text-xs text-gray-500">Grades Entered</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Subjects Needing Attention -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
                        <div class="p-2 bg-amber-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        Subjects Needing Attention
                    </h3>
                    @if($subjectsWithStats->count() > 0)
                        <div class="space-y-4">
                            @foreach($subjectsWithStats as $subject)
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-semibold text-gray-700">{{ $subject->code }} - {{ $subject->name }}</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $subject->completion_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                        <div class="h-3 rounded-full transition-all duration-500 {{ $subject->completion_percentage >= 80 ? 'bg-emerald-500' : ($subject->completion_percentage >= 50 ? 'bg-amber-500' : 'bg-red-500') }}"
                                             style="width: {{ $subject->completion_percentage }}%">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $subject->pending_grades }} pending out of {{ $subject->total_enrollments }} total</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-600 font-medium">No subjects with enrollments</p>
                        </div>
                    @endif
                </div>

                <!-- Batch Status Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        Import Batch Status
                    </h3>
                    @if($totalBatches > 0)
                        <canvas id="batchStatusChart" class="max-h-64"></canvas>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-600 font-medium">No import batches yet</p>
                            <p class="text-sm text-gray-500 mt-1">Start by importing grades</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-5">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <a href="{{ route('chairperson.grades.index') }}" class="group bg-gradient-to-br from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-white/20 backdrop-blur-sm group-hover:bg-white/30 rounded-lg transition-all">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-lg mb-1">Enter Grades</h4>
                                <p class="text-sm text-indigo-100">Manually enter student grades</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('chairperson.grade-import.create') }}" class="group bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-white/20 backdrop-blur-sm group-hover:bg-white/30 rounded-lg transition-all">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-lg mb-1">Import Grades</h4>
                                <p class="text-sm text-emerald-100">Upload Excel file with grades</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('chairperson.grade-batches.index') }}" class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-white/20 backdrop-blur-sm group-hover:bg-white/30 rounded-lg transition-all">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-lg mb-1">My Batches</h4>
                                <p class="text-sm text-purple-100">View import history & status</p>
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
                                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-600 mr-2">
                                                {{ $activity->action }}
                                            </span>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">No recent activity yet</p>
                        <p class="text-sm text-gray-500 mt-1">Start entering grades to see activity here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        @if($totalBatches > 0)
        // Batch Status Chart
        const batchStatusCtx = document.getElementById('batchStatusChart').getContext('2d');
        const batchStatusData = @json($batchStatusData);
        
        // Color mapping for each status
        const statusColors = {
            'Pending': 'rgba(251, 191, 36, 0.8)',
            'Approved': 'rgba(34, 197, 94, 0.8)',
            'Rejected': 'rgba(239, 68, 68, 0.8)'
        };
        
        const colors = batchStatusData.map(b => statusColors[b.status] || 'rgba(156, 163, 175, 0.8)');
        
        new Chart(batchStatusCtx, {
            type: 'doughnut',
            data: {
                labels: batchStatusData.map(b => b.status),
                datasets: [{
                    data: batchStatusData.map(b => b.count),
                    backgroundColor: colors,
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 4
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
                                size: 13,
                                weight: '500'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>
