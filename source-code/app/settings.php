<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    protected $table = "settings";
    protected $fillable = ["id","name", "value","status"];
    public $timestamps = false;
}
