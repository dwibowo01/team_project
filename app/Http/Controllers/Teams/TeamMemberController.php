<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * Add a user to a team with a role.
     */
    public function store(Request $request, Team $team): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role'  => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($team->hasUser($user)) {
            return back()->with('error', 'User is already a member of this team.');
        }

        $team->addUser($user, $request->role);

        return back()->with('success', "User {$user->name} added to the team.");
    }

    /**
     * Update a team member's role.
     */
    public function update(Request $request, Team $team, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'string'],
        ]);

        $team->updateUser($user, $request->role);

        return back()->with('success', "Role updated for {$user->name}.");
    }

    /**
     * Remove a user from a team.
     */
    public function destroy(Team $team, User $user): RedirectResponse
    {
        $team->deleteUser($user);

        return back()->with('success', "{$user->name} has been removed from the team.");
    }
}
