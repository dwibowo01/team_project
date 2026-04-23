<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👁 {{ $team->name }} — Team Overview
            </h2>
            <a href="{{ route('management.dashboard') }}" class="text-sm text-indigo-600 hover:underline">
                ← Back to Management
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Members --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Members ({{ $members->count() }})</h3>
                        <a href="{{ route('management.team-members', $team) }}"
                           class="text-sm text-indigo-600 hover:underline">Manage →</a>
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
                                @forelse($members as $member)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $member->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $member->email }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @php $role = $team->userRole($member); @endphp
                                            @if($role)
                                                <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded">
                                                    {{ $role->name ?? $role->code }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">—</span>
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

            {{-- Roles --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Roles ({{ $roles->count() }})</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($roles as $role)
                            <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">
                                {{ $role->name ?? $role->code }}
                            </span>
                        @empty
                            <p class="text-gray-500 text-sm">No roles defined.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Groups --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Groups ({{ $groups->count() }})</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($groups as $group)
                            <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full">
                                {{ $group->name ?? $group->code }}
                            </span>
                        @empty
                            <p class="text-gray-500 text-sm">No groups defined.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
