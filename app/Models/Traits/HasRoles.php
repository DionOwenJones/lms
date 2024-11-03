<?php

namespace App\Models\Traits;

trait HasRoles
{
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isBusiness(): bool
    {
        return $this->hasRole('business');
    }
} 