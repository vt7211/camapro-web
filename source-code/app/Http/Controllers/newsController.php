<?php

namespace App\Http\Controllers;

use Request;
use App\User, App\news, App\categories,DB, App\Alias;
use Illuminate\Support\Facades\Input;
use Validator, Config, File, Auth; // de xoa file va goi file
use Gate;

class newsController extends Controller
{

    public function getaddNews($type){
        // $parent = categories::select("id","name","parent_id")->where('post_type',$type)->get();
        $parent = categories::select('categories.*',"alias.alias")
        ->leftjoin('alias','categories.id','alias.id_target')
        ->where("post_type",$type)
        ->where("alias.type","cat".$type)
        ->groupBy('categories.id')
        ->get();



        // \View::composer(['media::header'], function (\View $view) {

        //     // Get all user's permission using your ACL system
        //     $mediaPermissions = [
        //         'folders.create',
        //         'folders.edit',
        //         'folders.delete',
        //         'files.create',
        //         'files.edit',
        //         'files.delete',
        //     ];
        //     \RvMedia::setPermissions($mediaPermissions);
        // });
        return view("admin.addnews", compact("parent","type"));
    }
    public function postaddNews(Request $Request,$type){
        // dd($Request);
        $alias = Request::input("alias");
        $count = Alias::where('alias', $alias)->count();
        if($count>0){
            return redirect()->back()->with(["flash_message"=>"Đường dẫn bài viết này đã tồn tại, bạn hãy thử với đường dẫn khác nhé.","flash_level"=>"danger"]);
        }
        $new = new news;
        $new->name = Request::input("name");
        $new->content = Request::input("content");
        $new->intro = Request::input("intro");
        $new->image = Request::input("feature_image");
        $new->keyworks = Request::input("keyworks");
        $new->status = Request::input("status");
        $new->description = Request::input("description");
        $new->user_id = Auth::user()->id;
        $new->type = $type;
        // $new->cate_id = json_encode(Request::input("choosecate"));

        $new->save();
        $Alias = new Alias;
        $Alias->id_target = $new->id;
        $Alias->alias = $alias;
        $Alias->type = $type;
        $Alias->save();

        $new->categories()->attach(Request::input("choosecate"));
        return redirect()->route("admin.news.getEdit",array($type,$new->id))->with(["flash_message"=>"Bạn đã thêm bài viết thành công","flash_level"=>"success"]);
    }
    public function getStatus($id, $status){
        $new = news::find($id);
        $new->status = $status;
        $new->save();
        return redirect()->back()->with(["flash_message"=>"Bạn đã cập nhật trạng tháy bài viết thành công","flash_level"=>"success"]);
    }
    public function getList($type, $status){
        $txt = $cat = '';
        $number = 30;
        $news = DB::table('news')
        ->join('users', 'users.id', '=', 'news.user_id');
        // ->join('categories_news', 'categories_news.news_id', '=', 'news.id');
        // ->join('categories', 'categories.id', '=', 'categories_news.categories_id');
        if($status=='all')
            $news=$news->whereIn('news.status',array(0,1));
        else
            $news=$news->where('news.status',$status);

        if(Auth::user()->level==2){
            $news=$news->where('user_id',Auth::user()->id);
            $uID = Auth::user()->id;
            $total = news::select('id')->where('type',$type)->where('user_id',$uID)->whereIn('status',array(0,1))->count();
            $active = news::select('id')->where('type',$type)->where('user_id',$uID)->where('status',1)->count();
            $pendding = news::select('id')->where('type',$type)->where('user_id',$uID)->where('status',0)->count();
            $draft = news::select('id')->where('type',$type)->where('user_id',$uID)->where('status',2)->count();
        }else{
            $total = news::select('id')->where('type',$type)->whereIn('status',array(0,1))->count();
            $active = news::select('id')->where('type',$type)->where('status',1)->count();
            $pendding = news::select('id')->where('type',$type)->where('status',0)->count();
            $draft = news::select('id')->where('type',$type)->where('status',2)->count();
        }


        if(isset($_GET['txt']) && $_GET['txt']!=''){
            $txt = $_GET['txt'];
            $news = $news->where('news.name','LIKE',"%$txt%");
        }
        if(isset($_GET['cat']) && $_GET['cat']!=''){
            $cat = $_GET['cat'];
            $news = $news->where('categories_news.categories_id',$cat);
        }
        $cats = categories::select("id","name","parent_id")->where('post_type',$type)->get();

        // ->take(10)
        $news = $news->where("news.type",$type)
        ->orderBy('news.id', 'desc')
        ->groupBy('news.id')
        ->select(array("news.image","news.status","news.created_at","news.name","news.id","username"))
        // ->selectRaw('GROUP_CONCAT(categories.name) as catname')
        ->paginate($number);

        foreach($news as $key => $n){
            $n->cname = DB::table('categories_news as cn')
            ->join('categories as c', 'c.id', '=', 'cn.categories_id')
            ->select('c.name')
            ->where('cn.news_id',$n->id)
            ->groupBy('c.id')
            ->get();
        }
        return view("admin.listnews", compact("news","status","total","active","pendding","draft","type","txt","cat","cats"));
    }
    public function getDelete($id){
        $current_user = Auth::user()->id;
        $new = news::find($id);
        $Alias = Alias::where("id_target",$id)->where("type",$new->type)->delete();
        $new->categories()->detach();
        $new->delete($id);



        return redirect()->back()->with(["flash_message"=>"Bạn đã xóa bài viết thành công","flash_level"=>"success"]);

    }
    public function getEdit($type, $id){
        // $data = news::find($id);
        $Alias = Alias::where("id_target",$id)->where("type",$type)->first();

        $data = news::with('categories')->where('id',$id)->first();
        $cat = $data->categories()->first();
        //$parent = categories::select("id","name","parent_id")->where("post_type",$type)->get();
        $parent = categories::select('categories.*',"alias.alias")
        ->leftjoin('alias','categories.id','alias.id_target')
        ->where("post_type",$type)
        ->where("alias.type","cat".$type)
        ->groupBy('categories.id')
        ->get();
        // dd($parent);
        return view('admin.editnews', compact("data","parent","id","cat","Alias","type"));
    }
    public function postEdit($type, $id, Request $Request){
        $alias = Request::input("alias");
        // dd($alias);
        $count = Alias::where('alias', $alias)->where('id_target','!=', $id)->count();
        if($count>1){
            return redirect()->back()->with(["flash_message"=>"Đường dẫn bài viết này đã tồn tại, bạn hãy thử với đường dẫn khác nhé.","flash_level"=>"danger"]);
        }
        $new = news::find($id);
        $new->name = Request::input("name");
        $new->content = Request::input("content");
        $new->intro = Request::input("intro");
        $new->image = Request::input("feature_image");
        $new->keyworks = Request::input("keyworks");
        $new->status = Request::input("status");
        $new->description = Request::input("description");
        // $new->user_id = Auth::user()->id;

        $new->save();

        $Alias = Alias::where("id_target",$id)->where("type","news")->first();
        if($Alias!=null){
            $Alias->alias = $alias;
            $Alias->save();
        }else{
            $Alias = new Alias;
            $Alias->id_target = $new->id;
            $Alias->alias = $alias;
            $Alias->type = $type;
            $Alias->save();
        }


        $new->categories()->sync(Request::input("choosecate"));
        return redirect()->back()->with(["flash_message"=>"Bạn đã sửa bài viết thành công","flash_level"=>"success"]);
    }

    public function getListPage($status){
        $pages = DB::table('news')
        ->join('users', 'users.id', '=', 'news.user_id')
        ->where('type','page');
        if($status=='all')
            $pages=$pages->whereIn('news.status',array(0,1));
        else
            $pages=$pages->where('news.status',$status);

        if(Auth::user()->level==2){
            $pages=$pages->where('user_id',Auth::user()->id);
            $uID = Auth::user()->id;
            $total = news::select('id')->where('type','page')->where('user_id',$uID)->whereIn('status',array(0,1))->count();
            $active = news::select('id')->where('type','page')->where('user_id',$uID)->where('status',1)->count();
            $pendding = news::select('id')->where('type','page')->where('user_id',$uID)->where('status',0)->count();
            $draft = news::select('id')->where('type','page')->where('user_id',$uID)->where('status',2)->count();
        }else{
            $total = news::select('id')->where('type','page')->whereIn('status',array(0,1))->count();
            $active = news::select('id')->where('type','page')->where('status',1)->count();
            $pendding = news::select('id')->where('type','page')->where('status',0)->count();
            $draft = news::select('id')->where('type','page')->where('status',2)->count();
        }


        $pages = $pages->orderBy('news.id', 'desc')->groupBy('news.id')->select(array(
            "news.image","news.status","news.created_at","news.name","news.id","username"
        ))->get();


        return view("admin.listpages", compact("pages","total","active","pendding","draft","status"));
    }
    public function getaddPage(){
        return view("admin.addpages");
    }
    public function postaddPage(Request $Request){
        $alias = Request::input("alias");
        $count = Alias::where('alias', $alias)->count();
        if($count>0){
            return redirect()->back()->with(["flash_message"=>"Đường dẫn trang này đã tồn tại, bạn hãy thử với đường dẫn khác nhé.","flash_level"=>"danger"]);
        }
        // dd($Request);
        $new = new news;
        $new->name = Request::input("name");
        $new->content = Request::input("content");
        $new->image = Request::input("feature_image");
        $new->keyworks = Request::input("keyworks");
        $new->status = Request::input("status");
        $new->description = Request::input("description");
        $new->user_id = Auth::user()->id;
        $new->type = 'page';
        // $new->cate_id = json_encode(Request::input("choosecate"));

        $new->save();
        $Alias = new Alias;
        $Alias->id_target = $new->id;
        $Alias->alias = $alias;
        $Alias->type = "page";
        $Alias->save();
        return redirect()->route("admin.page.getList","all")->with(["flash_message"=>"Bạn đã thêm trang thành công","flash_level"=>"success"]);
    }
    public function getEditPage($id){
        $Alias = Alias::where("id_target",$id)->where("type","page")->first();
        $data = news::find($id);
        return view('admin.editpage', compact("data","id","Alias"));
    }
    public function postEditPage($id, Request $Request){
        $alias = Request::input("alias");
        $count = Alias::where('alias', $alias)->where('id_target','!=', $id)->count();
        if($count>1){
            return redirect()->back()->with(["flash_message"=>"Đường dẫn trang này đã tồn tại, bạn hãy thử với đường dẫn khác nhé.","flash_level"=>"danger"]);
        }

        $new = news::find($id);
        $new->name = Request::input("name");
        $new->content = Request::input("content");
        $new->image = Request::input("feature_image");
        $new->keyworks = Request::input("keyworks");
        $new->status = Request::input("status");
        $new->description = Request::input("description");
        // $new->user_id = Auth::user()->id;

        $new->save();
        $Alias = Alias::where("id_target",$id)->where("type","page")->first();
        $Alias->alias = $alias;
        $Alias->save();
        return redirect()->route('admin.page.getList','all')->with(["flash_message"=>"Bạn đã sửa trang thành công","flash_level"=>"success"]);
    }
}
