<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Student Portal') - {{ config('app.name', 'BSU-Bokod SIMS') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/bsu-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/bsu-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        
        <!-- Toastify CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Student Navigation Bar -->
            <nav class="hero-bg shadow-lg sticky top-0 z-50">
                <!-- Subtle overlay for better text contrast -->
                <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent pointer-events-none"></div>
                
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Left: Logo & Brand -->
                        <div class="shrink-0 flex items-center relative z-10">
                            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 group">
                                @if(file_exists(public_path('images/bsu-logo.png')))
                                    <img src="{{ asset('images/bsu-logo.png') }}" alt="BSU-Bokod" class="w-10 h-10 object-contain drop-shadow-lg">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-400 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex flex-col">
                                    <span class="text-base font-bold text-white group-hover:text-emerald-300 transition-colors drop-shadow-lg leading-tight">
                                        BSU-Bokod
                                    </span>
                                    <span class="text-[10px] text-emerald-300 font-medium uppercase tracking-wider leading-tight">
                                        Student Portal
                                    </span>
                                </div>
                            </a>
                        </div>

                        <!-- Right: Navigation Links + User Controls -->
                        <div class="hidden sm:flex sm:items-center sm:gap-8">
                            <!-- Navigation Links -->
                            <div class="flex space-x-1 relative z-10">
                                <a href="{{ route('student.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/10 hover:text-emerald-300 transition-all {{ request()->routeIs('student.dashboard') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('student.profile') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/10 hover:text-emerald-300 transition-all {{ request()->routeIs('student.profile') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    Profile
                                </a>
                            </div>

                            <!-- User Controls -->
                            <div class="flex items-center gap-3 relative z-10">
                                <!-- Student Badge -->
                                <div class="hidden lg:block px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-400 text-gray-900 shadow-lg">
                                    🎓 Student
                                </div>
                                
                                <div class="hidden md:block text-right">
                                    <div class="text-sm font-semibold text-white drop-shadow">
                                        {{ Auth::guard('student')->user()->student->first_name ?? 'Student' }} {{ Auth::guard('student')->user()->student->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-emerald-300">
                                        {{ Auth::guard('student')->user()->student->student_id ?? '' }}
                                    </div>
                                </div>
                                
                                <form method="POST" action="{{ route('student.logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 border border-white/30 text-sm font-semibold rounded-lg text-white hover:bg-white/10 hover:border-emerald-400 focus:outline-none transition-all duration-150 backdrop-blur-sm bg-white/5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Mobile Hamburger -->
                        <div class="flex items-center sm:hidden relative z-10">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-white hover:text-emerald-300 hover:bg-white/10 focus:outline-none transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Toastify JS -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        
        <!-- Toast Messages -->
        @if(session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 4000,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #10b981, #059669)",
                stopOnFocus: true,
                className: "toast-success",
            }).showToast();
        </script>
        @endif

        @if(session('error'))
        <script>
            Toastify({
                text: "{{ session('error') }}",
                duration: 4000,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #ef4444, #dc2626)",
                stopOnFocus: true,
                className: "toast-error",
            }).showToast();
        </script>
        @endif

        @if(session('info'))
        <script>
            Toastify({
                text: "{{ session('info') }}",
                duration: 4000,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #3b82f6, #2563eb)",
                stopOnFocus: true,
                className: "toast-info",
            }).showToast();
        </script>
        @endif

        <!-- Scripts Stack -->
        @stack('scripts')
    </body>
</html>
