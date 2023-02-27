<?php

namespace App\Policies;

use App\User;
use App\categories;
use Illuminate\Auth\Access\HandlesAuthorization;

class categoriesPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->hasAccess(['cate.list']);
    }
    public function create(User $user)
    {
        return $user->hasAccess(['cate.create']);
    }
    public function update(User $user)
    {
        return $user->hasAccess(['cate.update']);
    }
    public function delete(User $user)
    {
        return $user->hasAccess(['cate.delete']);
    }
    
}
