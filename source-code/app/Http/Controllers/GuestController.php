<?php

namespace App\Http\Controllers;

use Request;

use DB, Mail, Auth, Hash;
use App\User, App\news, App\categories,App\comments, App\settings, App\menus_detail, App\passwordreset;
use  App\diachi, App\Alias, App\visit;

class GuestController extends Controller
{
    public function home(Request $Request) {
        $data = $this->getsetting();
        $ip = get_client_ip();
        if($ip){
            $visit = new visit;
            // $visit->ip = Request::ip();
            $visit->ip = get_client_ip();
            $visit->link = curPageURL();
            $visit->user_agent = Request::server('HTTP_USER_AGENT');
            $visit->referer = Request::server('HTTP_REFERER');
            // dd($visit);
            $visit->note = "";
            $visit->save();
        }
        return view('guest.home',compact("data"));
    }
    public function single($slug){
        $data = $this->getsetting();
        $ip = get_client_ip();
        if($ip){
            $visit = new visit;
            // $visit->ip = Request::ip();
            $visit->ip = get_client_ip();
            $visit->link = curPageURL();
            $visit->user_agent = Request::server('HTTP_USER_AGENT');
            $visit->referer = Request::server('HTTP_REFERER');
            // dd($visit);
            $visit->note = "Chi tiết: $slug";
            $visit->save();
        }
        return view('guest.home',compact("data","slug"));
    }
    public function getsetting(){
        $data = settings::select('name','value')->get()->toArray();
        $i = 0;
        $keys = array_keys($data);

        for($i=0;$i<count($keys);$i++) {
            $data[$data[$i]['name']]=$data[$i];
            unset($data[$i]);
        }
        // dd($data);
        $idmenu = 3;
        $data["menu"] = menus_detail::select()->where('menu_id',$idmenu)->where('parent_id',0)->orderby('order_menu')->get();
        foreach($data["menu"] as $item){
            $item->con =  menus_detail::select()->where('menu_id',$idmenu)->where('parent_id',$item->id)->orderby('order_menu')->get();
            foreach($item->con as $itemc){
                $itemc->con =  menus_detail::select()->where('menu_id',$idmenu)->where('parent_id',$itemc->id)->orderby('order_menu')->get();
                foreach($itemc->con as $itemcc){
                    $itemcc->con =  menus_detail::select()->where('menu_id',$idmenu)->where('parent_id',$itemcc->id)->orderby('order_menu')->get();
                }
            }
        }

        $idmenu = 4;

        // dd($data["menu"]);
        // $slider = settings::where('name','slider')->first();
        // $data['slider'] = json_decode($data['slider']);
        // dd($data['slider']);
        return $data;
    }

    public function slug($slug){
        $sl = Alias::where('alias', $slug)->first();
        
        
        $number = 15;
        if($sl==null){
            return redirect()->back()->with(["flash_message"=>"Đường dẫn bạn đang xem không tìm thấy trên hệ thống","flash_level"=>"danger","title"=>"Không Tìm Thấy","type"=>"alert"]);
        }

        $data = $this->getsetting();
        
        
        $login = Auth::check();
        //if($sl->type=="news" || $sl->type=="tailieu"){
        if($sl->type=="news"){
            $news = news::find($sl->id_target);
            return view('guest.news', compact("news","data"));
        }elseif($sl->type=="page"){
            $news = news::find($sl->id_target);
            return view('guest.news', compact("news","data"));
        }elseif($sl->type=="tailieu" && $login){
            $news = news::find($sl->id_target);
            return view('guest.news', compact("news","data"));
        }elseif($sl->type=="catnews"){
            $number = 24;
            $cat = categories::find($sl->id_target);
            $news = DB::table('news as n')
            ->leftJoin('categories_news as cd', 'n.id', '=', 'cd.news_id')
            ->leftJoin('alias as a', 'n.id', '=', 'a.id_target')
            ->select('n.name','n.image','n.content','n.description','n.intro','a.alias')
            ->where('cd.categories_id',$sl->id_target)
            ->where('n.status',1)
            ->orderBy('n.id', 'desc')
            ->paginate($number);
            // dd($news);
            return view('guest.catnews', compact("news","data","cat"));
        }else{
            return view('guest.notfound', compact("data"));
        }
        
    }

    public function getnewscat($id,$number){
        $data = array();
        $data["news"] = DB::table('news as n')
        ->leftJoin('categories_news as c', 'c.news_id', '=', 'n.id')
        ->leftJoin('alias as a', 'a.id_target', '=', 'n.id')
        ->select('n.name','a.alias','n.image','n.intro','n.content')
        ->where('a.type','news')
        ->where('n.status',1)
        ->where('c.categories_id',$id)
        ->limit($number)
        ->get();
        $cat = categories::select('name')->find($id);
        $data["title"] = $cat->name;
        return $data;
    }
}
