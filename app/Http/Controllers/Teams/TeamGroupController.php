<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamGroupController extends Controller
{
    /**
     * Create a new group within a team.
     */
    public function store(Request $request, Team $team): RedirectResponse
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'code'            => ['required', 'string', 'max:100'],
            'permissions'     => ['nullable', 'array'],
            'permissions_raw' => ['nullable', 'string'],
        ]);

        // Support either array or comma-separated string for permissions
        if ($request->has('permissions_raw') && $request->permissions_raw) {
            $permissions = array_map('trim', explode(',', $request->permissions_raw));
        } else {
            $permissions = $request->input('permissions', []);
        }

        $team->addGroup($request->code, $permissions, $request->name);

        return back()->with('success', "Group '{$request->name}' created.");
    }

    /**
     * Update an existing group.
     */
    public function update(Request $request, Team $team, string $groupCode): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
        ]);

        $permissions = $request->input('permissions', []);

        $team->updateGroup($groupCode, $permissions, $request->name);

        return back()->with('success', "Group '{$request->name}' updated.");
    }

    /**
     * Delete a group from a team.
     */
    public function destroy(Team $team, string $groupCode): RedirectResponse
    {
        $team->deleteGroup($groupCode);

        return back()->with('success', 'Group deleted.');
    }

    /**
     * Add a user to a team group.
     */
    public function addUser(Request $request, Team $team, string $groupCode): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $group = $team->getGroup($groupCode);

        if (! $group) {
            return back()->with('error', 'Group not found.');
        }

        $user = User::findOrFail($request->user_id);

        // Attach user to group (global=false means team-specific group)
        $group->users()->syncWithoutDetaching([$user->id => ['global' => false]]);

        return back()->with('success', "{$user->name} added to group.");
    }

    /**
     * Remove a user from a team group.
     */
    public function removeUser(Request $request, Team $team, string $groupCode): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $group = $team->getGroup($groupCode);

        if (! $group) {
            return back()->with('error', 'Group not found.');
        }

        $user = User::findOrFail($request->user_id);
        $group->users()->detach($user->id);

        return back()->with('success', "{$user->name} removed from group.");
    }

    /**
     * Add a user to a global group (grants cross-team access).
     */
    public function addGlobalUser(Request $request, Team $team, string $groupCode): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $group = $team->getGroup($groupCode);

        if (! $group) {
            return back()->with('error', 'Group not found.');
        }

        $user = User::findOrFail($request->user_id);

        // global=true grants this user cross-team access via this group
        $group->users()->syncWithoutDetaching([$user->id => ['global' => true]]);

        return back()->with('success', "{$user->name} added to global group.");
    }
}
