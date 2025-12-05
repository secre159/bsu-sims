<nav x-data="{ open: false }" class="hero-bg shadow-lg sticky top-0 z-50">
    <!-- Subtle overlay for better text contrast -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent pointer-events-none"></div>
    
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left: Logo & Brand -->
            <div class="shrink-0 flex items-center relative z-10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
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
                            Student System
                        </span>
                    </div>
                </a>
            </div>

            <!-- Right: Navigation Links + User Controls -->
            <div class="hidden sm:flex sm:items-center sm:gap-8">
                <!-- Navigation Links -->
                <div class="flex space-x-1 relative z-10">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->role === 'admin')
                        {{-- Admin Navigation --}}
                        <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                            {{ __('Students') }}
                        </x-nav-link>
                        <x-nav-link :href="route('programs.index')" :active="request()->routeIs('programs.*')">
                            {{ __('Programs') }}
                        </x-nav-link>
                        <x-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.*')">
                            {{ __('Subjects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('academic-years.index')" :active="request()->routeIs('academic-years.*')">
                            {{ __('Academic Years') }}
                        </x-nav-link>
                        {{-- Grade Approvals should appear immediately after Academic Years (Admin only) --}}
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.grade-approvals.index')" :active="request()->routeIs('admin.grade-approvals.*')">
                                {{ __('Grade Approvals') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                        <x-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.*')">
                            {{ __('Activity Log') }}
                        </x-nav-link>
                        <x-nav-link :href="route('archive.index')" :active="request()->routeIs('archive.*')">
                            {{ __('Archives') }}
                        </x-nav-link>
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('backups.index')" :active="request()->routeIs('backups.*')">
                                {{ __('Backups') }}
                            </x-nav-link>
                        @endif
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                {{ __('Users') }}
                            </x-nav-link>
                        @endif
                    @endif
                    
                    @if(Auth::user()->role === 'chairperson')
                        {{-- Chairperson Navigation --}}
                        <x-nav-link :href="route('chairperson.grades.index')" :active="request()->routeIs('chairperson.grades.*')">
                            {{ __('Grade Entry') }}
                        </x-nav-link>
                        <x-nav-link :href="route('chairperson.grade-import.create')" :active="request()->routeIs('chairperson.grade-import.*')">
                            {{ __('Import Grades') }}
                        </x-nav-link>
                        <x-nav-link :href="route('chairperson.grade-batches.index')" :active="request()->routeIs('chairperson.grade-batches.*')">
                            {{ __('My Batches') }}
                        </x-nav-link>
                        <x-nav-link :href="route('chairperson.reports.index')" :active="request()->routeIs('chairperson.reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endif
                </div>

                <!-- User Controls -->
                <div class="flex items-center gap-3 relative z-10">
                <!-- Role Badge -->
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-400 text-gray-900 shadow-lg">
                    @if(Auth::user()->role === 'admin')
                        👑 Admin
                    @elseif(Auth::user()->role === 'chairperson')
                        📋 Chairperson
                    @else
                        👤 Staff
                    @endif
                </span>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-white/30 text-sm leading-4 font-semibold rounded-lg text-white hover:bg-white/10 hover:border-emerald-400 focus:outline-none transition-all duration-150 backdrop-blur-sm bg-white/5">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="flex items-center sm:hidden relative z-10">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-white hover:text-emerald-300 hover:bg-white/10 focus:outline-none focus:bg-white/10 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/10 bg-black/20 backdrop-blur-lg">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->role === 'admin')
                {{-- Admin Navigation --}}
                <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                    {{ __('Students') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('programs.index')" :active="request()->routeIs('programs.*')">
                    {{ __('Programs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.*')">
                    {{ __('Subjects') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('academic-years.index')" :active="request()->routeIs('academic-years.*')">
                    {{ __('Academic Years') }}
                </x-responsive-nav-link>
                {{-- Grade Approvals after Academic Years (Admin only) --}}
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.grade-approvals.index')" :active="request()->routeIs('admin.grade-approvals.*')">
                        {{ __('Grade Approvals') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.*')">
                    {{ __('Activity Log') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('archive.index')" :active="request()->routeIs('archive.*')">
                    {{ __('Archives') }}
                </x-responsive-nav-link>
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('backups.index')" :active="request()->routeIs('backups.*')">
                        {{ __('Backups') }}
                    </x-responsive-nav-link>
                @endif
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                @endif
            @endif
            
            @if(Auth::user()->role === 'chairperson')
                {{-- Chairperson Navigation --}}
                <x-responsive-nav-link :href="route('chairperson.grades.index')" :active="request()->routeIs('chairperson.grades.*')">
                    {{ __('Grade Entry') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('chairperson.grade-import.create')" :active="request()->routeIs('chairperson.grade-import.*')">
                    {{ __('Import Grades') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('chairperson.grade-batches.index')" :active="request()->routeIs('chairperson.grade-batches.*')">
                    {{ __('My Batches') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('chairperson.reports.index')" :active="request()->routeIs('chairperson.reports.*')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/10">
            <div class="px-4 py-2">
                <div class="font-semibold text-sm text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-xs text-emerald-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
