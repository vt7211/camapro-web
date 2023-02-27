<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    protected $table = "categories";
    public $timestamps = true;
    protected $fillable = ["id","alias","name", "order", "parent_id","keywords", "description", "content"];

    public function news(){
        // return $this->belongsToMany("App\\news","categories","categories_id","news_id"); // quan he nhieu nhieu
        return $this->belongsToMany("App\\news"); // quan he nhieu nhieu
    }
}
