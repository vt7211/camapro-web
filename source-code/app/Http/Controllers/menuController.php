<?php

namespace App\Http\Controllers;

use App\menus, App\menus_detail, App\categories, App\news, DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class menuController extends Controller
{
    public function getList($id){
        if($id==0){
            $first = menus::select()->first();
            if($first) $id = $first->id;
        }
        $data = menus_detail::select()->where('menu_id',$id)->where('parent_id',0)->orderby('order_menu')->get();
        foreach($data as $item){
            $item->con =  menus_detail::select()->where('menu_id',$id)->where('parent_id',$item->id)->orderby('order_menu')->get();
            foreach($item->con as $itemc){
                $itemc->con =  menus_detail::select()->where('menu_id',$id)->where('parent_id',$itemc->id)->orderby('order_menu')->get();
            }
        }
        
        //$categories_news = categories::select("id","name","parent_id")->where('post_type','news')->get();
        $categories_news = DB::table('categories as c')
            ->leftJoin('alias as a', 'c.id', '=', 'a.id_target')
            ->select('c.name','a.alias','c.id','c.parent_id')
            ->where('c.post_type','news')
            ->where('a.type','catnews')
            ->orderBy('c.id', 'desc')
            ->get()->toArray();
        // dd($categories_news);
        //$categories_pro = categories::select("id","name","parent_id")->where('post_type','packeds')->get();
        $categories_pro = DB::table('categories as c')
            ->leftJoin('alias as a', 'c.id', '=', 'a.id_target')
            ->select('c.name','a.alias','c.id','c.parent_id')
            ->where('c.post_type','products')
            ->where('a.type','catproducts')
            ->orderBy('c.id', 'desc')
            ->get()->toArray();
        //$pages = news::with('categories')->where('type','page')->orderBy("id","DESC")->get();
        $pages = DB::table('news as n')
            ->leftJoin('alias as a', 'n.id', '=', 'a.id_target')
            ->select('n.name','a.alias','n.id')
            ->where('n.status',1)
            ->where('n.type','page')
            ->where('a.type','page')
            ->orderBy('n.id', 'desc')
            ->get();
        $menu = menus::select()->get();
    	return view('admin.listmenus', compact("data","id","menu","categories_news","categories_pro","pages"));
    }
    public function postadd(Request $Request){
        $menus = new menus;
        $menus->name = $Request->name;
        $menus->status = 1;
        $menus->save();

        return redirect()->route("admin.menu.getList", $menus->id)->with(["flash_message"=>"Bạn đã thêm danh mục thành công","flash_level"=>"success"]);
    }
    public function postedit(Request $Request,$id){
        
        $data = json_decode($Request->struct, true);
        //dd($Request->menu);
        // dd($data);
        $i=1;
        foreach($data as $menu){
            // dd($menu['id']);
            $menus = menus_detail::where('id',$menu['id'])->where('menu_id',$id)->first();
            if($menus != null){
                $menus->name = $Request->menu[$menu['id']]['name'];
                $menus->alias = $Request->menu[$menu['id']]['alias'];
                $menus->class = $Request->menu[$menu['id']]['class'];
                $menus->order_menu = $i;
                $menus->parent_id = 0;
                $menus->save();
                $i++;
            }else{
                $menus = new menus_detail;
                $menus->name = $Request->menu[$menu['id']]['name'];
                $menus->alias = $Request->menu[$menu['id']]['alias'];
                $menus->class = $Request->menu[$menu['id']]['class'];
                $menus->order_menu = $i;
                $menus->menu_id = $id;
                $menus->parent_id = 0;
                $menus->save();
                $i++;
            }
            if(isset($menu['children'])){
                // dd($menu['children']);
                foreach($menu['children'] as $menu2){
                    $menus = menus_detail::find($menu2['id']);
                    if($menus!=null){
                        $menus->name = $Request->menu[$menu2['id']]['name'];
                        $menus->alias = $Request->menu[$menu2['id']]['alias'];
                        $menus->class = $Request->menu[$menu2['id']]['class'];
                        $menus->order_menu = $i;
                        $menus->parent_id = $menu['id'];
                        $menus->save();
                        $i++;
                    }else{
                        $menus = new menus_detail;
                        $menus->name = $Request->menu[$menu2['id']]['name'];
                        $menus->alias = $Request->menu[$menu2['id']]['alias'];
                        $menus->class = $Request->menu[$menu2['id']]['class'];
                        $menus->order_menu = $i;
                        $menus->menu_id = $id;
                        $menus->parent_id = $menu['id'];
                        $menus->save();
                        $i++;
                    }
                    if(isset($menu2['children'])){
                        foreach($menu2['children'] as $menu3){
                            $menus = menus_detail::find($menu3['id']);
                            if($menus!=null){
                                $menus->name = $Request->menu[$menu3['id']]['name'];
                                $menus->alias = $Request->menu[$menu3['id']]['alias'];
                                $menus->class = $Request->menu[$menu3['id']]['class'];
                                $menus->order_menu = $i;
                                $menus->parent_id = $menu2['id'];
                                $menus->save();
                                $i++;
                            }else{
                                $menus = new menus_detail;
                                $menus->name = $Request->menu[$menu3['id']]['name'];
                                $menus->alias = $Request->menu[$menu3['id']]['alias'];
                                $menus->class = $Request->menu[$menu3['id']]['class'];
                                $menus->order_menu = $i;
                                $menus->menu_id = $id;
                                $menus->parent_id = $menu2['id'];
                                $menus->save();
                                $i++;
                            }
                        }
                    }
                }
            }
        }
        return redirect()->route("admin.menu.getList", $id)->with(["flash_message"=>"Bạn đã cập nhật danh mục thành công","flash_level"=>"success"]);
    }
    public function getDeleteCon($idcha,$id){
        $menu = menus_detail::find($id);
        $menu->delete($id);
        return redirect()->route("admin.menu.getList", $idcha)->with(["flash_message"=>"Bạn đã xóa danh mục thành công","flash_level"=>"success"]);
    }
    public function getDelete($id){
        $menu = menus::find($id);
        $menu->delete($id);
        return redirect()->route("admin.menu.getList", 0)->with(["flash_message"=>"Bạn đã xóa nhóm danh mục thành công","flash_level"=>"success"]);
    }
}
function getMenu($data, $id){
    foreach($data as $item){
        $con =  menus_detail::select()->where('menu_id',$id)->where('parent_id',$item->id)->get();
        if(count($con)>0){
            $item->con = $con;
            getMenu($item->con, $id);
        }else{
            return $data;
        }
    }
}
