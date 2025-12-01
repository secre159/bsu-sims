<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Add New User
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

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search and Filter Section -->
                    <form method="GET" action="{{ route('users.index') }}" class="mb-6">
                        <div class="flex gap-3 flex-wrap">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by name or email..." 
                                   class="border rounded px-3 py-2 flex-1 min-w-[200px]">
                            <select name="role" class="border rounded px-3 py-2">
                                <option value="">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="department_id" class="border rounded px-3 py-2">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-brand-medium hover:bg-brand-deep text-white px-4 py-2 rounded">
                                Filter
                            </button>
                            <a href="{{ route('users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                                Clear
                            </a>
                        </div>
                    </form>

                    <p class="text-sm text-gray-500 mb-4">Found: {{ $users->count() }} users</p>

                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-brand-deep to-brand-medium text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr class="{{ $user->id === auth()->id() ? 'bg-blue-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="text-xs text-blue-600">(You)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($user->role == 'admin') bg-red-100 text-red-800
                                                @elseif($user->role == 'chairperson') bg-purple-100 text-purple-800
                                                @elseif($user->role == 'approver') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($user->role ?? 'user') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $user->department?->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No users found.
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
