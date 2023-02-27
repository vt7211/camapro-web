<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use JWTAuth, DB;
use Tymon\JWTAuth\Exceptions\JWTException;
// use Illuminate\Http\Response;

use Auth, Response, Request, Carbon;
use App\User;

class apiController extends Controller{
    public function getData(Request $request, $name){
        $url = env('DOMAIN_API')."/$name";
        // $header = $request->header('Authorization');
        $header = [];
        $Authorization = Request::header('Authorization');
        // foreach(Request::header() as $key => $item) {
        //     $header[] = "$key: $item";
        // }
        if($Authorization) $header[] = "Authorization: $Authorization";
        $body = Request::all();
        $appendHtml = (int)Request::input('appendHtml');
        $method = Request::input('method') ? Request::input('method') : 'post';
        // $method = 'post';
        // $data = curl($url, $method, $header, json_encode($body));
        $data = curl($url, $method, $header, $body);
        // if(Request::input('addfavourite')) return [$data, 1];
        // if($name == 'addfavourite') return [$Authorization, $body['id'], $data];
        // $data = json_decode($data);
        if(!$appendHtml){
            // return $data;
            return response()->json($data);
        }
        return view("guest.tabs.tab$name", compact("data"));
        // return response()->json(array('status'=>'success','title'=>'Tin Tức','data'=>$news,'msg'=>'Lấy danh sách bài viết thành công'));
    }
}
