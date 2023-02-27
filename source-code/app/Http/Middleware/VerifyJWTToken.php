<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class VerifyJWTToken extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                // return response()->json(['status' => 'Token is Invalid']);
                return response()->json(['status'=>'error','msg' => 'token invalid'],401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                // return response()->json(['status' => 'Token is Expired']);
                return response()->json(['status'=>'error','msg' => 'token expired'],401);
            }else{
                //return response()->json(['status' => 'Authorization Token not found']);
                return response()->json(['status'=>'error','msg' =>'Token is required'],400);
            }
        }
        return $next($request);
    }
}
