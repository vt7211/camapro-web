<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    protected $table = "alias";
    public $timestamps = false;
    protected $fillable = [
        'alias', 'id_target'
    ];
    
}
