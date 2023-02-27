<?php

namespace App\Http\Controllers;

use Request;
// use App\Http\Requests\userRequest;

use App\User,App\Role;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator, Config, File; // de xoa file va goi file

class userController extends Controller
{
    public function getaddUser(){
        $roles = Role::select("id","name")->get();
    	return view("admin.adduser",compact("roles"));
    }
    public function postaddUser(Request $Request){
        // $v = Validator::make(Request::all() , [
        //     'avarta' => 'image|max:2048'
        // ]);
        // if ($v->fails())
        // {
        //     $messages = $v->errors()->getMessages();
        //     $str = '';
        //     foreach($messages['avarta'] as $i){
        //         echo $str .= '<li>'.$i.'</li>';
        //     }
        //     return redirect()->back()->with(["flash_message"=>"<ul>".$str."</ul>","flash_level"=>"danger"]);
        // }
        $count = User::select('id')->where('username',Request::input("username"))->count();
        if($count>0){
            return redirect()->route("admin.user.getList")->with(["flash_message"=>"Username này đã tồn tại, bạn hãy đặt 1 username khác nhé","flash_level"=>"success"]);
        }
        $user = new User;
        $user->username = Request::input("username");
    	$user->firstname = Request::input("first");
        $user->lastname = Request::input("last");
        $user->email = Request::input("email");
        $user->phone = Request::input("phone");
        $user->address = Request::input("add");
        $user->status = Request::input("status");
        $user->level = Request::input("level");
        $user->password = bcrypt(Request::input("pass"));
        $user->note = Request::input("note");
    	$user->remember_token = Request::input("_token");

        $user->ngaysinh = Request::input("ngaysinh");
        if(!empty(Request::File("avarta"))){
            $filename = time().'-'.Request::File("avarta")[0]->getClientOriginalName();
            $user->image = $filename;
            // dd(Request::input());
            Input::file("avarta")[0]->move(Config::get('siteVars.dirUploadUser'), $filename);
        }
        $user->save();
        $user->roles()->attach(Request::input("level"));

    	return redirect()->route("admin.user.getList")->with(["flash_message"=>"Bạn đã thêm thành viên thành công","flash_level"=>"success"]);
    }

    public function getList(){
        $role = Role::select('id','name')->get();
    	$users = User::select(["id","firstname","lastname","address","phone","status","email", "level","username"])->orderBy("id","DESC")->get()->toArray();
    	return view("admin.listusers", compact("users","role"));
    }
    public function getDelete($id){
        $current_user = Auth::user()->id;
        $user = User::find($id);
        if($user->email=='vantan7211@gmail.com'){
            return redirect()->route("admin.user.getList")->with(["flash_message"=>"Bạn không thể xóa user này","flash_level"=>"danger"]);
        }
        if($id == 1 || ($current_user != 1 && $user["level"]==1)){
            return redirect()->route("admin.user.getList")->with(["flash_message"=>"Bạn không thể xóa user này","flash_level"=>"danger"]);
        }else{
            File::delete(Config::get('siteVars.dirUploadUser')."/".$user->image);
            $user->delete($id);
            return redirect()->route("admin.user.getList")->with(["flash_message"=>"Bạn đã xóa user này thành công","flash_level"=>"success"]);
        }

    }
    public function getEdit($id){
        $roles = Role::select("id","name")->get();
        $current_user = Auth::user()->id;
        $user = User::find($id);
        if($current_user != 1 && $user["level"]==1 && ($id != $current_user)){
            return redirect()->route("admin.user.getList")->with(["flash_message"=>"Bạn không thể sửa user này","flash_level"=>"danger"]);
        }else{
            $data = User::find($id);
            return view('admin.edituser', compact("data","roles"));
        }
    }
    public function postEdit($id, Request $Request){
        // $this->validate($Request,[
        //     "txtPass"=>"min:6",
        //     "txtRePass"=>"same:txtPass",
        //     "txtEmail"=>"email"
        // ]);
        $authu = Auth::User();
        $changerole = $authu->is('user.changerole');
        $user = User::find($id);

        $user->firstname = Request::input("first");
        $user->lastname = Request::input("last");
        $user->email = Request::input("email");
        $user->phone = Request::input("phone");
        $user->address = Request::input("add");
        if($changerole){
            $user->status = Request::input("status");
            $user->level = Request::input("level");
        }
        $user->ngaysinh = Request::input("ngaysinh");
        if(Request::input("pass")!=''){
            $user->password = bcrypt(Request::input("pass"));
        }
        $user->note = Request::input("note");
        $user->remember_token = Request::input("_token");

        if(!empty(Request::File("avarta"))){
            file::delete(Config::get('siteVars.dirUploadUser').'/'.$user->image);
            $filename = time().'-'.Request::File("avarta")[0]->getClientOriginalName();
            $user->image = $filename;
            // dd(Request::input());
            Input::file("avarta")[0]->move(Config::get('siteVars.dirUploadUser'), $filename);
        }
        $user->roles()->sync(Request::input("level"));
        $user->save();
        return redirect()->route('admin.user.getList')->with(["flash_message"=>"Bạn đã sửa user này thành côn","flash_level"=>"success"]);
    }

    public function getthoat(){
        Auth::logout();
        return redirect()->route('getdangnhap')->with(["flash_message"=>"Bạn đã đăng xuất thành công","flash_level"=>"success"]);
    }
}

