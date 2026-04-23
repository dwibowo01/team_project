<?php

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    /**
     * Accept the given invite (Jetstream team invitation).
     */
    public function inviteAccept(Request $request, int $invitationId): RedirectResponse
    {
        $invitation = TeamInvitation::findOrFail($invitationId);
        $team       = $invitation->team;

        $user = Auth::user();

        // Add the authenticated user to the team
        if (! $team->hasUser($user)) {
            $team->users()->attach($user->id, ['role' => $invitation->role]);
        }

        // Remove the invitation
        $invitation->delete();

        return redirect(route('dashboard'))->with(
            'status',
            __('Success! You have accepted the invitation to join the :team team.', ['team' => $team->name])
        );
    }
}
