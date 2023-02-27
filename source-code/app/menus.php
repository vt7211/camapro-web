<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menus extends Model
{
    protected $table = "menus";
    public $timestamps = true;
    protected $fillable = ["id","name"];

    public function menus_detail(){
        return $this->hasMany("App\menus_detail");
    }
}
