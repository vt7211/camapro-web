<?php

namespace App\Policies;

use App\User;
// use App\settings;
use Illuminate\Auth\Access\HandlesAuthorization;

class settingsPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->hasAccess(['setting.list']);
    }

    public function update(User $user)
    {
        return $user->hasAccess(['setting.update']);
    }
}
