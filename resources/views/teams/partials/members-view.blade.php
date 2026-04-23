{{--
    Reusable members management partial.
    Required: $team, $members, $roles, $groups, $teamColor, $teamIcon, $backRoute
--}}
@php
    $color = $teamColor ?? 'indigo';
    $icon  = $teamIcon  ?? '👥';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $icon }} {{ $team->name }} — Members
            </h2>
            <a href="{{ $backRoute }}" class="text-sm text-{{ $color }}-600 hover:underline">← Dashboard</a>
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

            {{-- Add Member --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Add Member</h3>
                    <form method="POST" action="{{ route('teams.members.store', $team) }}" class="flex gap-3 flex-wrap">
                        @csrf
                        <input type="email" name="email" placeholder="User email"
                               class="flex-1 min-w-48 border-gray-300 rounded-md shadow-sm focus:ring-{{ $color }}-500 focus:border-{{ $color }}-500 text-sm"
                               required />
                        <select name="role" class="border-gray-300 rounded-md shadow-sm text-sm">
                            @foreach($roles as $role)
                                <option value="{{ $role->code }}">{{ $role->name ?? $role->code }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-{{ $color }}-600 text-white text-sm px-4 py-2 rounded hover:bg-{{ $color }}-700">
                            Add Member
                        </button>
                    </form>
                </div>
            </div>

            {{-- Members Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">
                        Current Members ({{ $members->count() }})
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Groups</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($members as $member)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $member->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $member->email }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <form method="POST" action="{{ route('teams.members.update', [$team, $member]) }}"
                                                  class="flex items-center gap-2">
                                                @csrf @method('PATCH')
                                                <select name="role" class="border-gray-300 rounded text-xs py-1">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->code }}"
                                                            {{ optional($member->team_role)->code === $role->code ? 'selected' : '' }}>
                                                            {{ $role->name ?? $role->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="text-xs text-{{ $color }}-600 hover:underline">Update</button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex flex-wrap gap-1">
                                                @if(isset($member->team_groups))
                                                    @foreach($member->team_groups as $grp)
                                                        <span class="bg-{{ $color }}-100 text-{{ $color }}-700 text-xs px-2 py-0.5 rounded-full">
                                                            {{ $grp->name ?? $grp->code }}
                                                            @if($grp->pivot->global ?? false)
                                                                <span class="text-gray-400">(g)</span>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <form method="POST" action="{{ route('teams.members.destroy', [$team, $member]) }}"
                                                  onsubmit="return confirm('Remove {{ $member->name }} from team?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:underline">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500 text-sm">No members yet.</td>
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
