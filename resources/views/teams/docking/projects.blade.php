<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ⚓ Docking — Projects
            </h2>
            <a href="{{ route('docking.dashboard') }}" class="text-sm text-blue-600 hover:underline">← Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Docking Projects</h3>
                    <p class="text-gray-500">Project management for the Docking team. Add your project list here.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
