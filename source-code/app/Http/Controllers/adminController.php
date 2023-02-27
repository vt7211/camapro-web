<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User, App\news, App\categories,App\comments;
class adminController extends Controller
{
    public function dashboard() {
        $data = array();
        $data['users'] = User::select('id')->count();
        $data['news'] = news::select('id')->where('type','news')->where('status',1)->count();
        $data['comments'] = comments::select('id')->where('status',1)->count();
        $data['alerts'] = 0;
        $data['datanews'] = news::select('name','status','created_at')->where('type','news')->whereIn('status',array(0,1))->limit(30)->get();
        $data['datacomments'] = comments::select('comment_content','status','created_at')->whereIn('status',array(0,1))->limit(30)->get();
        return view('admin.dashboard',compact("data"));
    }
}
