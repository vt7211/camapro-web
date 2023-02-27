<?php

namespace App\Policies;

use App\User;
// use App\comments;
use Illuminate\Auth\Access\HandlesAuthorization;

class visitPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->hasAccess(['visit.list']);
    }
    public function update(User $user)
    {
        return $user->hasAccess(['visit.update']);
    }

    public function delete(User $user)
    {
        return $user->hasAccess(['visit.delete']);
    }
}
