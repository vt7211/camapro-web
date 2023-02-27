<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\news, App\User;

class NewsPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->hasAccess(['news.list']);
    }
    public function create(User $user)
    {
        return $user->hasAccess(['news.create']);
    }
    public function update(User $user, $id)
    {
        
        $news = news::find($id);
        // dd($news->user_id);
        return $user->hasAccess(['news.update']) or $user->id == $news->user_id;
    }
    public function delete(User $user)
    {
        return $user->hasAccess(['news.delete']);
    }
    public function publish(User $user)
    {
        return $user->hasAccess(['news.publish']);
    }
    public function draft(User $user)
    {
        // return $user->inRole('admin','supper');
        return $user->hasAccess(['news.draft']) or $user->id == $news->user_id;
    }
}
