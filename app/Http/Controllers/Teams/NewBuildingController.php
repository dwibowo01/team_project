<?php

namespace App\Http\Controllers\Teams;

use App\Enums\TeamName;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NewBuildingController extends BaseTeamController
{
    private function getTeam(): Team
    {
        return Team::where('type', TeamName::NewBuilding->value)->firstOrFail();
    }

    /**
     * New Building team dashboard.
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

        return view('teams.new-building.dashboard', compact('team', 'members', 'groups', 'roles', 'userRole', 'userGroups'));
    }

    /**
     * Manage new building projects.
     */
    public function projects(): View
    {
        $team = $this->getTeam();
        return view('teams.new-building.projects', compact('team'));
    }

    /**
     * Manage members of the new building team.
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

        return view('teams.new-building.members', compact('team', 'members', 'roles', 'groups'));
    }

    /**
     * Manage groups within the new building team.
     */
    public function groups(): View
    {
        $team   = $this->getTeam();
        $groups = $team->groups;

        return view('teams.new-building.groups', compact('team', 'groups'));
    }
}
