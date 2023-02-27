<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alias;
class AliasController extends Controller
{
    public function checkalias(Request $Request){
        $alias = $Request->alias;
        $status = 1;
        $head = 200;
        $count = Alias::where('alias', $alias);
        if($Request->id!=0) $count=$count->where('id_target','!=',$Request->id);
        $count=$count->count();
        if($count>0){
            $status = 0;
            $head = 200;
        }
        return response()->json(array('status'=> $status), $head);
    }
}
