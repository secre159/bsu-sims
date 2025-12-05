<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BSU-Bokod SIMS') }} - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen hero-bg flex items-center justify-center px-4 py-12 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 4s;"></div>
        </div>

        <div class="max-w-5xl w-full relative z-10">
            <!-- Header with Logo -->
            <div class="text-center mb-8">
                <!-- Logo Placeholder -->
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/10 backdrop-blur-sm rounded-full mb-4 border-2 border-white/20">
                    <span class="text-white/50 text-xs font-semibold">LOGO</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Benguet State University</h1>
                <p class="text-lg md:text-xl text-white/90 font-semibold">Bokod Campus</p>
                <p class="landing-muted mt-1 text-sm tracking-wide">Student Information Management System</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-3 bg-white/10 backdrop-blur-sm border border-white/20 text-white rounded-lg text-center max-w-md mx-auto text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Cards -->
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <!-- Admin Portal -->
                <div class="bg-white rounded-xl shadow-lg p-6 transition-all hover:shadow-xl">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-lg mb-3">
                            <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">Admin Portal</h2>
                        <p class="text-xs text-gray-500">Administrators & Staff</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="adminForm">
                        @csrf
                        <input type="hidden" name="login_type" value="admin">

                        @if ($errors->any())
                            <div class="mb-4 p-3 bg-danger-50 border border-danger-200 text-danger-700 rounded-lg text-sm">
                                @foreach ($errors->all() as $error)
                                    <div>• {{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="admin_email" class="block text-xs font-semibold text-gray-700 mb-1.5">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input id="admin_email" type="email" name="identifier" value="{{ old('identifier') }}" 
                                    class="input-primary block w-full pl-9 pr-3 py-2.5 text-sm" 
                                    placeholder="admin@bsu-bokod.edu.ph" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="admin_password" class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="admin_password" type="password" name="password" 
                                    class="input-primary block w-full pl-9 pr-3 py-2.5 text-sm" 
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-5">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 w-3.5 h-3.5">
                                <span class="ml-1.5 text-xs text-gray-600">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                            Sign In as Admin
                        </button>
                    </form>
                </div>

                <!-- Student Portal -->
                <div class="bg-white rounded-xl shadow-lg p-6 transition-all hover:shadow-xl">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-teal-100 rounded-lg mb-3">
                            <svg class="w-7 h-7 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">Student Portal</h2>
                        <p class="text-xs text-gray-500">Enrolled Students</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="studentForm">
                        @csrf
                        <input type="hidden" name="login_type" value="student">

                        <div class="mb-3">
                            <label for="student_id" class="block text-xs font-semibold text-gray-700 mb-1.5">Student ID</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                </div>
                                <input id="student_id" type="text" name="identifier" 
                                    class="input-primary block w-full pl-9 pr-3 py-2.5 text-sm" 
                                    placeholder="2024-0001" required>
                            </div>
                            <!-- <p class="mt-1 text-xs text-gray-500">Format: YYYY-XXXX</p> -->
                        </div>

                        <div class="mb-4">
                            <label for="student_password" class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="student_password" type="password" name="password" 
                                    class="input-primary block w-full pl-9 pr-3 py-2.5 text-sm" 
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-5">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 w-3.5 h-3.5">
                                <span class="ml-1.5 text-xs text-gray-600">Remember me</span>
                            </label>
                            <a href="#" class="text-xs text-teal-600 hover:text-teal-700 font-medium">
                                Need help?
                            </a>
                        </div>

                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                            Sign In as Student
                        </button>
                    </form>

                    <!-- <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                        <p class="text-xs text-gray-500">Default password: birthdate (MMDDYYYY)</p>
                    </div> -->
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 landing-muted text-xs">
                <p>&copy; {{ date('Y') }} Benguet State University - Bokod Campus. All rights reserved.</p>
                <p class="mt-1 text-white/70">For technical support, contact the IT Department</p>
            </div>
        </div>
    </div>
</body>
</html>
