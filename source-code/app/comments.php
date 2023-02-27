<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
  protected $table = "comments";
  protected $fillable = ["id","name_author", "email_author","ip_author","comment_content","user_id"];
  public $timestamps = true;

  public function news(){
      return $this->belongTo("App\\news","post_id");
  }
  public function users(){
      return $this->belongTo("App\User","user_id");
  }
}
