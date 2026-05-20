<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Get the owner ID for data scoping.
     * Admins see their own data (owner_id = user.id).
     * Employees see their boss's data (owner_id = user.owner_id).
     */
    protected function getOwnerId()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $user->id;
        }
        
        return $user->owner_id;
    }
}
