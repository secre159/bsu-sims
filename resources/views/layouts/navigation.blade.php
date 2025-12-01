<nav x-data="{ open: false }" class="bg-brand-deep border-b border-brand-deep shadow-sm text-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
<x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(in_array(Auth::user()->role, ['admin', 'approver', 'user', null]))
                        {{-- Admin/Staff Navigation --}}
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
                        @if(in_array(Auth::user()->role, ['admin', null]))
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
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
<button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:bg-white\/10 focus:outline-none transition ease-in-out duration-150">
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

            <!-- Hamburger -->
<div class="-me-2 flex items-center sm:hidden text-white">
<button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-brand-deep">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(in_array(Auth::user()->role, ['admin', 'approver', 'user', null]))
                {{-- Admin/Staff Navigation --}}
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
                @if(in_array(Auth::user()->role, ['admin', null]))
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
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-brand-light">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-white/70">{{ Auth::user()->email }}</div>
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
