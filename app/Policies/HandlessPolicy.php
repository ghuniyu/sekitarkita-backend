<?php

namespace App\Policies;

use App\Models\User;

trait HandlessPolicy
{
    public function onlySuperadmin(User $user)
    {
        return $user['area'] == null;
    }
}
