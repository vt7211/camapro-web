<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\categories, App\Alias;
class CateController extends Controller
{
    public function checkalias(Request $Request){
        $alias =  $Request->alias;
        $status = 1;
        $head = 200;
        $count = Alias::where('alias', $alias);
        if( $Request->id!=0) $count=$count->where('id','!=', $Request->id);
        $count=$count->count();
        if($count>1){
            $status = 0;
            $head = 200;
        }
        return response()->json(array('status'=> $status), $head);
     }
    public function getAdd($type){
        $parent = categories::select("id","name","parent_id")->where('post_type',$type)->get()->toArray();
        return view("admin.addcate", compact("parent","type"));
    }
    public function postAdd(Request $Request, $type){
		$cate = new categories;
		$cate->name = $Request->name;
		$cate->order = $Request->order;
		$cate->parent_id = $Request->parent_id ? $Request->parent_id : 0;
		$cate->keywords = $Request->keywords;
		$cate->description = $Request->description;
        $cate->content = $Request->content;
        $cate->post_type = $type;
        $cate->feature_image = $Request->feature_image;

        $cate->save();
        $alias = new Alias;
        $alias->alias = changeTitle($Request->alias);
        $alias->type = "cat".$type;
        $alias->id_target = $cate->id;
        $alias->save();

    	return redirect()->route('admin.cate.getList',$type)->with(["flash_message"=>"Nhóm tin đã được thêm thành công","flash_level"=>"success"]);
    }
    public function getList($type){

        // $data = categories::select("id", "feature_image","name", "parent_id","alias","created_at")->orderBy("id","Desc")->where("post_type",$type)->get()->toArray();
        // $k = $type;

        $data = categories::withCount($type)->where("post_type",$type)->orderBy('order','DESC')->get()->toArray();
        $count = categories::select("id")->where("post_type",$type)->count();

    	return view('admin.listcate', compact("data","type",'count'));
    }

    public function getEdit($type,$id){
        $data = categories::where("id", $id)->get()->first()->toArray(); // dung $data = cates::findOrFail($id)->toArray()
        $parent = categories::select("id","name","parent_id")->where('post_type',$type)->get()->toArray();
        $alias = Alias::where('id_target',$id)->where('type',"cat".$type)->first();
        return view("admin.editcate", compact("data","parent","type","alias"));
    }
    public function postEdit(Request $Request,$type,$id){
        // $this->validate($Request,
        //     [
        //         "txtCateName"=>"Required"
        //     ],
        //     [
        //         "txtCateName.required"=>"The name category not empty "
        //     ]
        // );
        $cate = categories::find($id);
        $cate->name = $Request->name;
        $cate->order = $Request->order;
        $cate->parent_id = $Request->parent_id != $id ? $Request->parent_id : 0;
        $cate->keywords = $Request->keywords;
        $cate->description = $Request->description;
        $cate->content = $Request->content;
        $cate->feature_image = $Request->feature_image;
        $cate->save();

        $alias = Alias::where('id_target',$id)->where('type',"cat".$type)->first();
        if($alias==null){
            $alias = new Alias;
            $alias->alias = changeTitle($Request->alias);
            $alias->type = "cat".$type;
            $alias->id_target = $cate->id;
            $alias->save();
        }else{
            $alias->alias = changeTitle($Request->alias);
            $alias->save();
        }


        return redirect()->route('admin.cate.getList',$type)->with(["flash_message"=>"Nhóm đã được cập nhật thành công","flash_level"=>"success"]); // chuyen trang va truyen cau thong va kieu thong bao
    }

    public function getDelete($type, $id){
        $count = categories::where("parent_id", $id)->count();
        if($count == 0){
            $alias = Alias::where('id_target',$id)->where('type',"cat".$type)->delete();

            $cate = categories::find($id);
            $cate->delete($id);
            return redirect()->route('admin.cate.getList',$type)->with(["flash_message"=>"Nhóm này đã được xóa","flash_level"=>"success"]);
        }else{
            return redirect()->route('admin.cate.getList',$type)->with(["flash_message"=>"Bạn không thể xóa nhóm này, bạn hãy xóa các nhóm con trước","flash_level"=>"danger"]);
        }
    }
}
