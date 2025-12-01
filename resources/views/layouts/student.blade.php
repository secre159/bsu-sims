<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Student Portal</title>

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
<nav class="bg-brand-deep shadow text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
<div class="text-2xl font-bold text-white">SIMS Student Portal</div>
                        </div>
                        <div class="flex items-center space-x-4">
<span class="text-white">{{ Auth::guard('student')->user()->student->first_name ?? 'Student' }}</span>
                            <form method="POST" action="{{ route('student.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Logout</button>
                            </form>
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
