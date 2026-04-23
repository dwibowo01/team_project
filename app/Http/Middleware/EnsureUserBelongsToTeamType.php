<?php

namespace App\Http\Middleware;

use App\Enums\TeamName;
use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserBelongsToTeamType
{
    /**
     * Handle an incoming request.
     *
     * Allows access if:
     *  - The user is a member of the Management team (super-admin), OR
     *  - The user is a member of the team whose type matches $teamType.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $teamType): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $requestedType = TeamName::from($teamType);

        // Management team members can access any team dashboard
        $inManagement = $user->allTeams()->contains(
            fn ($team) => $team->type === TeamName::Management
        );

        if ($inManagement) {
            return $next($request);
        }

        // Check if user belongs to the requested team type
        $inTeam = $user->allTeams()->contains(
            fn ($team) => $team->type === $requestedType
        );

        if ($inTeam) {
            return $next($request);
        }

        abort(403, 'You do not have access to this team.');
    }
}
