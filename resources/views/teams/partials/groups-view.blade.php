{{--
    Reusable groups management partial.
    Required: $team, $groups, $teamColor, $teamIcon, $backRoute
--}}
@php
    $color = $teamColor ?? 'indigo';
    $icon  = $teamIcon  ?? '🗂';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ $icon }} {{ $team->name }} — Groups
            </h2>
            <a href="{{ $backRoute }}" class="text-sm text-{{ $color }}-600 hover:underline">← Dashboard</a>
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

            {{-- Create Group --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Create New Group</h3>
                    <form method="POST" action="{{ route('teams.groups.store', $team) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Group Name</label>
                                <input type="text" name="name" placeholder="e.g. Team Leads"
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Group Code</label>
                                <input type="text" name="code" placeholder="e.g. leads"
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm" required />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Permissions <span class="text-gray-400 text-xs">(comma-separated, e.g. projects.view,projects.edit)</span>
                            </label>
                            <input type="text" name="permissions_raw" placeholder="projects.view, projects.edit"
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm" />
                            <p class="text-xs text-gray-400 mt-1">Note: Use wildcard <code>projects.*</code> to grant all project permissions.</p>
                        </div>
                        <button type="submit" class="bg-{{ $color }}-600 text-white text-sm px-4 py-2 rounded hover:bg-{{ $color }}-700">
                            Create Group
                        </button>
                    </form>
                </div>
            </div>

            {{-- Groups List --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        Groups ({{ $groups->count() }})
                    </h3>
                    @forelse($groups as $group)
                        <div class="border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $group->name ?? $group->code }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">Code: <code>{{ $group->code }}</code></p>
                                </div>
                                <form method="POST" action="{{ route('teams.groups.destroy', [$team, $group->code]) }}"
                                      onsubmit="return confirm('Delete group {{ $group->name ?? $group->code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>

                            {{-- Permissions --}}
                            @if($group->permissions && $group->permissions->isNotEmpty())
                                <div class="mt-3">
                                    <p class="text-xs font-medium text-gray-600 mb-1">Permissions:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($group->permissions as $perm)
                                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded">
                                                {{ $perm->name ?? $perm->code ?? $perm }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Group Members --}}
                            @if($group->users && $group->users->isNotEmpty())
                                <div class="mt-3">
                                    <p class="text-xs font-medium text-gray-600 mb-1">Members:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($group->users as $gUser)
                                            <span class="bg-{{ $color }}-50 text-{{ $color }}-700 text-xs px-2 py-0.5 rounded-full">
                                                {{ $gUser->name }}
                                                @if($gUser->pivot->global ?? false)
                                                    <span class="text-gray-400">(global)</span>
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Add User to Group --}}
                            <div class="mt-3 flex gap-2">
                                <form method="POST" action="{{ route('teams.groups.users.store', [$team, $group->code]) }}"
                                      class="flex gap-2">
                                    @csrf
                                    <input type="number" name="user_id" placeholder="User ID"
                                           class="w-28 border-gray-300 rounded text-xs py-1" />
                                    <button type="submit" class="text-xs text-{{ $color }}-600 border border-{{ $color }}-300 px-2 py-1 rounded hover:bg-{{ $color }}-50">
                                        + Add Member
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('teams.groups.global-users.store', [$team, $group->code]) }}"
                                      class="flex gap-2">
                                    @csrf
                                    <input type="number" name="user_id" placeholder="User ID"
                                           class="w-28 border-gray-300 rounded text-xs py-1" />
                                    <button type="submit" class="text-xs text-orange-600 border border-orange-300 px-2 py-1 rounded hover:bg-orange-50">
                                        + Add Global
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No groups yet. Create one above.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
