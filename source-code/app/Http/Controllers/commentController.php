<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\news, App\comments;

class commentController extends Controller
{
    public function getList($type,$status){
        $total = comments::select('id')->where('comment_type',$type)->whereIn('status',array(0,1))->count();
        $active = comments::select('id')->where('comment_type',$type)->where('status',1)->count();
        $pendding = comments::select('id')->where('comment_type',$type)->where('status',0)->count();
        $spam = comments::select('id')->where('comment_type',$type)->where('status',3)->count();
        $draft = comments::select('id')->where('comment_type',$type)->where('status',2)->count();
        
        $comments = comments::where('comment_type',$type);
        if($status=='all')
            $comments=$comments->whereIn('status',array(0,1));
        else
            $comments=$comments->where('status',$status);
        $comments=$comments->orderBy("id","DESC")->get();
        return view("admin.listcomments", compact("comments","type","status","total","active","pendding","spam","draft"));
    }
    public function getStatus($type,$id,$status){
        // 0: pendding, 1:active, 2:draft, 3:spam
        $comment = comments::find($id);
        $comment->status = $status;
        $comment->save();
        return redirect()->route("admin.comment.getList",array($type,$status))->with(["flash_message"=>"Bạn đã cập nhật bình luận thành công","flash_level"=>"success"]);
    }
    public function getDelete($id){
        $comment = comments::find($id);
        $comment->delete($id);
        return redirect()->route("admin.comment.getList",array($type,$status))->with(["flash_message"=>"Bạn đã xóa bình luận thành công","flash_level"=>"success"]);

    }
}
