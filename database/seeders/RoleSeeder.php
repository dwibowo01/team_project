<?php

namespace Database\Seeders;

use App\Enums\TeamName;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed Spatie roles scoped per team.
     * Roles: admin, member, viewer — created for each team type.
     */
    public function run(): void
    {
        foreach (TeamName::cases() as $teamName) {
            $team = Team::where('type', $teamName->value)->first();

            if (! $team) {
                continue;
            }

            setPermissionsTeamId($team->id);

            foreach ($this->rolesForTeam($teamName) as $roleName => $permissions) {
                $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
                $role->syncPermissions($permissions);
            }
        }

        // Reset to no team context
        setPermissionsTeamId(null);
    }

    /**
     * Define Spatie roles and their permissions per team type.
     */
    private function rolesForTeam(TeamName $teamName): array
    {
        $base = [
            'admin'  => $this->adminPermissions($teamName),
            'member' => $this->memberPermissions($teamName),
            'viewer' => ['dashboard.view'],
        ];

        return $base;
    }

    private function adminPermissions(TeamName $teamName): array
    {
        $base = ['dashboard.view', 'team.edit', 'members.manage', 'groups.manage', 'roles.manage'];

        return match ($teamName) {
            TeamName::Management  => array_merge($base, ['teams.manage', 'reports.view']),
            TeamName::Docking     => array_merge($base, ['projects.manage', 'docking.manage']),
            TeamName::NewBuilding => array_merge($base, ['projects.manage', 'new-building.manage']),
            TeamName::Site        => array_merge($base, ['projects.manage', 'site.manage']),
        };
    }

    private function memberPermissions(TeamName $teamName): array
    {
        return match ($teamName) {
            TeamName::Management  => ['dashboard.view', 'reports.view'],
            TeamName::Docking     => ['dashboard.view', 'projects.view', 'docking.view'],
            TeamName::NewBuilding => ['dashboard.view', 'projects.view', 'new-building.view'],
            TeamName::Site        => ['dashboard.view', 'projects.view', 'site.view'],
        };
    }
}
