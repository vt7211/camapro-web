<?php

namespace App\Policies;

use App\User;
// use App\menus;
use Illuminate\Auth\Access\HandlesAuthorization;

class menusPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->hasAccess(['menu.list']);
    }

    public function create(User $user)
    {
        return $user->hasAccess(['menu.create']);
    }

    public function update(User $user)
    {
        return $user->hasAccess(['menu.update']);
    }

    public function delete(User $user)
    {
        return $user->hasAccess(['menu.delete']);
    }
}
