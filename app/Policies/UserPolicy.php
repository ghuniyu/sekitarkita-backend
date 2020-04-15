<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, HandlessPolicy;

    public function viewAny(User $user)
    {
        return $this->onlySuperadmin($user);
    }

    public function view(User $user, User $model)
    {
        return $this->onlySuperadmin($user);
    }

    public function create(User $user)
    {
        return $this->onlySuperadmin($user);
    }

    public function update(User $user, User $model)
    {
        return $this->onlySuperadmin($user);
    }

    public function delete(User $user)
    {
        return $this->onlySuperadmin($user);
    }

}
