<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnerPolicy
{
    use HandlesAuthorization, HandlessPolicy;

    public function viewAny(User $user)
    {
        return $this->onlySuperadmin($user);
    }

    public function view(User $user)
    {
        return $this->onlySuperadmin($user);
    }

    public function create(User $user)
    {
        return $this->onlySuperadmin($user);
    }

    public function update(User $user)
    {
        return $this->onlySuperadmin($user);
    }

    public function delete(User $user)
    {
        return $this->onlySuperadmin($user);
    }
}
