<?php

namespace App\Models;

use App\Enums\TeamName;
use Jurager\Teams\Models\Team as BaseTeam;

class Team extends BaseTeam
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['user_id', 'name', 'type', 'description'];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TeamName::class,
        ];
    }

    /**
     * Determine if this is the Management team (super-admin).
     */
    public function isManagement(): bool
    {
        return $this->type === TeamName::Management;
    }

    /**
     * Get the route name for this team's dashboard.
     */
    public function dashboardRoute(): string
    {
        return match ($this->type) {
            TeamName::Management  => 'management.dashboard',
            TeamName::Docking     => 'docking.dashboard',
            TeamName::NewBuilding => 'new-building.dashboard',
            TeamName::Site        => 'site.dashboard',
            default               => 'dashboard',
        };
    }
}
