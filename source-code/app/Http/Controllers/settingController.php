<?php

namespace App\Http\Controllers;

use Request;
use App\settings, DB;
use Illuminate\Support\Facades\Input;
class settingController extends Controller
{
    public function getList(){
        // $where = array('titleweb','urlweb','address','email','sdt','fanpage','sdtzalo','youtube','google','logo','favicon','codeheader','codefooter');
        $data = settings::select('name','value')->get()->toArray();
        $i = 0;
        $keys = array_keys($data);

        for($i=0;$i<count($keys);$i++) {
            $data[$data[$i]['name']]=$data[$i];
            unset($data[$i]);
        }
        $slider = settings::where('name','slider')->first();
        $data['slider'] = json_decode($slider['value']);
        // dd($data['slider']);
    	return view('admin.settings.listsettings', compact("data"));
    }
    public function postSavegeneral( Request $Request){
        $all = Request::all() ;
        unset($all["_token"], $all["_method"]);
        // dd($all);

        foreach($all as $key => $item){
            // dd($all, $key,$item);
            $setting = settings::select()->where('name', $key)->first();
            if($setting){
                if(is_array($item)){
                    $setting->value = json_encode($item);
                }else{
                    $setting->value = $item;
                }
                $setting->save();
            }
            
        }

        return redirect()->route('admin.setting.getList')->with(["flash_message"=>"Bạn đã cập nhật cấu hình thành công","flash_level"=>"success"]);
    }
    public function postSaveslider( Request $Request){
        
        $setting = settings::where('name', 'slider')->first();
        $setting->value = json_encode(Request::input('slider'));
        $setting->save();
        
        return redirect()->route('admin.setting.getList')->with(["flash_message"=>"Bạn đã cập nhật cấu hình slider thành công","flash_level"=>"success"]);
    }
}
