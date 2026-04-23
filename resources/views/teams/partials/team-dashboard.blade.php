{{--
    Reusable team dashboard partial.
    Required variables: $team, $members, $groups, $roles, $userRole, $userGroups
    $teamColor: Tailwind color prefix (e.g. 'blue', 'green', 'orange')
    $teamIcon: emoji icon
    $projectsRoute, $membersRoute, $groupsRoute: route names
--}}
@php
    $color = $teamColor ?? 'indigo';
    $icon  = $teamIcon  ?? '🏗';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $icon }} {{ $team->name }} Dashboard
            </h2>
            @if($userRole)
                <span class="bg-{{ $color }}-100 text-{{ $color }}-800 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $userRole->name ?? $userRole->code }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded p-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded p-4">{{ session('error') }}</div>
            @endif

            {{-- Quick Nav --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ $projectsRoute }}"
                   class="block p-4 bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-lg hover:bg-{{ $color }}-100 transition text-center">
                    <p class="text-2xl mb-1">📋</p>
                    <p class="text-sm font-medium text-{{ $color }}-800">Projects</p>
                </a>
                <a href="{{ $membersRoute }}"
                   class="block p-4 bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-lg hover:bg-{{ $color }}-100 transition text-center">
                    <p class="text-2xl mb-1">👥</p>
                    <p class="text-sm font-medium text-{{ $color }}-800">
                        Members <span class="text-xs">({{ $members->count() }})</span>
                    </p>
                </a>
                <a href="{{ $groupsRoute }}"
                   class="block p-4 bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-lg hover:bg-{{ $color }}-100 transition text-center">
                    <p class="text-2xl mb-1">🗂</p>
                    <p class="text-sm font-medium text-{{ $color }}-800">
                        Groups <span class="text-xs">({{ $groups->count() }})</span>
                    </p>
                </a>
                <div class="block p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                    <p class="text-2xl mb-1">🔑</p>
                    <p class="text-sm font-medium text-gray-600">
                        Roles <span class="text-xs">({{ $roles->count() }})</span>
                    </p>
                </div>
            </div>

            {{-- My Groups --}}
            @if($userGroups->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-gray-800 mb-3">My Groups</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($userGroups as $group)
                                <span class="bg-{{ $color }}-100 text-{{ $color }}-700 text-sm px-3 py-1 rounded-full">
                                    {{ $group->name ?? $group->code }}
                                    @if($group->pivot->global ?? false)
                                        <span class="text-xs ml-1 text-gray-500">(global)</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Recent Members --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-800">Team Members</h3>
                        <a href="{{ $membersRoute }}" class="text-sm text-{{ $color }}-600 hover:underline">View all →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($members->take(5) as $member)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $member->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $member->email }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @php $role = $team->userRole($member); @endphp
                                            @if($role)
                                                <span class="bg-{{ $color }}-100 text-{{ $color }}-700 text-xs px-2 py-1 rounded">
                                                    {{ $role->name ?? $role->code }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-center text-gray-500 text-sm">No members yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
