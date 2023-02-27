<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\visit;

class VisitController extends Controller
{
    public function getList(){
        $start = date('d/m/Y',strtotime('-1 months',time()));
        $end = date('d/m/Y');
        $referer = '';

        $visits = visit::select();

        if(isset($_GET["referer"]) && $_GET["referer"]!=''){
            $referer = $_GET["referer"];
            $visits = $visits->where("referer","LIKE","%$referer%");
        }

        if(isset($_GET["start"]) && $_GET["start"]!=null){
            $start = $_GET["start"];
        }
        if(isset($_GET["end"]) && $_GET["end"]!=null){
            $end = $_GET["end"];
        }
        $date = str_replace('/', '-', $start );
        $startdate = date("Y-m-d", strtotime($date));
        
        $date = str_replace('/', '-', $end );
        $enddate = date("Y-m-d", strtotime($date));
        $visits = $visits->where('created_at', '>=',$startdate.' 0:0:0')
        ->where('created_at', '<=',$enddate.' 23:59:59')
        ->orderBy('id', 'desc')
        ->paginate(50);

        return view("admin.listvisit", compact("visits","start","end","referer"));
    }
    public function getDelete($id){
    
    }
}
