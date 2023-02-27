<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Http\Requests\loginRequest;
use DB,Session,Hash,Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    // gioi han 10 lan dang nhap trong vòng 300s
    protected $maxLoginAttempts = 10; // Amount of bad attempts user can make
    protected $lockoutTime = 300; // Time for which user is going to be blocked in seconds

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest', ['except' => ['logout', 'Postlogin']]); // whjat the heck
        $this->middleware('guest', ['except' => ['logout', 'authenticate', 'Postlogin', 'getLogin']]);
    }
    public function getLogin(){
        if(Auth::check()){
            $lv = Auth::user()->level;
            return redirect()->route("admin.dashboard")->with(["flash_message"=>"Bạn đã đăng nhập thành công","flash_level"=>"success"]);
            // if($lv == 4 || $lv == 3){
            //     return redirect()->route("admin.dashboard")->with(["flash_message"=>"Bạn đã đăng nhập thành công","flash_level"=>"success"]);
            // }elseif($lv == 2){
            //     return redirect()->route("owner.dashboard")->with(["flash_message"=>"Bạn đã đăng nhập thành công","flash_level"=>"success"]);
            // }else{
            //     return redirect()->route("user.dashboard")->with(["flash_message"=>"Bạn đã đăng nhập thành công","flash_level"=>"success"]);
            // }
        }
        return view('login');
    }


    public function Postlogin(loginRequest $request){
        $login = array(
            "username"=>$request->username,
            "password"=>$request->password,
        );
        if(isset($request->remember) || $request->remember == 1) $remember = true;
        else $remember = false;

        // $user = User::find(1);
        // $user->password = Hash::make('!@#digistar!@#');
        // $user->remember_token = $request->_token;
        // $user->save();

        if(Auth::attempt($login,$remember)){
            $lv = Auth::user()->level;
            // if($lv == 1 || $lv == 2){
            //     return redirect()->route("admin.dashboard")->with(["flash_message"=>"Bạn đã đăng nhập thành công","flash_level"=>"success"]);
            // }elseif($lv == 3){
            //     return redirect()->route("owner.dashboard")->with(["flash_message"=>"Bạn đã đăng nhập thành công","flash_level"=>"success"]);
            // }else{
            //     return redirect()->route("user.dashboard")->with(["flash_message"=>"Bạn đã đăng nhập thành công","flash_level"=>"success"]);
            // }
            return redirect()->route("admin.dashboard")->with(["flash_message"=>"Bạn đã đăng nhập thành công","flash_level"=>"success"]);
        }else{
            return redirect()->route("getlogin")->with(["flash_message"=>"Username or password not correct","flash_level"=>"danger"]);
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route("getlogin")->with(["flash_message"=>"You are logout success","flash_level"=>"success"]);
    }
}
