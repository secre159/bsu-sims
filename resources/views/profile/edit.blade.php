<x-app-layout>
    <x-slot name="title">Profile Settings</x-slot>

    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Premium Header with Gradient -->
            <div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-600 p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
                <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-white opacity-10"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-white/20 backdrop-blur-sm rounded-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white drop-shadow-lg">Profile Settings</h1>
                    </div>
                    <p class="text-cyan-100 ml-14">Manage your account information and security settings</p>
                </div>
            </div>

            <div class="max-w-4xl mx-auto space-y-6">
                <!-- Profile Information Card -->
                <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-cyan-600 opacity-0 group-hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
                    <div class="relative z-10 p-8">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 bg-blue-500 rounded-2xl blur-xl opacity-20"></div>
                                <div class="relative p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Password Card -->
                <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 opacity-0 group-hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
                    <div class="relative z-10 p-8">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 bg-indigo-500 rounded-2xl blur-xl opacity-20"></div>
                                <div class="relative p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account Card -->
                <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-red-100">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-pink-600 opacity-0 group-hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
                    <div class="relative z-10 p-8">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 bg-red-500 rounded-2xl blur-xl opacity-20"></div>
                                <div class="relative p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
