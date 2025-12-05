<x-app-layout>
    <x-slot name="title">Edit User</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit User') }}: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if($user->id === auth()->id())
                        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                            <strong>Note:</strong> You are editing your own account.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-medium focus:border-brand-medium">
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-medium focus:border-brand-medium">
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                New Password <span class="text-gray-400">(leave blank to keep current)</span>
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-medium focus:border-brand-medium">
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-medium focus:border-brand-medium">
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="role" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-medium focus:border-brand-medium">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                <strong>Admin:</strong> Full system access |
                                <strong>Chairperson:</strong> Grade management for assigned department
                            </p>
                        </div>

                        <!-- Department -->
                        <div class="mb-6" id="departmentField">
                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Department
                                <span class="text-red-500" id="deptRequired">*</span>
                            </label>
                            <select name="department_id" id="department_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-medium focus:border-brand-medium">
                                <option value="">— No Department —</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Required for Chairperson role.</p>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            const deptRequired = document.getElementById('deptRequired');
            const deptSelect = document.getElementById('department_id');
            
            if (this.value === 'chairperson') {
                deptRequired.style.display = 'inline';
                deptSelect.required = true;
            } else {
                deptRequired.style.display = 'none';
                deptSelect.required = false;
            }
        });
        
        // Trigger on page load
        document.getElementById('role').dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
