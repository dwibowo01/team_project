<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                🏢 {{ __('Management Dashboard') }}
            </h2>
            <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                Super Admin
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 rounded p-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 rounded p-4">{{ session('error') }}</div>
            @endif

            {{-- Overview Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($teams as $team)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $team->name }}</h3>
                                <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full">
                                    {{ $team->users_count }} {{ Str::plural('member', $team->users_count) }}
                                </span>
                            </div>
                            @if($team->description)
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ $team->description }}</p>
                            @endif
                            <div class="flex gap-2">
                                <a href="{{ route('management.view-team', $team) }}"
                                   class="text-sm text-indigo-600 hover:underline">
                                    View Dashboard →
                                </a>
                                <a href="{{ route('management.team-members', $team) }}"
                                   class="text-sm text-gray-500 hover:underline ml-3">
                                    Manage Members
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Management Team Info --}}
            @if($managementTeam)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            Management Team Members
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Role</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($managementTeam->allUsers() as $member)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $member->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @php $role = $managementTeam->userRole($member); @endphp
                                                @if($role)
                                                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">
                                                        {{ $role->name ?? $role->code }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
