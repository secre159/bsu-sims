<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BSU-Bokod Student Information Management System (SIMS)</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/bsu-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/bsu-logo.png') }}">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .animate-delay-100 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-200 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-300 { animation-delay: 0.3s; opacity: 0; }
        .animate-delay-400 { animation-delay: 0.4s; opacity: 0; }
    </style>
</head>
<body class="antialiased">
    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex flex-col">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1 flex flex-col justify-center w-full">
            <!-- Hero Content -->
            <div class="text-center mb-6">
                <!-- Logo -->
                <div class="mb-3 animate-fade-in-up">
                    @if(file_exists(public_path('images/bsu-logo.png')))
                        <img src="{{ asset('images/bsu-logo.png') }}" alt="BSU-Bokod Logo" class="w-32 h-32 mx-auto object-contain">
                    @else
                        <!-- Placeholder until logo is added -->
                        <div class="inline-flex items-center justify-center w-32 h-32 bg-white/10 backdrop-blur-sm rounded-2xl border-2 border-white/20 shadow-2xl">
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto mb-2 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <p class="text-xs text-white/40 font-semibold">BSU Logo</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Title -->
                <div class="animate-fade-in-up animate-delay-100 mb-4">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-2">
                        <span class="hero-title-gradient">BSU-Bokod SIMS</span>
                    </h1>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                        </svg>
                        <span class="text-white/90 text-sm font-medium">Student Information Management System</span>
                    </div>
                </div>
                
                <!-- University Name -->
                <p class="text-base sm:text-lg text-white/90 font-medium mb-4 animate-fade-in-up animate-delay-200">
                    Benguet State University - Bokod Campus
                </p>
            </div>

            <!-- System Features Badges -->
            <div class="flex flex-wrap justify-center gap-3 mb-6 animate-fade-in-up animate-delay-300">
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white/5 backdrop-blur-sm rounded-lg border border-white/10">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                    <span class="text-white/80 text-xs font-medium">Enrollment</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white/5 backdrop-blur-sm rounded-lg border border-white/10">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-white/80 text-xs font-medium">Grades</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white/5 backdrop-blur-sm rounded-lg border border-white/10">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-white/80 text-xs font-medium">Academic Records</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white/5 backdrop-blur-sm rounded-lg border border-white/10">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <span class="text-white/80 text-xs font-medium">Reports</span>
                </div>
            </div>

            <!-- Portal Cards Section -->
            <div class="text-center mb-5">
                <h2 class="text-xl sm:text-2xl font-bold text-white mb-1">Choose Your Portal</h2>
                <p class="landing-muted text-xs sm:text-sm">Access your account to manage or view student information</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto w-full">
                <!-- Admin Portal Card -->
                <a href="{{ route('admin.login') }}" class="portal-card group">
                    <div class="portal-top-indigo" aria-hidden="true"></div>
                    <div class="p-6">
                        <!-- Icon -->
                        <div class="flex justify-center mb-4">
                            <div class="portal-icon-indigo">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Title & Description -->
                        <h2 class="text-xl font-bold text-gray-900 mb-2 text-center">Admin Portal</h2>
                        <p class="text-gray-600 text-sm mb-4 text-center">Manage students, programs, and academic records</p>
                        
                        <!-- Features List -->
                        <ul class="space-y-1.5 mb-4 text-xs text-gray-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Student enrollment management
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Grade processing & approval
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Reports & analytics
                            </li>
                        </ul>
                        
                        <!-- CTA -->
                        <div class="text-center">
                            <span class="portal-cta-text text-indigo-600 group-hover:text-indigo-800 text-sm font-semibold">
                                Access Portal
                                <svg class="w-4 h-4 ml-1 inline-block transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Student Portal Card -->
                <a href="{{ route('student.login') }}" class="portal-card group">
                    <div class="portal-top-green" aria-hidden="true"></div>
                    <div class="p-6">
                        <!-- Icon -->
                        <div class="flex justify-center mb-4">
                            <div class="portal-icon-green">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Title & Description -->
                        <h2 class="text-xl font-bold text-gray-900 mb-2 text-center">Student Portal</h2>
                        <p class="text-gray-600 text-sm mb-4 text-center">View grades, enrollment status, and academic records</p>
                        
                        <!-- Features List -->
                        <ul class="space-y-1.5 mb-4 text-xs text-gray-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                View your current grades
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Check enrollment status
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Access academic records
                            </li>
                        </ul>
                        
                        <!-- CTA -->
                        <div class="text-center">
                            <span class="portal-cta-text text-green-600 group-hover:text-green-800 text-sm font-semibold">
                                Access Portal
                                <svg class="w-4 h-4 ml-1 inline-block transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-6 px-4 border-t border-white/10">
            <div class="max-w-7xl mx-auto">
                <!-- Contact Information -->
                <div class="max-w-md mx-auto text-center mb-6">
                    <h3 class="text-white font-semibold text-sm mb-3">Contact Information</h3>
                    <ul class="space-y-1 text-xs text-white/60">
                        <li>Bokod, Benguet</li>
                        <li>Philippines 2606</li>
                        <li class="mt-2 text-emerald-400">registrar@bsu-bokod.edu.ph</li>
                    </ul>
                </div>
                
                <!-- Copyright -->
                <div class="text-center pt-6 border-t border-white/10">
                    <p class="landing-muted text-xs">
                        &copy; {{ date('Y') }} Benguet State University - Bokod Campus. All rights reserved.
                    </p>
                    <p class="text-white/50 text-xs mt-1">
                        Student Information Management System v1.0
                    </p>
                </div>
            </div>
        </footer>
    </section>
</body>
</html>
