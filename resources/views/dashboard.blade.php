<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Welcome, ') }}{{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('Select a team dashboard to continue:') }}</p>

                    @if($teams->isEmpty())
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded p-4">
                            <p class="text-yellow-800 dark:text-yellow-300">{{ __('You are not assigned to any team yet. Please contact an administrator.') }}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($teams as $team)
                                <a href="{{ route($team->dashboardRoute()) }}"
                                   class="block p-6 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($team->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-indigo-900 dark:text-indigo-200">{{ $team->name }}</p>
                                            @if($team->description)
                                                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">{{ $team->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
