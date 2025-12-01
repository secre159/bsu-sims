<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Available Reports</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Student Master List -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold mb-2">Student Master List</h4>
                            <p class="text-sm text-gray-600 mb-3">Complete list of all students</p>
                            <a href="{{ route('reports.students') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-block">
                                View Report
                            </a>
                        </div>

                        <!-- Program-wise List -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold mb-2">Students by Program</h4>
                            <p class="text-sm text-gray-600 mb-3">Students grouped by programs</p>
                            <a href="{{ route('reports.programs') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-block">
                                View Report
                            </a>
                        </div>

                        <!-- Year Level Report -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold mb-2">Students by Year Level</h4>
                            <p class="text-sm text-gray-600 mb-3">Students grouped by year level</p>
                            <a href="{{ route('reports.year-levels') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-block">
                                View Report
                            </a>
                        </div>

                        <!-- Export Options -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold mb-2">Export Data</h4>
                            <p class="text-sm text-gray-600 mb-3">Export student data to CSV (Excel compatible)</p>
                            <a href="{{ route('reports.export-students') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded inline-block">
                                Download CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
