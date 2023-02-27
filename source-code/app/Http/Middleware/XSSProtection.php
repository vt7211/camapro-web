<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class XSSProtection
{
    /**
     * The following method loops through all request input and strips out all tags from
     * the request. This to ensure that users are unable to set ANY HTML within the form
     * submissions, but also cleans up input.
     *
     * @param Request $request
     * @param callable $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!in_array(strtolower($request->method()), ['put', 'post'])) {
            return $next($request);
        }
        
        $before = $input = $request->all();
        
        $anhsai = 0;
        array_walk_recursive($input, function(&$input, $key) use (&$anhsai)  {
            if(is_string($input)){
                $input = strip_tags($input);
            }elseif($input!=null && $input->getClientOriginalExtension() != null){
                $ext = $input->getClientOriginalExtension();
                
                if (!in_array($ext, array('jpeg','jpg','png','gif'))) {
                    $input = null;
                    $anhsai = 1;
                }
            }
        });
        if($anhsai) return redirect()->back()->withInput()->with(["flash_message"=>"Bạn chọn ảnh không đúng định dạng","flash_level"=>"danger"]);
        // dd($before, $input, $anhsai);
        $request->merge($input);

        return $next($request);
    }
}
