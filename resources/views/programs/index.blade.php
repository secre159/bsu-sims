<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Programs Management') }}
            </h2>
            <a href="{{ route('programs.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Add New Program
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Program Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Students</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($programs as $program)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $program->code }}</td>
                                        <td class="px-6 py-4">{{ $program->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $program->department?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $program->students_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $program->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $program->is_active ? 'Offered' : 'Not Offered' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('programs.show', $program) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('programs.edit', $program) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            @if($program->students_count == 0)
                                                <form action="{{ route('programs.destroy', $program) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this program?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No programs found. Add programs using the button above.
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
</x-app-layout>
