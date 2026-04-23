<?php

namespace App\Http\Controllers\Teams;

use App\Enums\TeamName;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SiteController extends BaseTeamController
{
    private function getTeam(): Team
    {
        return Team::where('type', TeamName::Site->value)->firstOrFail();
    }

    /**
     * Site team dashboard.
     */
    public function index(): View
    {
        $team = $this->getTeam();
        $user = Auth::user();

        $members    = $team->allUsers();
        $groups     = $team->groups;
        $roles      = $team->roles;
        $userRole   = $this->resolveUserRole($team, $user);
        $userGroups = $user->groups()->where('team_id', $team->id)->get();

        return view('teams.site.dashboard', compact('team', 'members', 'groups', 'roles', 'userRole', 'userGroups'));
    }

    /**
     * Manage site management tasks.
     */
    public function projects(): View
    {
        $team = $this->getTeam();
        return view('teams.site.projects', compact('team'));
    }

    /**
     * Manage members of the site team.
     */
    public function members(): View
    {
        $team    = $this->getTeam();
        $members = $team->allUsers()->map(function ($user) use ($team) {
            $user->team_role   = $this->resolveUserRole($team, $user);
            $user->team_groups = $user->groups()->where('team_id', $team->id)->get();
            return $user;
        });
        $roles  = $team->roles;
        $groups = $team->groups;

        return view('teams.site.members', compact('team', 'members', 'roles', 'groups'));
    }

    /**
     * Manage groups within the site team.
     */
    public function groups(): View
    {
        $team   = $this->getTeam();
        $groups = $team->groups;

        return view('teams.site.groups', compact('team', 'groups'));
    }
}
