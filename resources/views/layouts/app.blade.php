<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <x-banner />

        {{-- Full-height flex layout: sidebar + content --}}
        <div class="flex h-screen overflow-hidden">

            {{-- Sidebar --}}
            <x-sidebar />

            {{-- Main area: topnav + page content --}}
            <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

                {{-- Top Navigation Bar --}}
                <header class="flex-shrink-0 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center px-4 sm:px-6 gap-4">

                    {{-- Page Title / Breadcrumb slot --}}
                    <div class="flex-1 min-w-0">
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>

                    {{-- Right actions --}}
                    <div class="flex items-center gap-2">

                        {{-- Theme Switcher --}}
                        <x-theme-switcher />

                        {{-- Notification Bell --}}
                        @auth
                            @livewire('notification-bell')
                        @endauth

                        {{-- User Profile Dropdown --}}
                        @auth
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <img src="{{ Auth::user()->profile_photo_url }}"
                                         alt="{{ Auth::user()->name }}"
                                         class="w-8 h-8 rounded-full object-cover">
                                    <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-200">
                                        {{ Auth::user()->name }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     @click.outside="open = false"
                                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                     style="display: none;">
                                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <a href="{{ route('profile.show') }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Profile
                                        </a>
                                    </div>
                                    <div class="py-1 border-t border-gray-100 dark:border-gray-700">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-900">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
