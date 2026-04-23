<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;

abstract class BaseTeamController extends Controller
{
    /**
     * Safely resolve a user's role within a team.
     *
     * The jurager/teams package uses `===` (object identity) to detect if the given
     * user is the team owner, which fails when two separate Eloquent instances represent
     * the same user (e.g. Auth::user() vs $team->owner loaded via relationship).
     * We work around this by passing the team's own eager-loaded owner object when the
     * authenticated user's ID matches the team's owner ID.
     */
    protected function resolveUserRole(Team $team, object $user): ?object
    {
        $subject = ($user->id === $team->user_id) ? $team->owner : $user;
        return $team->userRole($subject);
    }
}
