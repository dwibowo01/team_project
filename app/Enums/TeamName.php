<?php

namespace App\Enums;

enum TeamName: string
{
    case Management  = 'Management';
    case Docking     = 'Docking';
    case NewBuilding = 'New Building';
    case Site        = 'Site';

    /**
     * Return a human-readable label.
     */
    public function label(): string
    {
        return $this->value;
    }

    /**
     * URL-friendly slug for routing.
     */
    public function slug(): string
    {
        return match ($this) {
            self::Management  => 'management',
            self::Docking     => 'docking',
            self::NewBuilding => 'new-building',
            self::Site        => 'site',
        };
    }

    /**
     * Whether this team supports groups and project management.
     */
    public function supportsGroups(): bool
    {
        return $this !== self::Management;
    }
}
