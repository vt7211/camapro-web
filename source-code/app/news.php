<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class news extends Model
{
    protected $table = "news";
    protected $fillable = ["id","alias","name", "status","intro","content","image","keywords","description","user_id","cate_id"];
    public $timestamps = true;

    public function categories(){
        return $this->belongsToMany("App\categories","categories_news","news_id","categories_id");  // quan he nhieu nhieu
    }
    public function User(){
        return $this->belongsTo("App\User","user_id");
    }
    public function comments(){
        return $this->hasMany("App\comments","id");
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('published', false);
    }
}
