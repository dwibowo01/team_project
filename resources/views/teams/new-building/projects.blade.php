<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                🏗 New Building — Projects
            </h2>
            <a href="{{ route('new-building.dashboard') }}" class="text-sm text-green-600 hover:underline">← Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">New Building Projects</h3>
                    <p class="text-gray-500 dark:text-gray-400">Project management for the New Building team. Add your project list here.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
