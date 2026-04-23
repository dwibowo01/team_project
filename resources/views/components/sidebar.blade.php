{{--
    Shrinkable sidebar component.
    Collapse state is persisted in localStorage via Alpine's $persist plugin.
--}}
<div x-data="{
        collapsed: Alpine.$persist(false).as('sidebar_collapsed'),
        teamMenuOpen: false
     }"
     :class="collapsed ? 'w-16' : 'w-64'"
     class="flex flex-col h-full bg-gray-900 dark:bg-gray-950 text-white transition-all duration-300 ease-in-out flex-shrink-0">

    {{-- Sidebar Header / Toggle --}}
    <div class="flex items-center h-16 px-3 border-b border-gray-700 dark:border-gray-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0 flex-1">
            <div class="w-8 h-8 flex-shrink-0 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span x-show="!collapsed" x-transition.opacity class="text-sm font-semibold truncate">
                {{ config('app.name') }}
            </span>
        </a>
        <button @click="collapsed = !collapsed"
                class="ml-auto p-1.5 rounded text-gray-400 hover:text-white hover:bg-gray-700 transition flex-shrink-0"
                :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
            <svg x-show="!collapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <svg x-show="collapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Team Switcher --}}
    @auth
        @php $currentTeam = Auth::user()->currentTeam; @endphp
        <div class="px-3 py-3 border-b border-gray-700 dark:border-gray-800">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="w-full flex items-center gap-2 px-2 py-2 rounded-lg text-left hover:bg-gray-700 transition text-sm">
                    <div class="w-7 h-7 flex-shrink-0 rounded bg-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                        {{ $currentTeam ? strtoupper(substr($currentTeam->name, 0, 1)) : '?' }}
                    </div>
                    <div x-show="!collapsed" x-transition.opacity class="flex-1 min-w-0">
                        <p class="text-xs text-gray-400 leading-none">Current Team</p>
                        <p class="font-medium text-white truncate mt-0.5">
                            {{ $currentTeam?->name ?? 'No team' }}
                        </p>
                    </div>
                    <svg x-show="!collapsed" class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                    </svg>
                </button>

                {{-- Teams dropdown --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     @click.outside="open = false"
                     :class="collapsed ? 'left-12' : 'left-0 right-0'"
                     class="absolute top-full mt-1 bg-gray-800 rounded-lg shadow-xl ring-1 ring-black ring-opacity-30 z-50 overflow-hidden"
                     style="display: none;">
                    <div class="px-3 py-2 text-xs text-gray-400 font-medium border-b border-gray-700">Switch Team</div>
                    @foreach(Auth::user()->allTeams() as $team)
                        <form method="POST" action="{{ route('current-team.update') }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-700 transition text-left
                                           {{ $currentTeam?->id === $team->id ? 'text-indigo-400' : 'text-gray-200' }}">
                                <div class="w-6 h-6 flex-shrink-0 rounded bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($team->name, 0, 1)) }}
                                </div>
                                <span class="truncate">{{ $team->name }}</span>
                                @if($currentTeam?->id === $team->id)
                                    <svg class="w-3 h-3 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    @endauth

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        {{-- Dashboard --}}
        <x-sidebar-nav-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" :collapsed="false">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </x-slot>
            Dashboard
        </x-sidebar-nav-item>

        @auth
            @php
                $userTeams = Auth::user()->allTeams();
                $isManagement = $userTeams->contains(fn($t) => $t->type === \App\Enums\TeamName::Management);
            @endphp

            {{-- Management section --}}
            @if($isManagement)
                <div x-show="!collapsed" class="pt-3 pb-1">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Management</p>
                </div>
                <x-sidebar-nav-item href="{{ route('management.dashboard') }}" :active="request()->routeIs('management.*')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </x-slot>
                    Management
                </x-sidebar-nav-item>
            @endif

            {{-- Docking section --}}
            @if($isManagement || $userTeams->contains(fn($t) => $t->type === \App\Enums\TeamName::Docking))
                <div x-show="!collapsed" class="pt-3 pb-1">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Docking</p>
                </div>
                <x-sidebar-nav-item href="{{ route('docking.dashboard') }}" :active="request()->routeIs('docking.dashboard')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                        </svg>
                    </x-slot>
                    Dashboard
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('docking.members') }}" :active="request()->routeIs('docking.members')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </x-slot>
                    Members
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('docking.groups') }}" :active="request()->routeIs('docking.groups')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </x-slot>
                    Groups
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('docking.projects') }}" :active="request()->routeIs('docking.projects')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </x-slot>
                    Projects
                </x-sidebar-nav-item>
            @endif

            {{-- New Building section --}}
            @if($isManagement || $userTeams->contains(fn($t) => $t->type === \App\Enums\TeamName::NewBuilding))
                <div x-show="!collapsed" class="pt-3 pb-1">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">New Building</p>
                </div>
                <x-sidebar-nav-item href="{{ route('new-building.dashboard') }}" :active="request()->routeIs('new-building.dashboard')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </x-slot>
                    Dashboard
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('new-building.members') }}" :active="request()->routeIs('new-building.members')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </x-slot>
                    Members
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('new-building.groups') }}" :active="request()->routeIs('new-building.groups')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </x-slot>
                    Groups
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('new-building.projects') }}" :active="request()->routeIs('new-building.projects')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </x-slot>
                    Projects
                </x-sidebar-nav-item>
            @endif

            {{-- Site section --}}
            @if($isManagement || $userTeams->contains(fn($t) => $t->type === \App\Enums\TeamName::Site))
                <div x-show="!collapsed" class="pt-3 pb-1">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Site</p>
                </div>
                <x-sidebar-nav-item href="{{ route('site.dashboard') }}" :active="request()->routeIs('site.dashboard')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </x-slot>
                    Dashboard
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('site.members') }}" :active="request()->routeIs('site.members')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </x-slot>
                    Members
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('site.groups') }}" :active="request()->routeIs('site.groups')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </x-slot>
                    Groups
                </x-sidebar-nav-item>
                <x-sidebar-nav-item href="{{ route('site.projects') }}" :active="request()->routeIs('site.projects')">
                    <x-slot name="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </x-slot>
                    Projects
                </x-sidebar-nav-item>
            @endif
        @endauth

    </nav>

    {{-- Sidebar Footer --}}
    <div class="p-3 border-t border-gray-700 dark:border-gray-800">
        @auth
        <a href="{{ route('profile.show') }}"
           class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-gray-700 transition text-sm text-gray-300">
            <img src="{{ Auth::user()->profile_photo_url }}"
                 alt="{{ Auth::user()->name }}"
                 class="w-7 h-7 rounded-full object-cover flex-shrink-0">
            <div x-show="!collapsed" x-transition.opacity class="min-w-0 flex-1">
                <p class="text-white font-medium truncate text-xs">{{ Auth::user()->name }}</p>
                <p class="text-gray-400 truncate text-xs">{{ Auth::user()->email }}</p>
            </div>
        </a>
        @endauth
    </div>
</div>
