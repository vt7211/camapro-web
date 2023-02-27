<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class usersPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->hasAccess(['user.list']);
    }

    public function create(User $user)
    {
        return $user->hasAccess(['user.create']);
    }

    public function update(User $user,$id)
    {
        return $user->hasAccess(['user.update'])  or $user->id == $id;
    }
    public function delete(User $user)
    {
        return $user->hasAccess(['user.delete']);
    }
    public function changerole(User $user)
    {
        return $user->hasAccess(['user.changerole']);
    }
}
