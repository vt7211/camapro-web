<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class checklogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $response = $next($request);
        if (Auth::guest()) {
            //return redirect()->guest("dangnhap");
            return redirect()->route('getlogin')->with(["flash_message"=>"Bạn không thể đăng nhập vào trang này","flash_level"=>"danger"]);

            //if(!$request->user()->isAdmin()){
            //    return redirect('/admin/user/login');
            //}
        }
        // dd($response);
        return $response;


    }
}
