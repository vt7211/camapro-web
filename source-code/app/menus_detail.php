<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menus_detail extends Model
{
    protected $table = "menus_detail";
    protected $fillable = ["id","alias","name", "class","description","menu_id","parent_id"];
    public $timestamps = true;

    public function menus(){
        return $this->belongsTo("App\menus","menu_id");
    }
}
