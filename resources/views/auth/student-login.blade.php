<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Login - {{ config('app.name', 'BSU-Bokod SIMS') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/bsu-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/bsu-logo.png') }}">
    
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
        </div>

        <div class="max-w-md w-full relative z-10">
            <!-- Back to Home -->
            <a href="{{ url('/') }}" class="inline-flex items-center text-white/80 hover:text-white mb-6 text-sm transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Home
            </a>

            <!-- Header -->
            <div class="text-center mb-8">
                @if(file_exists(public_path('images/bsu-logo.png')))
                    <img src="{{ asset('images/bsu-logo.png') }}" alt="BSU-Bokod Logo" class="w-24 h-24 mx-auto mb-4 object-contain">
                @else
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-full mb-4 border-2 border-white/20">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                @endif
                <h1 class="text-3xl font-bold text-white mb-2">Student Portal</h1>
                <p class="landing-muted">Access your academic information</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-3 bg-white/10 backdrop-blur-sm border border-white/20 text-white rounded-lg text-center text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Card -->
            <div class="bg-white rounded-xl shadow-2xl p-8">
                <form method="POST" action="{{ route('student.login.post') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-danger-50 border border-danger-200 text-danger-700 rounded-lg text-sm">
                            @foreach ($errors->all() as $error)
                                <div>• {{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="student_id" class="block text-sm font-semibold text-gray-700 mb-2">Student ID</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                            </div>
                            <input id="student_id" type="text" name="student_id" value="{{ old('student_id') }}" 
                                class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 placeholder-gray-400 bg-white" 
                                placeholder="2024-0001" required autofocus>
                        </div>
                        <!-- <p class="mt-2 text-xs text-gray-500">Format: YYYY-XXXX</p> -->
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" 
                                class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 placeholder-gray-400 bg-white" 
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-success focus:ring-success w-4 h-4">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-success hover:text-emerald-700 font-medium">
                            Need help?
                        </a>
                    </div>

                    <button type="submit" class="w-full bg-success hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition-colors shadow-lg shadow-emerald-500/30">
                        Sign In
                    </button>
                </form>

                <!-- <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-xs text-blue-800 font-medium mb-1">Default Password</p>
                        <p class="text-xs text-blue-700">Your birthdate in MMDDYYYY format</p>
                        <p class="text-xs text-blue-600 mt-1 italic">Example: If born on Jan 15, 2000, use 01152000</p>
                    </div>
                </div> -->
            </div>

            <!-- Footer -->
            <div class="text-center mt-6 landing-muted text-xs">
                <p>&copy; {{ date('Y') }} BSU-Bokod. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
