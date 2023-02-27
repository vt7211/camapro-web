<?php
namespace App;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $fillable = [
        'name', 'email', 'password','level'
    ];
    public function roles(){
        return $this->belongsToMany("App\\role", 'role_users',"user_id","role_id");
    }
    protected $hidden = [
        'password', 'remember_token',
    ];

    
    public function is($roleName)
    {
        $roles = $this->roles()->first();
        foreach ($roles->permissions as $k => $v)
        {
            if ($k == $roleName && $v == true)
            {
                return true;
            }
        }

        return false;
    }
    public function hasAccess(array $permissions) : bool
    {
        if(isset($this->roles)){
            foreach ($this->roles as $role) {
                if($role->hasAccess($permissions)) {
                    return true;
                }
            }
        }

        return false;
    }
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->count() == 1;
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
