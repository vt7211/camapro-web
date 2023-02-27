<?php

namespace App\Policies;

use App\User;
// use App\comments;
use Illuminate\Auth\Access\HandlesAuthorization;

class commentsPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->hasAccess(['comment.list']);
    }

    public function create(User $user)
    {
        return $user->hasAccess(['comment.create']);
    }

    public function update(User $user)
    {
        return $user->hasAccess(['comment.update']);
    }

    public function delete(User $user)
    {
        return $user->hasAccess(['comment.delete']);
    }
}
