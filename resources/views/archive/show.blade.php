<x-app-layout>
    <x-slot name="title">Archived Students</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Archived Students') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $schoolYear }} - {{ $semester }}</p>
            </div>
            <a href="{{ route('archive.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                ← Back to Archives
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Archive Info -->
            <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 p-6 rounded-xl shadow-sm">
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-indigo-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-800">Archive Information</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $archivedStudents->total() }} students archived from {{ $schoolYear }} - {{ $semester }}</p>
                            @if($archivedStudents->first() && $archivedStudents->first()->archive_reason)
                                <p class="text-sm text-gray-600 mt-1"><strong>Reason:</strong> {{ $archivedStudents->first()->archive_reason }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        Archived: {{ $archivedStudents->first() ? $archivedStudents->first()->archived_at->format('M d, Y') : 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Archived Student Records</h3>
                        <span class="text-sm text-gray-500">Total: {{ $archivedStudents->total() }}</span>
                    </div>

                    @if($archivedStudents->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Student ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Program</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Year Level</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($archivedStudents as $archived)
                                        @php
                                            $student = (object) $archived->student_data;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $student->student_id ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if(isset($student->photo_path) && $student->photo_path)
                                                        <img src="{{ asset('storage/' . $student->photo_path) }}" 
                                                             alt="{{ $student->first_name ?? '' }}"
                                                             class="w-10 h-10 rounded-full object-cover mr-3">
                                                    @else
                                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center mr-3">
                                                            <span class="text-white font-semibold text-sm">
                                                                {{ substr($student->first_name ?? 'N', 0, 1) }}{{ substr($student->last_name ?? 'A', 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $student->first_name ?? '' }} {{ $student->middle_name ?? '' }} {{ $student->last_name ?? '' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $student->email ?? $student->email_address ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $archived->program->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $archived->year_level }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'Active' => 'bg-green-100 text-green-800',
                                                        'Inactive' => 'bg-gray-100 text-gray-800',
                                                        'Graduated' => 'bg-blue-100 text-blue-800',
                                                        'Suspended' => 'bg-red-100 text-red-800'
                                                    ];
                                                    $color = $statusColors[$archived->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                    {{ $archived->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <form action="{{ route('archive.restore', $archived->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to restore this student to active records?');">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                        </svg>
                                                        Restore
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $archivedStudents->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No Archived Students</h3>
                            <p class="text-gray-500">This archive is empty</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
