<?php

namespace App;



use Illuminate\Database\Eloquent\Model;



class passwordreset extends Model
{
    protected $table = "password_resets";
    protected $fillable = ["id","token"];
    public $timestamps = false;
}

