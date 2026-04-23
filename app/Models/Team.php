<?php

namespace App\Models;

use App\Enums\TeamName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Jurager\Teams\Models\Owner;
use Jurager\Teams\Support\Facades\Teams as TeamsFacade;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'description',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['roles.permissions', 'groups.permissions'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type'          => TeamName::class,
            'personal_team' => 'boolean',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Get the owner of the team.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(TeamsFacade::model('user'), 'user_id');
    }

    /**
     * Get all users associated with the team (jurager-compatible pivot with role_id).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            TeamsFacade::model('user'),
            TeamsFacade::model('membership'),
            Config::get('teams.foreign_keys.team_id', 'team_id'),
            'user_id'
        )
            ->withPivot('role_id', 'role')
            ->withTimestamps()
            ->as('membership');
    }

    /**
     * Get all roles associated with the team.
     */
    public function roles(): HasMany
    {
        return $this->hasMany(TeamsFacade::model('role'), Config::get('teams.foreign_keys.team_id', 'team_id'), 'id');
    }

    /**
     * Get all permissions linked to the team.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(TeamsFacade::model('permission'), Config::get('teams.foreign_keys.team_id', 'team_id'), 'id');
    }

    /**
     * Get all abilities linked to the team.
     */
    public function abilities(): HasMany
    {
        return $this->hasMany(TeamsFacade::model('ability'), Config::get('teams.foreign_keys.team_id', 'team_id'), 'id');
    }

    /**
     * Get all groups associated with the team.
     */
    public function groups(): HasMany
    {
        return $this->hasMany(TeamsFacade::model('group'), Config::get('teams.foreign_keys.team_id', 'team_id'), 'id');
    }

    /**
     * Get team invitations (Jetstream).
     */
    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    // -------------------------------------------------------------------------
    // User Management (jurager-compatible API)
    // -------------------------------------------------------------------------

    /**
     * Get all users associated with the team (owner + members).
     */
    public function allUsers(): Collection
    {
        return $this->users->merge([$this->owner])->unique('id');
    }

    /**
     * Determine whether the given user belongs to this team.
     *
     * @param  mixed  $user
     */
    public function hasUser($user): bool
    {
        return $this->users->contains($user) || $this->owner?->id === $user->id;
    }

    /**
     * Determine if the team has a member with the given email address.
     */
    public function hasMemberWithEmail(string $email): bool
    {
        return $this->allUsers()->contains(fn ($user) => $user->email === $email);
    }

    /**
     * Get the role of the given user in this team.
     */
    public function userRole(object $user): ?object
    {
        if ($this->owner && $this->owner->id === $user->id) {
            return new Owner();
        }

        $member = $this->users->firstWhere('id', $user->id);

        if ($member) {
            $roleId = $member->membership->role_id ?? null;
            if ($roleId) {
                return $this->roles->firstWhere('id', $roleId);
            }
        }

        return null;
    }

    /**
     * Add a user to the team with the given role code.
     */
    public function addUser(object $user, string $roleCode): void
    {
        if ($this->hasUser($user)) {
            return;
        }

        $role = $this->roles->firstWhere('code', $roleCode);

        if (! $role) {
            return;
        }

        $this->users()->attach($user->id, ['role_id' => $role->id, 'role' => $roleCode]);
        $this->unsetRelation('users');
    }

    /**
     * Update the role of an existing team member.
     */
    public function updateUser(object $user, string $roleCode): void
    {
        if (! $this->hasUser($user)) {
            return;
        }

        $role = $this->roles->firstWhere('code', $roleCode);

        if (! $role) {
            return;
        }

        $this->users()->updateExistingPivot($user->id, ['role_id' => $role->id, 'role' => $roleCode]);
        $this->unsetRelation('users');
    }

    /**
     * Remove a user from the team.
     */
    public function deleteUser(object $user): void
    {
        if (! $this->hasUser($user)) {
            return;
        }

        $this->users()->detach($user->id);
        $this->unsetRelation('users');
    }

    /**
     * Remove a team member (Jetstream alias for deleteUser).
     *
     * @param  mixed  $user
     */
    public function removeUser($user): void
    {
        $this->deleteUser($user);
    }

    // -------------------------------------------------------------------------
    // Role Management
    // -------------------------------------------------------------------------

    /**
     * Determine if the team has a role with the given code.
     */
    public function hasRole(string $code): bool
    {
        return $this->roles->contains('code', $code);
    }

    /**
     * Add a role to the team.
     */
    public function addRole(string $code, array $permissions = [], ?string $name = null, ?string $description = null): object
    {
        $role = $this->roles()->create([
            'code'        => $code,
            'name'        => $name,
            'description' => $description,
        ]);

        foreach ($permissions as $permissionCode) {
            $this->permissions()->firstOrCreate(['code' => $permissionCode]);
        }

        $this->load('roles.permissions');

        return $role;
    }

    /**
     * Update a role and its permissions.
     */
    public function updateRole(string $code, array $permissions = [], ?string $name = null, ?string $description = null): object|bool
    {
        $role = $this->roles->firstWhere('code', $code);

        if (! $role) {
            return false;
        }

        $role->update(array_filter(compact('name', 'description')));

        return $role;
    }

    /**
     * Delete a role from the team.
     */
    public function deleteRole(string $code): bool
    {
        $role = $this->roles->firstWhere('code', $code);

        if (! $role) {
            return false;
        }

        return (bool) $role->delete();
    }

    // -------------------------------------------------------------------------
    // Group Management
    // -------------------------------------------------------------------------

    /**
     * Get a group by its code or id.
     */
    public function getGroup(int|string $keyword): ?object
    {
        if (is_int($keyword)) {
            return $this->groups->firstWhere('id', $keyword);
        }

        return $this->groups->firstWhere('code', $keyword);
    }

    /**
     * Add a group to the team.
     */
    public function addGroup(string $code, array $permissions = [], ?string $name = null): object
    {
        $group = $this->groups()->create([
            'code' => $code,
            'name' => $name ?? $code,
        ]);

        $this->load('groups.permissions');

        return $group;
    }

    /**
     * Update an existing group.
     */
    public function updateGroup(int|string $keyword, array $permissions = [], ?string $name = null): object|bool
    {
        $group = $this->getGroup($keyword);

        if (! $group) {
            return false;
        }

        if ($name !== null) {
            $group->update(['name' => $name]);
        }

        return $group;
    }

    /**
     * Delete a group from the team.
     */
    public function deleteGroup(int|string $keyword): bool
    {
        $group = $this->getGroup($keyword);

        if (! $group) {
            return false;
        }

        return (bool) $group->delete();
    }

    // -------------------------------------------------------------------------
    // Team Type Helpers
    // -------------------------------------------------------------------------

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
