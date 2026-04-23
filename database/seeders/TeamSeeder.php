<?php

namespace Database\Seeders;

use App\Enums\TeamName;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeamSeeder extends Seeder
{
    /**
     * Seed the four fixed teams with default roles.
     */
    public function run(): void
    {
        // Create a system super-admin user (owner of all teams)
        $owner = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        foreach (TeamName::cases() as $teamName) {
            // Only create the team if it doesn't already exist
            $team = Team::firstOrCreate(
                ['type' => $teamName->value],
                [
                    'name'        => $teamName->value,
                    'user_id'     => $owner->id,
                    'description' => $this->description($teamName),
                ]
            );

            // Only create roles once (check if admin role already exists)
            if (! $team->hasRole('admin')) {
                $team->addRole('admin', $this->adminPermissions($teamName));
                $team->addRole('member', $this->memberPermissions($teamName));
            }

            // The team owner is automatically in the team; add non-owner test members if needed

            // For non-Management teams, seed default groups
            if ($teamName->supportsGroups()) {
                $this->seedGroups($team);
            }
        }

        // Update owner's current_team_id to Management
        $managementTeam = Team::where('type', TeamName::Management->value)->first();
        $owner->forceFill(['current_team_id' => $managementTeam->id])->save();
    }

    /**
     * Seed demo groups for a project team.
     */
    private function seedGroups(Team $team): void
    {
        if (! $team->getGroup('support')) {
            $team->addGroup('support', ['projects.view', 'projects.comment'], 'Support');
        }

        if (! $team->getGroup('leads')) {
            $team->addGroup('leads', ['projects.*', 'members.view'], 'Team Leads');
        }
    }

    private function description(TeamName $teamName): string
    {
        return match ($teamName) {
            TeamName::Management  => 'Management & super-admin team with full visibility.',
            TeamName::Docking     => 'Handles all docking projects and operations.',
            TeamName::NewBuilding => 'Manages new building construction projects.',
            TeamName::Site        => 'Responsible for site management activities.',
        };
    }

    private function adminPermissions(TeamName $teamName): array
    {
        $base = ['team.edit', 'members.*', 'groups.*', 'roles.*'];

        return match ($teamName) {
            TeamName::Management  => array_merge($base, ['teams.*', 'dashboard.*']),
            TeamName::Docking     => array_merge($base, ['projects.*', 'docking.*']),
            TeamName::NewBuilding => array_merge($base, ['projects.*', 'new-building.*']),
            TeamName::Site        => array_merge($base, ['projects.*', 'site.*']),
        };
    }

    private function memberPermissions(TeamName $teamName): array
    {
        return match ($teamName) {
            TeamName::Management  => ['dashboard.view'],
            TeamName::Docking     => ['projects.view', 'projects.add', 'docking.view'],
            TeamName::NewBuilding => ['projects.view', 'projects.add', 'new-building.view'],
            TeamName::Site        => ['projects.view', 'projects.add', 'site.view'],
        };
    }
}
