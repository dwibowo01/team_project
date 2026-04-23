<?php

namespace App\Http\Controllers\Teams;

use App\Enums\TeamName;
use App\Models\Team;
use Illuminate\View\View;

class ManagementController extends BaseTeamController
{
    /**
     * Management team dashboard – superadmin view of all teams.
     */
    public function index(): View
    {
        $teams = Team::whereNotNull('type')
            ->where('type', '!=', TeamName::Management->value)
            ->withCount('users')
            ->get();

        $managementTeam = Team::where('type', TeamName::Management->value)->first();

        return view('teams.management.dashboard', compact('teams', 'managementTeam'));
    }

    /**
     * View another team's dashboard (management privilege).
     */
    public function viewTeam(Team $team): View
    {
        $members = $team->allUsers();
        $roles   = $team->roles;
        $groups  = $team->groups;

        return view('teams.management.view-team', compact('team', 'members', 'roles', 'groups'));
    }

    /**
     * Manage team members and roles for any team.
     */
    public function members(Team $team): View
    {
        $members = $team->allUsers()->map(function ($user) use ($team) {
            $user->team_role = $this->resolveUserRole($team, $user);
            return $user;
        });

        $roles = $team->roles;

        return view('teams.management.members', compact('team', 'members', 'roles'));
    }
}
