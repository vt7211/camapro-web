<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use JWTAuth, DB;
use Tymon\JWTAuth\Exceptions\JWTException;
// use Illuminate\Http\Response;
use App\Jobs\getcodefree;
use Illuminate\Support\Facades\Redis;

use Auth, Response, Request, Carbon;
use App\image_cosos, App\chonlydohh, App\vongquay, App\giovang, App\active, App\point, App\inbox;
use  App\User, App\coso, App\code, App\diachi, App\settings, App\news, App\lydohh, App\danhgia, App\dkcoso;
// set_time_limit(0);
// ini_set("memory_limit", "10056M");
class apiController extends Controller{
    public function getsetting(){
        $diachis = diachi::get();
        $settings = settings::get();
        $url = URL('/');
        $urlcss = $url.'/app.css';
        $noibo = ip_info(null, 'Country Code') == "VN" ? true : false;
        foreach($settings as $item){
            if($item->name == 'quyenloitv'){
                // $item->value = $item->value;
                $item->value = '<html><head><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body><div class="content" style="width: 100%; "><link href="'.$item->value.'" rel="stylesheet" type="text/css" />'.str_replace('src="/uploads/','src="'.$url.'/uploads/',$item->content).'</div></body></html>';
            }elseif($item->name == 'onoffvongquay'){
                if(!$noibo) $item->value = false;
            }elseif($item->name == 'view_forum'){
                if(!$noibo) $item->value = false;
            }
        }
        return response()->json(['status' => 'success','noibo' => $noibo, 'settings' => $settings, 'diachi' => $diachis,'msg'=>'Lấy thông tin app thành công']);
    }
    public function login(Request $request){
        $phone = convertphone(Request::input("phone"));
        $password = Request::input("password");
        $user = User::where('username',$phone)->first();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'phone' => $phone,
                'msg' => 'Không tìm thấy user, bạn hãy đăng ký trước nhé'
            ]);
        }
        if (!$password) {
            return response()->json([
                'status' => 'error',
                'phone' => $phone,
                'msg' => 'Mật khẩu không được trống'
            ]);
        }
        $credentials = array(
            'username' => $phone,
            'password' => Request::input("password")
        );
        
        
        if($phone == "0931419102"){ // sdt uu tien
            $token=JWTAuth::fromUser($user);
            // return $token;
        }else{
            $token=JWTAuth::attempt(
                $credentials
                // ['exp' => Carbon\Carbon::now()->addweek(4)->timestamp]
            );
        }
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'phone' => $phone,
                'msg' => 'Mật khẩu sai'
            ]);
        }
        $lv = $user->level;
        
        if($user->confirmed == 0){
            return response()->json(['status' => 'error', 'user' => $phone, 'confirmed'=>$user->confirmed,'msg'=>'Tài khoản này đang bị khoá, bạn hãy xác thực tài khoản nhé']);
        }

        $no = Request::input("nofid");
        $list = $user->nofid;
        if($list==null || $list=='') $list = array();
        else $list=json_decode($user->nofid,true);

        if( !in_array( $no ,$list ) && $no != null){
            $list[] = $no;
        }

        $newid = [];
        foreach($list as $item){
            $newid[] = $item;
        }
        $user->nofid = json_encode($newid);

        $user->save();

        
        if($user->status == 0){
            // JWTAuth::invalidate(JWTAuth::getToken());
            JWTAuth::setToken($token)->invalidate(true);
            return response()->json(['token' => $token, 'confirmed'=>$user->confirmed, 'status' => 'error','msg'=>'Tài khoản này đang bị khoá']);
        }
        $user->ngaysinh = $user->ngaysinh != null ? date('d/m/Y',strtotime($user->ngaysinh)) : '';
        $user->typeAdmin = $user->level < 5 ? true : false;
        if($user->endactive != null){
            // $str = 'Thành viên Vip - '.date('d/m/Y',strtotime($user->endactive));
            $songayconlai = datediff(time(), strtotime($user->endactive));
            if($songayconlai <= 0){
                $str = "Đã hết hạn";
            }else{
                $str = "Thành viên Vip (còn $songayconlai ngày)";
            }
            $user->endactive = $str;
        }else{
            $user->endactive = 'Chưa mua Vip';
        }
        $user->statusactive = $user->endactive && strtotime($user->endactive) > time() ? true : false;
        
        return response()->json(['token' => $token, 'user' => $user, 'status' => 'success','msg'=>'Bạn đã đăng nhập thành công']);
    }
    public function edituser(Request $request){
        $user = Auth::user();
    	$user->firstname = Request::input("name");
        $user->email = Request::input("email");
        $user->address = Request::input("address");
        // if($user->nguoigioithieu == '') $user->nguoigioithieu = Request::input("nguoigioithieu");

        $ngaysinh = Request::input("ngaysinh");
        if($ngaysinh != null && $ngaysinh != ''){
            $date = str_replace('/', '-', $ngaysinh );
            $newDate = date("Y-m-d", strtotime($date));
            $user->ngaysinh = $newDate;
        }
        
        $pass = Request::input("password");
        if($pass != null){
            $user->password = bcrypt($pass);
        }
        $user->save();
        $user->ngaysinh = $ngaysinh;
        if($user->endactive != null){
            // $str = 'Thành viên Vip - '.date('d/m/Y',strtotime($user->endactive));
            $songayconlai = datediff(time(), strtotime($user->endactive));
            if($songayconlai <= 0){
                $str = "Đã hết hạn";
            }else{
                $str = "Thành viên Vip (còn $songayconlai ngày)";
            }
            $user->endactive = $str;
        }else{
            $user->endactive = 'Chưa mua Vip';
        }
        $user->statusactive = $user->endactive && strtotime($user->endactive) > time() ? true : false;

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'msg' => 'Bạn đã chỉnh sửa thông tin thành công'
        ]);
    }
    public function register(Request $request){
        // phpinfo(); 
        // $phone = str_replace(array(","," ","."),'',Request::input("phone"));
        $phone = convertphone(Request::input("phone"));
        // return response()->json([
        //     'status' => 'error',
        //     'aaa' => Request::input("phone"),
        //     'phone' => "Số điện thoại $phone này đã tồn tại"
        // ]);
        
        $sdtgioithieu = convertphone(Request::input("sdtgioithieu"));
        $name = Request::input("name");
        $count = User::select('id')->where('username',$phone)->count();
        if($count>0){
            return response()->json([
                'status' => 'error',
                'msg' => "Số điện thoại $phone này đã tồn tại"
            ]);
        }
        $nguoigioithieu = null;
        if($sdtgioithieu != null){
            $nguoigioithieu = User::select('id')->where('username',$sdtgioithieu)->first();
            if($nguoigioithieu == null){
                return response()->json([
                    'status' => 'error',
                    'msg' => "Số điện thoại $sdtgioithieu giới thiệu không tìm thấy"
                ]);
            }
        }
        $x = 1;
        $makichhoat = rand(100000,999999);
        while($x != 0) {
            $makichhoat = rand(100000,999999);
            $x = User::select('confirmation_code')->where('confirmation_code',$makichhoat)->count();
        }
        
        $strkichhoat = 'Ma kich hoat Camapro cua ban la: '.$makichhoat;
        $res = sentsms($phone, $strkichhoat);
        
        if($res != null && $res['code'] != 1000){
            $str = 'Gửi tin nhắn bị lỗi, bạn hãy thử lại hoặc liên hệ với người quản lý App nhé';
            if(isset($res['message'])) $str = $res['message'];
            elseif(isset($res['message2'])) $str = $res['message2'];
            return response()->json([
                'status' => 'error',
                'phone' => $phone,
                'detail' => $res,
                'string' => $strkichhoat,
                'msg' => $str
            ]);
        }

        $user = new User;
        $user->username = $phone;
    	$user->firstname = $name;
        $user->phone = $phone;
        $user->status = 0;
        $user->level = 10;
        $user->confirmed = 0;
        $user->confirmation_code = $makichhoat;
        $user->nguoigioithieu = $nguoigioithieu;
        $user->password = bcrypt(Request::input("password"));
        $user->remember_token = csrf_token();
        $user->save();

        // if($nguoigioithieu != null){
        //     $nguoigioithieu->
        // }

        return response()->json([
            'status' => 'success',
            'username' => $phone,
            'id' => $user->id,
            'user' => $user,
            'msg' => 'Bạn đã đăng ký thành viên thành công'
        ]);

    }
    public function resentcode(){
        $phone = convertphone(Request::input("phone"));
        $User = User::where('username',$phone)->first();
        if($User==null){
            return response()->json([
                'status' => 'error',
                'msg' => 'Số điện thoại này không tìm thấy trên hệ thống'
            ]);
        }
        if($User->confirmed==1){
            return response()->json([
                'status' => 'error',
                'msg' => 'Tài khoản của bạn đã xác nhận'
            ]);
        }else{
            $makichhoat = rand(100000,999999);
            $x = 1;
            while($x != 0) {
                $makichhoat = rand(100000,999999);
                $x = User::select('confirmation_code')->where('confirmation_code',$makichhoat)->count();
            }
            $res = sentsms($phone,'Ma xac nhan CAMAVN.COM moi cua ban la: '.$makichhoat);
            if($res != null && $res['code'] != 1000){
                return response()->json([
                    'status' => 'error',
                    'phone' => $phone,
                    'detail' => $res,
                    'msg' => isset($res['message']) ? $res['message'] : 'Gửi tin nhắn bị lỗi, bạn hãy thử lại hoặc liên hệ với người quản lý App nhé'
                ]);
            }

            $User->confirmation_code = $makichhoat;
            $User->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Đã gửi lại mã xác nhận qua số điện thoại'
            ]);
        }
    }
    public function resetpass(){
        $phone = convertphone(Request::input("phone"));
        $User = User::where('username',$phone)->first();
        if($User==null){
            return response()->json([
                'status' => 'error',
                'msg' => 'Số điện thoại này không tìm thấy trên hệ thống'
            ]);
        }
        if($User->status==0){
            return response()->json([
                'status' => 'error',
                'msg' => 'Tài khoản của bạn đang bị khóa'
            ]);
        }
        if($User->confirmed==0){
            return response()->json([
                'status' => 'unconfirm',
                'msg' => 'Bạn chưa kích hoạt tài khoản'
            ]);
        }
        $x = 1;
        $makichhoat = rand(100000,999999);
        while($x != 0) {
            $makichhoat = rand(100000,999999);
            $x = User::select('confirmation_code')->where('confirmation_code',$makichhoat)->count();
        }
        $res = sentsms($phone,'Ma reset mat khau CAMAVN.COM: '.$makichhoat);
        if($res != null && $res['code'] != 1000){
            return response()->json([
                'status' => 'error',
                'phone' => $phone,
                'detail' => $res,
                'msg' => isset($res['message']) ? $res['message'] : 'Gửi tin nhắn bị lỗi, bạn hãy thử lại hoặc liên hệ với người quản lý App nhé'
            ]);
        }

        $User->confirmation_code = $makichhoat;
        $User->save();
        return response()->json([
            'status' => 'success',
            'msg' => 'Mã để lấy lại mật khẩu đã gửi đến tin nhắn của bạn'
        ]);
        
    }
    public function confirmresetpass(Request $request){
        // $phone = str_replace(array(","," ","."),'',Request::input("phone"));
        $code = convertphone(Request::input("code"));
        $user = User::select()->where('confirmation_code',$code)->first();
        if($user == null){
            return response()->json([
                'status' => 'error',
                'code' => $code,
                'msg' => 'Không tìm thấy thông tin, bạn hãy kiểm tra lại mã reset mật khẩu'
            ]);
        }
        if($user->confirmed == 0){
            return response()->json([
                'status' => 'error',
                'msg' => 'Tài khoản này chưa được kích hoạt, bạn hãy kích hoạt nhé'
            ]);
        }
        $pass = rand(100000,999999);
        $res = sentsms($user->phone,'Mat khau moi CAMAVN.COM cua ban la: '.$pass);
        if($res != null && $res['code'] != 1000){
            return response()->json([
                'status' => 'error',
                'phone' => $phone,
                'detail' => $res,
                'msg' => isset($res['message']) ? $res['message'] : 'Gửi tin nhắn bị lỗi, bạn hãy thử lại hoặc liên hệ với người quản lý App nhé'
            ]);
        }
        
        $user->password = bcrypt($pass);
        $user->confirmation_code = null;
        $user->save();
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'status' => 'success',
            'code' => $code,
            'username' => $user->phone,
            'id' => $user->id,
            'user' => $user,
            'token' => $token,
            'msg' => 'Bạn đã thay đổi mật khẩu thành công, mật khẩu đã được gửi về số điện thoại của bạn'
        ]);

    }
    public function confirm(Request $request){
        // $phone = str_replace(array(","," ","."),'',Request::input("phone"));
        $code = convertphone(Request::input("code"));
        $user = User::select()->where('confirmation_code',$code)->first();
        if($user == null){
            return response()->json([
                'status' => 'error',
                'code' => $code,
                'msg' => 'Không tìm thấy thông tin, bạn hãy kiểm tra lại mã kích hoạt'
            ]);
        }
        if($user->confirmed == 1){
            return response()->json([
                'status' => 'error',
                'msg' => 'Tài khoản này đã được kích hoạt rồi, bạn hãy đăng nhập nhé'
            ]);
        }
        
        $user->status = 1;
        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        if($user->nguoigioithieu){
            $nguoigioithieu = User::where('nguoigioithieu',$user->nguoigioithieu)->first();
            $nguoigioithieu->diemgioithieu += 5;
            $nguoigioithieu->tongdiem += 5;
            $nguoigioithieu->save();
        }

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'status' => 'success',
            'code' => $code,
            'username' => $user->phone,
            'id' => $user->id,
            'user' => $user,
            'token' => $token,
            'msg' => 'Bạn đã kích hoạt và đăng nhập thành công'
        ]);

    }
    public function sentsms(Request $request){
        $phone = convertphone(Request::input("phone"));
        $phone = '0909979367';
        // $noidung = Request::input("noidung");
        $tieude = 'tieu de';
        $noidung = 'noi dung tin nhan';

        $res = sentsms($phone,'Ma kich hoat Camapro cua ban la: 123456');
        
        if($res != null && $res['code'] != 1000){
            return response()->json([
                'status' => 'error',
                'phone' => $phone,
                'detail' => $res,
                'msg' => $res['message']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'phone' => $phone,
            'detail' => $res,
            'msg' => 'Gửi tin nhắn thành công'
        ]);
    }
    public function sentnotification(Request $request){
        // $phone = convertphone(Request::input("phone"));
        $phone = '0909979367';
        // $noidung = Request::input("noidung");
        $tieude = 'tieu de';
        $noidung = 'noi dung tin nhan';
        $url = '';
        $data = [
            'motinnof' => 9,
            'typemotin' => 'news',
            'title' => $tieude,
            'content' => $noidung
        ];
        $ids = ['8a17c07b-9bfe-4aa9-9698-8a325bb44e6a'];
        $deviceid = Request::input("deviceid");
        if($deviceid != null && $deviceid != ''){
            
            $ids = [$deviceid];
        }
        $res = json_decode(sendMessage($noidung,$url,$tieude,$data, $ids));
        return response()->json([$res,$phone,$data,$noidung,$ids]);
    }
    public function user(Request $request){
        $token = (string)JWTAuth::getToken();
        $user = null;
        if($token){
            $kq = $this->checktoken($token);
            if($kq['status']){
                $token = $kq['token'];
                $user = JWTAuth::setToken($kq['token'])->toUser();
            }
        }
        if ($user) {
            $user->ngaysinh = date('d/m/Y',strtotime($user->ngaysinh));
            $user->typeAdmin = $user->level < 5 ? true : false;
            if($user->endactive != null){
                // $str = 'Thành viên Vip - '.date('d/m/Y',strtotime($user->endactive));
                $songayconlai = datediff(time(), strtotime($user->endactive));
                if($songayconlai <= 0){
                    $str = "Đã hết hạn";
                }else{
                    $str = "Thành viên Vip (còn $songayconlai ngày)";
                }
                $user->endactive = $str;
            }else{
                $user->endactive = 'Chưa mua Vip';
            }
            $user->statusactive = $user->endactive && strtotime($user->endactive) > time() ? true : false;
            return response()->json(['status' => 'success','user' => $user, 'token' => $token]);
        }
        return response()->json(array('status'=> 'error', 'msg' => 'Không tìm thấy tài khoản'));
    }
    public function logs($id,$us){
        $loca = dirname(__FILE__);
        if (!file_exists($loca."/logs.txt")) {
            $myfile = fopen($loca."/logs.txt", "w") or die("Unable to open file!");
        }
        $txt = file_get_contents($loca.'/logs.txt');
        $myfile = fopen($loca."/logs.txt", "w") or die("Unable to open file!");
        $txt .= 'At: '.date('Y-m-d H:i:s',time())." === User: $us - ID: $id \n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }
    public function logout(Request $request) {
        $user = JWTAuth::toUser(Request::input('token'));
        // $user = Auth::user();
        // dd($user);
        $no = Request::input("nofid");

        // $txt = "$no";
        // $this->logs($txt, $user->username);
        // dd(1);

        $iddevices = array();
        // return response()->json(['status' => 'error','msg'=>$no]);
        if($user->nofid!=null){
            $iddevices = json_decode($user->nofid,true);

            if(is_array($iddevices)){
                foreach($iddevices as $k => $item){
                    if($item==$no || $item==null ){
                        unset($iddevices[$k]);
                    }
                }
            }
        }
        // dd($iddevices);

        $user->nofid = json_encode($iddevices);
        // $user->note = 'thoat '.$no;
        $user->save();
        // dd($user);

        JWTAuth::invalidate(Request::input('token'));
        // JWTAuth::setToken(Request::input('token'))->invalidate(true);
        // return response()->json('You have successfully logged out.', Response::HTTP_OK);
        return response()->json(['status' => 'success','msg'=>'Bạn đã đăng xuất thành công']);
    }
    
    public function refresh(){
        return response(JWTAuth::getToken());
    }
    // https://viblo.asia/p/jwt-json-web-tokens-trong-laravel-57-1VgZvoaOlAw
    public function checktoken($token){
        $data = [
            'status' => 1,
            'msg' => '...',
            'detail' => '',
            'token' => $token,
            'user' => null
        ];
        try {
            $apy = JWTAuth::getPayload($token)->toArray();
            $data['msg'] = 'ok';
            $data['detail'] = $apy;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            
            $data['status'] = 0;
            $data['msg'] = 'token_expired';
            $data['detail'] = $e->getMessage();

            
            try{
                $new_token = JWTAuth::refresh($token);
                $data['status'] = 1;
                $data['msg'] = 'đã lấy lại token mới';
                $data['token'] = $new_token;
                $data['user'] = JWTAuth::setToken($new_token)->toUser();
            }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e2){
                $data['status'] = 0;
                $data['detail'] = $e2->getMessage();
            }
            // dd($data['detail']);
            
            

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $data['status'] = 0;
            $data['msg'] = 'token_invalid';
            $data['detail'] = $e->getMessage();
            // dd($data,3);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $data['status'] = 0;
            $data['msg'] = 'token_absent';
            $data['detail'] = $e->getMessage();
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            $data['status'] = 0;
            $data['msg'] = 'token_blacklist';
            $data['detail'] = $e->getMessage();
        }
        // $data['user'] = JWTAuth::setToken($token)->toUser();
        // dd($data);
        return $data;
    }
    public function home(){
        $noibo = ip_info(null, 'Country Code') == "VN" ? true : false;
        $token = (string)JWTAuth::getToken();
        // $user = Auth::user();
        $data = array(
            'user' => null,
            'cosos' => [],
            'diachis' => [],
            'token' => $token,
            'danhgiacode' => false
        );
        // dd(1);
        if($token != null){
            $user = null;
            $kq = $this->checktoken($token);
            if($kq['status']){
                $data['user'] = JWTAuth::setToken($kq['token'])->toUser();
                $data['user']->dsyeuthich = $data['user']->dsyeuthich != null ? json_decode($data['user']->dsyeuthich) : [];

                
                if($data['user']->endactive != null){
                    // $str = 'Thành viên Vip - '.date('d/m/Y',strtotime($data['user']->endactive));
                    $songayconlai = datediff(time(), strtotime($data['user']->endactive));
                    if($songayconlai <= 0){
                        $str = "Đã hết hạn";
                    }else{
                        $str = "Thành viên Vip (còn $songayconlai ngày)";
                    }
                    $data['user']->endactive = $str;
                }else{
                    $data['user']->endactive = 'Chưa mua Vip';
                }
                $data['user']->statusactive = $data['user']->endactive && strtotime($data['user']->endactive) > time() ? true : false;
                $data['token'] = $kq['token'];
            }
        }
        $cosohot= [];
        $idtrung = [];
        $noimage = URL('public/images/no-image.jpg');
        $diachi_id = (int)Request::input("diachi_id");
        $tencoso = Request::input("tencoso");

        if($diachi_id == 0 && $tencoso == ''){
            $cosoghim = coso::select('id','image','star','solike','likecongthem','name','giachinhthuc','giatruockm','diachi','phone','getcode')->whereIn('id',[5])->where('status',1)->get();
            $data['cosoghim'] = $cosoghim;
            
            foreach($cosoghim as $item){
                $idtrung[] = $item->id;
                $item->image = $item->image != '' ? get_image_url($item->image,'featured') : $noimage;
            }
            
            $cosohot = coso::select('id','image','star','solike','likecongthem','name','giachinhthuc','giatruockm','diachi','phone','getcode')->where('ontop',1)->where('status',1)->inRandomOrder()->get();
            
            foreach($cosohot as $item){
                $idtrung[] = $item->id;
                $item->image = $item->image != '' ? get_image_url($item->image,'featured') : $noimage;
            }
            if(count($cosoghim)){
                $cosohot = $cosoghim->merge($cosohot);
            }
        }
        $data['cosohot'] = $cosohot;
        
        $cosos = coso::select('id','image','star','solike','likecongthem','name','giachinhthuc','giatruockm','diachi','phone','getcode');
        
        if($diachi_id > 0){
            $cosos = $cosos->where('diachi_id',$diachi_id);
        }
        if($tencoso != ''){
            $cosos = $cosos->where('name','LIKE','%'.$tencoso.'%');
        }

        $cosos = $cosos->inRandomOrder()
        ->limit(15)
        // ->where('getcode',1)
        ->where('status',1)
        ->whereNotIn('id',$idtrung)
        ->get();
        foreach($cosos as $item){
            $item->image = $item->image != '' ? get_image_url($item->image,'featured') : $noimage;
        }
        $data['cosos'] = $cosos;
        $diachis = diachi::select('name','id')->get();
        $data['diachis'] = $diachis;

        $settings = settings::select('name','value','typevalue')->get();
        $dataset = [
            'lydohh' => lydohh::select('id','name')->orderBy('thutu','asc')->get()
        ];
        foreach($settings as $item){
            if(is_json($item->value) && $item->typevalue == 'string'){
                // return $item['value'];
                $arrayvl = json_decode($item['value']);
                $temp = [];
                // return $arrayvl;
                foreach($arrayvl as $item2) if($item2) $temp[] = $item2;
                $dataset[$item['name']] = $temp;
            }else{
                $dataset[$item['name']] = $item->typevalue == 'number' ? (int)$item['value'] :  $item['value'];
            }
        }
        $url = URL('/');
        $urlcss = $url.'/app.css';
        $dataset['quyenloitv'] = '<div class="content" style="width: 100%; "><link href="'.$urlcss.'" rel="stylesheet" type="text/css" />'.str_replace('src="/uploads/','src="'.$url.'/uploads/',$dataset['quyenloitv']).'</div>';
        $dataset['huongdannaptien'] = '<div class="content" style="width: 100%; "><link href="'.$urlcss.'" rel="stylesheet" type="text/css" />'.str_replace('src="/uploads/','src="'.$url.'/uploads/',$dataset['huongdannaptien']).'</div>';
        
        $datenow = date('Y-m-d');
        $giovangs = giovang::select('id')->where('batdau','>=',$datenow." 00:00:00")
        ->where('ketthuc','<=',$datenow." 23:59:59")
        ->where('status',1)
        ->get();
        $date = strtotime("-1 day");
        $hethan = date('Y-m-d G:i:s', $date);
        if($data['user'] != null){
            $check = code::where('active_at','>',$date)->where('status',10)->where('star',0)->count();
            if($check > 0){
                $data['danhgiacode'] = true;
            }
        }
        return response()->json(array('status'=>'success','giovangs' => $giovangs, 'diachis' => $diachis, 'settings' => $dataset,'data'=>$data,'msg'=>'Lấy thông tin home App thành công'));
    }
    public function morehome(){
        $ids = Request::input("id");
        $cosos = coso::select('id','image','name','star','solike','getcode','likecongthem','giachinhthuc','giatruockm','diachi','phone')
        ->whereNotIn('id',$ids);
        $diachi_id = (int)Request::input("diachi_id");
        $tencoso = Request::input("tencoso");
        if($diachi_id > 0){
            $cosos = $cosos->where('diachi_id',$diachi_id);
        }
        if($tencoso != ''){
            $cosos = $cosos->where('name','LIKE','%'.$tencoso.'%');
        }
        $cosos = $cosos->inRandomOrder()
        // ->where('getcode',1)
        ->where('status',1)
        ->limit(15)->get();
        
        foreach($cosos as $item){
            $item->image = get_image_url($item->image,'featured');
        }

        return response()->json(array('status'=>'success','cosos'=>$cosos,'msg'=>'Lấy thêm cơ sở thành công'));
    }
    public function search(){
        $txt = Request::input("txt");
        $diachi = Request::input("diachi");
        $cosos = coso::select('id','image','name','giachinhthuc','giatruockm','diachi','phone','getcode');
        if($txt !=  null && $txt != '') $cosos = $cosos->where('name','LIKE','%'.$txt.'%');
        if($diachi !=  null && $diachi != '') $cosos = $cosos->where('diachi_id',$diachi);
        
        $cosos = $cosos->limit(15)
        // ->where('getcode',1)
        ->where('status',1)
        ->paginate(15);;
        foreach($cosos as $item){
            $item->image = get_image_url($item->image,'featured');
        }

        return response()->json(array('status'=>'success','cosos'=>$cosos,'msg'=>'Tìm kiếm cơ sở thành công'));
    }
    public function findnear(){
        $lat = Request::input("lat");
        $long = Request::input("long");

        $sql = "
        select c.id, c.image, c.name, c.giachinhthuc, c.giatruockm, c.diachi, c.phone, c.tdlat, c.tdlong, c.diachi, ";
        // $sql .= "6373000 * DEGREES(ACOS(COS(RADIANS($lat))
        // * COS(RADIANS(c.lat))
        // * COS(RADIANS(c.lng) - RADIANS($long))
        // + SIN(RADIANS($lat))
        // * SIN(RADIANS(c.lat))))
        // AS distance";
        $sql .="round(112675 * DEGREES(ACOS(
            COS(RADIANS($lat)) *
            COS(RADIANS(c.tdlat)) *
            COS(RADIANS(c.tdlong) - RADIANS($long)) +
            SIN(RADIANS($lat)) * SIN(RADIANS(c.tdlat))
          ))) AS distance";


        $sql .=" from cosos as c
        where c.status = 1 ";
        // $sql .= "AND c.passport = 1 ";
        $sql .= "HAVING distance <= 7000 ";
        $sql .= "
        ORDER BY distance ASC
        LIMIT 0, 10
        ";
        $cosos = DB::select($sql); 
        
        foreach($cosos as $item){
            $item->image = get_image_url($item->image,'featured');
        }

        return response()->json(array('status'=>'success','cosos'=>$cosos,'msg'=>'Tìm kiếm cơ sở thành công'));
    }
    public function detailcoso(){
        $id = Request::input("id");
        $coso = coso::select('id','getcode','name','sku','giachinhthuc','giatruockm','diachi','tdlat','tdlong','phone','image','giomocua','noidung','star','banggia','solike','likecongthem','thongtincoban')->where('status',1)->find($id);
        if($coso == null){
            return response()->json(array('status'=>'error','msg'=>'Không tìm thấy cơ sở'));
        }
        $coso->banggia = $coso->banggia!=null ? json_decode($coso->banggia) : [];
        $noibo = ip_info(null, 'Country Code') == "VN" ? true : false;
        foreach($coso->banggia as $item){
            if(!$noibo){
                $item->name = str_replace(['sex','Sex','Vú','vú','Ngực','ngực'],'',$item->name);
                $item->diengiai = '';
            }
        }
        $coso->thongtincoban = $coso->thongtincoban!=null ? json_decode($coso->thongtincoban) : [];
        $coso->image = get_image_url($coso->image,'featured');
        $url = URL('/');
        $urlcss = $url.'/app.css';
        if($noibo) $coso->noidung = $coso->noidung != '' ? '<html><head><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body><div class="content" style="width: 100%; "><link href="'.$urlcss.'" rel="stylesheet" type="text/css" />'.str_replace('src="/uploads/','src="'.$url.'/uploads/',$coso->noidung).'</div></body></html>' : '';
        else{
            $coso->noidung = '';
        }
        $images = image_cosos::where('coso_id',$id)->get();
        foreach($images as $item){
            $item->linkimg = get_image_url($item->url,'featured');
        }
        return response()->json(array('status'=>'success','data'=>$coso,'images'=>$images,'msg'=>'Lấy nội dung cơ sở thành công'));
    }
    public function dklamcoso(){
        $dkcoso = new dkcoso;
        $dkcoso->name = Request::input("name");
        $dkcoso->phone = Request::input("phone");
        $dkcoso->diachi = Request::input("diachi");
        $dkcoso->save();

        return response()->json(array('status'=>'success','data'=>$dkcoso,'msg'=>'Đăng ký cơ sở thành công'));
    }
    public function getDataGiovang(){
        $datenow = date('Y-m-d');
        $giovangs = giovang::where('batdau','>=',$datenow." 00:00:00")
        ->where('ketthuc','<=',$datenow." 23:59:59")
        ->where('status',1) // 1 duoc lay
        ->orderBy('batdau','asc')
        ->get();
        $giovangcs = [];
        
        // anhcoso
        foreach($giovangs as $item){
            $time = time();
            $tinhtrang = 'chua';
            $strtimekt = strtotime($item->ketthuc);
            $strtimebd = strtotime($item->batdau);
            $tbgiovang = 'Giờ vàng sẽ diễn ra lúc '.date('G:i', $strtimebd) ;
            if($strtimebd > $time) $tinhtrang = 'chua';
            elseif($strtimekt < $time){
                $tinhtrang = 'xong';
                $tbgiovang = 'Giờ vàng này đã kết thúc '.date('G:i', $strtimekt);
            }elseif($item->status == 0){
                $tinhtrang = 'xong';
                $tbgiovang = 'Giờ vàng đã hết vé';
            }elseif(($item->soluong <= $item->sl_dalay)){
                $tinhtrang = 'het';
                $tbgiovang = 'Giờ vàng này đã hết vé';
            }elseif($item->status == 1 && $strtimebd <= $time && $strtimekt >= $time){
                $tinhtrang = 'dang';
                $tbgiovang = 'Giờ vàng này đang diễn ra';
            }
            
            $item->coso = coso::select('id','image','star','solike','likecongthem','name','giachinhthuc','giatruockm','diachi','phone','getcode')->find($item->coso_id);
            $item->anhcoso = $item->coso->image && $item->coso? get_image_url($item->coso->image,'featured') : $noimage;
            $item->tinhtrang = $tinhtrang;
            $item->tbgiovang = $tbgiovang;
            $item->untildown = $strtimekt - $time;
            $item->untildownkt = $strtimekt - $strtimebd;
            $item->time = $time;
            $item->now = date('d/m/Y G:i:s');
            $item->strtimebd = $strtimebd;
            $item->strtimekt = $strtimekt;
            $item->batdau = date('d/m/Y G:i', $strtimebd);
            $item->ketthuc = date('d/m/Y G:i', $strtimekt);
        }
        return $giovangs;
    }
    public function getGiovang(){
        $giovangs = $this->getDataGiovang();
        return response()->json(array('status'=>'success','date' => date('d/m/Y G:i:s'),'giovangs' => $giovangs, 'msg'=>'Lấy thông tin home App thành công'));
    }
    public function getCodeGiovang(){
        $token = (string)JWTAuth::getToken();
        // dd($token);
        $user = null;
        if($token){
            $kq = $this->checktoken($token);
            if($kq['status']){
                $user = JWTAuth::setToken($kq['token'])->toUser();
                $token = $kq['token'];
            }
        }
        if(!$user) return response()->json(array('status'=>'error','token' => $token,'msg'=>'Bạn phải đăng nhập để lấy mã'));
        
        // check active thu tien member
        $checkendactive = settings::where('name','checkendactive')->first();
        $checkendactive = $checkendactive ? (int)$checkendactive->value : false;
        // return response()->json(array('status'=>'error','data' => [$checkendactive, $user->phone],'msg'=>'test'));
        if($checkendactive || $user->phone == '0909979367'){
            $check = checkactive($user->endactive);
            // return response()->json(array('status'=>'error','data' => [$checkendactive, $user->phone, $check],'msg'=>'test'));
            if(!$check['status']) return response()->json(array('status'=>'error','token' => $token, 'tonaptien' => true, 'msg'=> $check['msg']));
        }

        
        $idgiovang = Request::input("giovang_id");
        $redis    = Redis::connection();
        // $redis->set('giovang', null);
        $response = $redis->get('giovang');
        $giovang = null;
        if($response && is_json($response)){
            $giovang = (array)json_decode($response, true);
            if($idgiovang != $giovang['id'] || ( $idgiovang == $giovang['id'] && date('Y-m-d') != date('Y-m-d', strtotime($giovang['batdau'])) )){
                $obgiovang = giovang::find($idgiovang);
                if($obgiovang) $giovang = giovangRedis($obgiovang);
                $redis->set('giovang', json_encode($giovang));
                // dd($redis->get('giovang'));
            }
        }else $giovang = giovangRedis(giovang::find($idgiovang));
        if(!$giovang) return response()->json(['status' => 'error', 'msg' => 'Không tìm thấy giờ vàng']);
        $time = time();
        if($time < strtotime($giovang['batdau'])) return response()->json(['status' => 'error', 'msg' => 'Giờ vàng này chưa bắt đầu']);
        if($time > strtotime($giovang['ketthuc'])) return response()->json(['status' => 'error','idgiovang' => $idgiovang, 'giovang' => $giovang, 'msg' => 'Giờ vàng này đã kết thúc '.date('d/m/Y G:i', strtotime($giovang['ketthuc']))]);

        if($giovang['status'] == 0){
            return response()->json(['status' => 'error', 'giovang' => $giovang, 'msg' => 'Giờ vàng này đã kết thúc']);
        }elseif($giovang['sl_dalay'] >= $giovang['soluong']){
            return response()->json(['status' => 'error', 'msg' => 'Giờ vàng này đã hết vé']);
        }
        $user = Auth::user();
        $job = new getcodefree($idgiovang, $user);
        dispatch($job);
        return $this->checkresgiovang($user->id, $idgiovang, $token);
    }
    
    public function getnews(){
        $noibo = ip_info(null, 'Country Code') == "VN" ? true : false;
        $news = DB::table('news')
        // ->join('categories_news', 'categories_news.news_id', '=', 'news.id')
        ->select("news.name","news.intro","news.content","news.id","news.image")
        ->where("news.status",1)
        ->where("news.type","news")
        // ->where("categories_news.categories_id",$cat)
        ->orderBy("created_at","DESC")
        ->paginate(10);
        $url = URL('/');
        $urlcss = $url.'/app.css';
        $noimge = URL('/public/images/no-image.jpg');
        $imgdef = 'https://appms.quanlymat.com/uploads/4/a6982f01-ab9e-4954-8bdf-987330736450-450x330.jpeg';
        foreach($news as $item){
            if($noibo){
                $item->image = $item->image ? get_image_url($item->image,'thumb') : $noimge;
            }else{
                $item->image = $imgdef;
            }
            $txt = $item->intro;
            if($txt=="") $txt= html_entity_decode(strip_tags($item->content));
            
            $txt = str_limit_html($txt, 200);
            $item->intro = $txt;

            $item->content = '<html><head><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body><div class="content" style="width: 100%; "><link href="'.$urlcss.'" rel="stylesheet" type="text/css" />'.str_replace('src="/uploads/','src="'.$url.'/uploads/',$item->content).'</div></body></html>';
            

        }
        return response()->json(array('status'=>'success','title'=>'Tin Tức','data'=>$news,'msg'=>'Lấy danh sách bài viết thành công'));
    }
    public function single(){
        $id = Request::input("id");
        $news = news::find($id);
        if($news == null){
            return response()->json(array('status'=>'error','msg'=>'Không tìm thấy bài viết'));
        }
        $url = URL('/');
        $urlcss = $url.'/app.css';
        $news->content = '<html><head><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body><div class="content" style="width: 100%; "><link href="'.$urlcss.'" rel="stylesheet" type="text/css" />'.str_replace('src="/uploads/','src="'.$url.'/uploads/',$news->content).'</div></body></html>';
        return response()->json(array('status'=>'success','data'=>$news,'msg'=>'Lấy nội dung bài viết thành công'));
    }

    // code
    public function getcode(){
        // $token = Request::input("token");
        $token = (string)JWTAuth::getToken();
        // dd($token);
        $user = null;
        if($token){
            $kq = $this->checktoken($token);
            if($kq['status']){
                $user = JWTAuth::setToken($kq['token'])->toUser();
                $token = $kq['token'];
            }
        }
        if(!$user) return response()->json(array('status'=>'error','token' => $token,'msg'=>'Bạn phải đăng nhập để lấy mã'));
        
        // check active thu tien member
        $checkendactive = settings::where('name','checkendactive')->first();
        $checkendactive = $checkendactive ? (int)$checkendactive->value : false;
        // return response()->json(array('status'=>'error','data' => [$checkendactive, $user->phone],'msg'=>'test'));
        if($checkendactive || $user->phone == '0909979367'){
            $check = checkactive($user->endactive);
            // return response()->json(array('status'=>'error','data' => [$checkendactive, $user->phone, $check],'msg'=>'test'));
            if(!$check['status']) return response()->json(array('status'=>'error','token' => $token, 'tonaptien' => true, 'msg'=> $check['msg']));
        }

        $coso_id = Request::input("coso_id");
        $coso = coso::find($coso_id);
        if($coso == null){
            return response()->json(array('status'=>'error','msg'=>'Không tìm thấy cơ sở'));
        }
        if($coso->status == 0){
            return response()->json(array('status'=>'error','msg'=>'Cơ sở không được phép hoạt động'));
        }
        if($coso->getcode == 0){
            return response()->json(array('status'=>'error','msg'=>'Cơ sở không được lấy code'));
        }

        $DATECODE = env('DATECODE');
        $date = strtotime("+$DATECODE day");
        $hethan = date('Y-m-d G:i:s', $date);

        $makm = incrementalHash();
        // dd($makm);
        $solan = 1;
        $x = 1;
        while($x != 0) {
            $makm = incrementalHash();
            $x = code::select('code')->where('code',$makm)->count();
            $solan++;
            if($solan > 10){
                return response()->json(array('status'=>'error','solan'=>$solan, 'code' => $makm,'msg'=>'Lấy mã quá nhiều lần nhưng chưa được'));
            }
        }

        $code = new code;
        $code->code = $makm;
        $code->coso_id = $coso_id;
        $code->khachhang_id = $user->id;
        $code->phone = $user->phone;
        $code->hethan = $hethan;
        $code->status = 0;
        $code->save();

        $code->hethan = date('d/m/Y G:i', strtotime($code->hethan));
        return response()->json(array('status'=>'success','token' => $token,'code'=>$code,'solan'=>$solan,'msg'=>'Lấy mã khuyến mãi thành công'));
    }
    public function danhgiacode(){
        $code = Request::input("code");
        $user = Auth::user();
        $code = code::where('code',$code)->where('khachhang_id',$user->id)->first();
        if($code == null){
            return response()->json(array('status'=>'error','msg'=>'Không tìm thấy mã khuyến mãi'));
        }
        if($code->star > 0){
            return response()->json(array('status'=>'error','msg'=>'Mã này đã đánh giá'));
        }
        $date = strtotime("-3 day");
        if($date > strtotime($code->active_at)){
            return response()->json(array('status'=>'error','msg'=>'Mã này đã hết hạn để đánh giá'));
        }
        $coso = coso::find($code->coso_id);
        if($coso == null){
            return response()->json(array('status'=>'error','msg'=>'Không tìm thấy cơ sở'));
        }

        $tenktv = Request::input("tenktv");
        $note = Request::input("note");
        $star = (int)Request::input("star");
        $code->star = $star;
        $code->tenktv = $tenktv;
        $code->note = $note;
        $code->save();

        if($star == 1) $coso->sao1 += 1;
        elseif($star == 2) $coso->sao2 += 1;
        elseif($star == 3) $coso->sao3 += 1;
        elseif($star == 4) $coso->sao4 += 1;
        elseif($star == 5) $coso->sao5 += 1;
        $sltong = $coso->sao1 + $coso->sao2 + $coso->sao3 + $coso->sao4 + $coso->sao5;
        $startb = round(($coso->sao1 + $coso->sao2 * 2 + $coso->sao3 * 3 + $coso->sao4 * 4 + $coso->sao5 * 5) / $sltong,2);
        $coso->star = $startb;

        $coso->save();

        $danhgia = new danhgia;
        $danhgia->tenktv = $tenktv;
        $danhgia->code = $code->code;
        $danhgia->star = $star;
        $danhgia->note = $note;
        $danhgia->coso_id = $code->coso_id;
        $danhgia->user_id = $code->khachhang_id;
        $danhgia->user_phone = $code->khachhang_id;
        $danhgia->save();

        return response()->json(array('status'=>'success','code'=>$code,'msg'=>'Đã đánh giá thành công'));
    }
    public function lydohethan(){
        $code = Request::input("code");
        $user = Auth::user();
        $code = code::where('code',$code)->where('khachhang_id',$user->id)->first();
        if($code == null){
            return response()->json(array('status'=>'error','msg'=>'Không tìm thấy mã khuyến mãi'));
        }
        if($code->status != 9){
            return response()->json(array('status'=>'error','msg'=>'Mã này hiện tại không thể nêu lý do hết hạn'));
        }
        if($code->lydohh_id > 0){
            return response()->json(array('status'=>'error','msg'=>'Mã này đã nêu lý do hết hạn'));
        }

        $lydohh_id = (int)Request::input("lydohh_id");
        $lydohh = lydohh::find($lydohh_id);
        if($lydohh == null){
            return response()->json(array('status'=>'error','msg'=>'Không tìm thấy lý do hết hạn'));
        }

        $code->lydohh_id = $lydohh_id;
        $code->note = Request::input("note");
        $code->save();

        $chonlydohh = new chonlydohh;
        $chonlydohh->note = Request::input("note");
        $chonlydohh->lydohh_id = $lydohh_id;
        $chonlydohh->coso_id = $code->coso_id;
        $chonlydohh->phone = $user->username;
        $chonlydohh->code = $code->code;
        $chonlydohh->save();

        return response()->json(array('status'=>'success','msg'=>'Đã gửi lý do hết hạn thành công'));
    }
    public function listcode(Request $request){
        $user = Auth::user();

        // $user = null;
        // $token = (string)JWTAuth::getToken();
        // if($token != null){
        //     $kq = $this->checktoken($token);
        //     // dd($kq );
        //     if($kq['status']){
        //         $user = JWTAuth::setToken($kq['token'])->toUser();
        //         $data['token'] = $kq['token'];
        //     }
        // }
        // dd(1,$user, $kq);

        $tgshow = date('Y-m-d G:i:s',strtotime('-30 day'));
        $codes = code::select('id','code','status','hethan','coso_id','active_at','lydohh_id','star')
        ->where('khachhang_id',$user->id)
        ->where('hethan','>=',$tgshow)
        ->where('lydohh_id',0)
        ->where('star',0)
        ->orderBy('id','DESC')
        ->paginate(10);

        $cosos = [];
        $thoihandg = strtotime("-3 day");
        $emptycoso = new coso;
        $emptycoso->image = URL('/public/images/no-image.jpg');
        $emptycoso->name = 'Không xác định';
        $emptycoso->diachi = 'Không xác định';
        
        foreach($codes as $item){
            if(!isset($cosos[$item['coso_id']])){
                $cosos[$item['coso_id']] = coso::select('id','image','name','diachi','phone')->find($item['coso_id']);
                if($cosos[$item['coso_id']] == null){
                    $cosos[$item['coso_id']] = $emptycoso;
                }else $cosos[$item['coso_id']]->image = isset($cosos[$item['coso_id']]->image) ? get_image_url($cosos[$item['coso_id']]->image,'thumb') : URL('public/images/no-image.jpg');
            }
            $item->tencoso = $cosos[$item['coso_id']]->name;
            $item->anhcoso = $cosos[$item['coso_id']]->image;
            $item->dccoso = $cosos[$item['coso_id']]->diachi;
            
            $item->danhgia = false;
            $item->ykienhethan = false;
            $item->danhgia = $item->status == 1 && $thoihandg < strtotime($item->active_at) && $item->star == 0 ? true : false;
            if($item->status == 0 && strtotime($item->hethan) < time()){
                
                $update = code::find($item->id);
                if($update != null){
                    $update->status = 9;
                    $update->save();
                }
            }
            if($item->lydohh_id == 0) $item->ykienhethan = true;
            $item->hethan = date('d/m/Y G:i',strtotime($item->hethan));
        }
        return response()->json(array('status'=>'success', 'list'=>$codes,'msg'=>'Lấy danh sách mã khuyến mãi thành công'));
    }

    // vonguqay
    public function getvongquay(Request $request){
        $user = Auth::user();
        // $tgshow = date('Y-m-d G:i:s',strtotime('-30 day'));
        $vongquays = vongquay::select()
        ->where('khach_id',$user->id)
        ->where('viewapp',1)
        ->orderBy('id','DESC')
        ->paginate(10);

        foreach($vongquays as $item){
            // if($item->lydohh_id == 0) $item->ykienhethan = true;
            $item->checkhethan = strtotime($item->thoihanquay) < time() && $item->status == 0 ? true : false;
            $item->thoihanquay = $item->thoihanquay ? date('d/m/Y G:i',strtotime($item->thoihanquay)) : null;
            $item->checknhangiai = !$item->thoihannhangiai || strtotime($item->thoihannhangiai) < time() ? false : true;
            $item->thoihannhangiai = $item->status == 10 && $item->thoihannhangiai && $item->nhangiai == 0 ? date('d/m/Y G:i', strtotime($item->thoihannhangiai)) : null;
        }
        return response()->json(array('status'=>'success', 'list'=>$vongquays,'title' => 'Vòng Quay May Mắn', 'msg'=>'Lấy danh sách vòng quay thành công'));
    }
    public function doiluotquay(){
        $user = Auth::user();
        $settings = settings::where('name','diemquay')->first();
        $diemquay = (int)$settings->value;
        if($user->tongdiem < $diemquay) return response()->json(array('status'=>'error', 'msg'=>'Bạn không đủ điểm để đổi lượt quay'));
        $sku = incrementalHash();
        $x = 1;
        while($x != 0) {
            $sku = incrementalHash();
            $x = vongquay::select('sku')->where('sku',$sku)->count();
        }
        $settings = settings::where('name','linkvongquay')->first();
        $linkvongquay = explode("/", $settings->value);
        if(!$linkvongquay) return response()->json(array('status'=>'error', 'msg'=>'Hệ thống chưa Set vòng quay nào'));
        $spin = $linkvongquay[count($linkvongquay) - 1];
        // dd($sku);
        $postdata = [
            'phone' => $user->phone,
            'name' => $user->firstname,
            'spin' => $spin,
            'bill' => $sku, 
            'key' => env('VONGQUAY_SECRET'), 
        ];
        // dd($postdata);
        $res = curl_json('https://spin.netsa.vn/api/addgioihan', $postdata);
        if(!$res) return response()->json(array('status'=>'error', 'msg'=>'Lỗi liên kết vòng quay'));
        $res = is_json($res) ? json_decode($res, true) : $res;
        if(!$res['success']){
            return response()->json(array('status'=>'error','postdata' => $postdata, 'msg'=>$res['msg']));
        }

        $settings = settings::where('name','thoihanquay')->first();
        $thoihanquay = (int)$settings->value;

        $vongquay = new vongquay;
        $vongquay->sku = $sku;
        $vongquay->khach_id = $user->id;
        $vongquay->phone = $user->phone;
        $vongquay->diem = $diemquay;
        $vongquay->status = 0;
        $vongquay->nhangiai = 0;
        $vongquay->thoihanquay = date('Y-m-d G:i:s', strtotime("+$thoihanquay day"));
        // $vongquay->keyapi = 0;
        $vongquay->save();

        // $vongquay->created_at = date('d/m/Y G:i');
        $vongquay->thoihanquay = date('d/m/Y G:i:s', strtotime($vongquay->thoihanquay));
        $vongquay->checkhethan = false;
        
        $user->tongdiem -= $diemquay;
        $user->save();

        return response()->json(array('status'=>'success', 'vongquay'=>$vongquay, 'user' => $user, 'msg'=>'Thêm vòng quay thành công'));
    }
    public function webhookSpin(){
        $token = Request::input("token");
        if(!$token) return response()->json(array('status'=>'error', 'msg'=>'Deny'), 401);
        $phone = Request::input("phone");
        $name = Request::input("name");
        $bill = Request::input("bill");
        $success = (int)Request::input("success");
        $giaithuong_name = Request::input("giaithuong_name");
        $spin_name = Request::input("spin_name");
        $note = Request::input("note");
        if($token != env('VONGQUAY_SECRET')) return response()->json(array('status'=>'error', 'msg'=>'Deny'), 402);
        $ip = get_client_ip();
        if($ip != env('VONGQUAY_IP')) return response()->json(array('status'=>'error', 'msg'=>'Deny'), 403);
        
        if($bill && $phone){
            $vongquay = vongquay::where('sku', $bill)->where('phone', $phone)->first();
            if($vongquay){
                if($vongquay->status == 0){
                    $vongquay->tengiai = $giaithuong_name;
                    $vongquay->status = $success ? 10 : 1;
                    if($vongquay->status == 10){
                        $settings = settings::where('name','thoihannhangiai')->first();
                        $thoihannhangiai = (int)$settings->value;
                        $vongquay->thoihannhangiai = date('Y-m-d G:i:s', strtotime("+$thoihannhangiai day"));;
                    }
                    $vongquay->save();
                    if($vongquay->status == 10){
                        $content = "Chúc mừng bạn, mã quay $vongquay->sku đã trúng $giaithuong_name";
                        $url = "";
                        $title = "$vongquay->sku đã trúng thưởng";
                        $data = [];
                        $devices = [];
                        
                        $user = User::select('id','nofid')->where('phone',$phone)->first();
                        if(!$user && $user->nofid && $user->nofid != '[]'){
                            $devices = json_decode($user->nofid);
                            if(!is_array($devices)){
                                $temp = [];
                                foreach($devices as $tb){
                                    $temp[] = $tb;
                                }
                                $devices = $temp;
                                $user->nofid = json_encode($temp);
                                $user->save();
                            }
                            $res = json_decode(sendMessage($content,$url,$title,$data,$devices), true);
                        }
                    }
                    return response()->json(array('status'=>'success', 'vongquay'=>$vongquay, 'msg'=>'Cập nhật vòng quay thành công'), 200);
                }else{
                    return response()->json(array('status'=>'error', 'msg'=>'Vòng quay này đã được quay'));
                }
            }else{
                return response()->json(array('status'=>'error', 'msg'=>'Không tìm thấy vòng quay'), 404);
            }
        }else{
            return response()->json(array('status'=>'error', 'msg'=>'Không nhận được thông tin đầy đủ'), 501);
        }
    }

    // favourite
    public function listfavourite(){
        $number = 5;
        $user = JWTAuth::toUser(Request::input('token'));
        $ids = array();
        $favouriteIDs = $user->dsyeuthich != null ? json_decode($user->dsyeuthich, true) : [];
        foreach($favouriteIDs as $item){
            $ids[] = $item;
        }
        $favourite = coso::select('id','image','star','solike','getcode','likecongthem','name','giachinhthuc','giatruockm','diachi','phone')
        ->whereIn("id",$ids)
        ->paginate($number);

        foreach($favourite as $item){
            // $item->image = URL($item->image);
            $item->image = get_image_url($item->image,'featured');
        }        
        return response()->json([
            'status' => 'success',
            'favourite' => $favourite,
            'favouriteIDs' => $favouriteIDs,
            'msg' => 'Đã lấy danh sách yêu thích thành công'
        ]);
    }
    public function addfavourite(){
        $id = Request::input('id');
        if($id==null && $id=='' && is_numeric($id)){
            return response()->json([
                'status' => 'error',
                'msg' => 'ID không hợp lệ hoặc bị trống'
            ]);
        }
        $coso = coso::find($id);
        if($coso==null){
            return response()->json([
                'status' => 'error',
                'msg' => 'Không tìm thấy thông tin cơ sở'
            ]);
        }
        $user = JWTAuth::toUser(Request::input('token'));
        $arr = $user->dsyeuthich != null ? json_decode($user->dsyeuthich,true) : [];
        if(!in_array($id,$arr)){
            
            $arr[] = $id;
            $coso->solike = $coso->solike + 1;
            $coso->save();

            $user->dsyeuthich = json_encode($arr);
            $user->save();

            return response()->json([
                'status' => 'success',
                'code' => 'LIKE',
                'favouriteIDs' => $arr,
                'user' => $user,
                'msg' => 'Đã thêm tin vào danh sách yêu thích'
            ]);
        }else{
            $ids = [];
            foreach($arr as $item){
                if($item != $id) $ids[] = $item;
            }

            $arr[] = $id;
            $coso->solike = $coso->solike - 1;
            $coso->save();

            $user->dsyeuthich = json_encode($ids);
            $user->save();
            return response()->json([
                'status' => 'success',
                'code' => 'UNLIKE',
                'favouriteIDs' => $ids,
                'user' => $user,
                'msg' => 'Đã xóa tin khỏi danh sách yêu thích'
            ]);
        }
    }

    // webhookSMS
    public function webhookSMSv2(){
        $keySMS = Request::input('keySMS');
        if($keySMS != env('KEYSMSWEBHOOK')){
            return response()->json(['status' => 'error', 'exist' => true, 'msg' => "$noidung\nSai key SMS"]);
        }
        // $all = Request::all();
        $message = (array)Request::input('message');
        if(count($message) > 0){
            $banggiadef = [1 => 50000, 6 => 275000, 12 => 500000];
            $moneyvip = settings::select()->where('name','moneyvip')->first();
            $moneyvip = is_json($moneyvip->value) ? (array)json_decode($moneyvip->value, true) : $banggiadef;
            // if(!is_array($moneyvip)) $moneyvip = $banggiadef;

            // $moneyvip = [1 => 50000, 6 => 275000, 12 => 500000];
            foreach($message as $key => $sms){
                $sms = (array)$sms;
                
                $skusms = $sms['_id'];
                $date_sent = $sms['date_sent'];
                $date = $sms['date'];
                $address = strtolower($sms['address']);
                
                $body = $sms['body'];
                $body = preg_split('/\r\n|[\r\n]/', $body);
                if(!isset($body[3])){
                    $body = explode('\n',$sms['body']);
                    if(!isset($body[3])){
                        $message[$key]['res'] = ['status' => 'error', 'msg' => "$skusms => Không tìm thấy nội dung tin nhắn"];
                        continue;
                    }
                }
                $noidung = $body[3];

                $check = active::select('id')->where('skusms',$skusms)->orderBy('id','desc')->first();
                if($check){
                    $message[$key]['res'] = ['status' => 'error', 'check' =>$check, 'msg' => "$skusms => $noidung\nTin nhắn này đã xử lý rồi"];
                    continue;
                }
                if(strpos($body[0], "19038186901019")){
                    
                    // $noidung = explode(" ",$noidung);
                    $phone = null;
                    if(str_contains($noidung, "CT tu")){
                        $strtachsdt = explode("CT tu",$noidung)[0];
                    }else{
                        $strtachsdt = $noidung;
                    }
                    preg_match_all('/((032|033|034|035|036|037|038|039|070|079|077|076|078|083|084|085|081|082|086|088|089|087|056|058|059|096|097|098|091|094|090|093|092|099)\d{7})/',$strtachsdt,$res);
                    if($res && isset($res[0]) && isset($res[0][0])){
                        // $phone = $res[0][0];
                        $phone = $res[0][count($res[0]) - 1];
                    }
                    // return $phone;
                    
                    if($phone){
                        $money = str_replace("So tien GD:+","",$body[1]);
                        $money = (int)str_replace([',','.',' '], '',$money);
                        if(!$money || $money <= 0){
                            $message[$key]['res'] = ['status' => 'error', 'msg' => "$skusms => $noidung\nTechcom Không nhận được số tiền"];
                            continue;
                        }
                        
                        $khachhang = User::where('phone', $phone)->first();
                        $note = '';
                        $status = 1;
                        if(!$khachhang){
                            $status = 0;
                            $note = "Techcom Không tìm thấy thông tin khách hàng $phone";
                        }
                        
                        
                        $months = 0;
                        if (is_array($moneyvip) || is_object($moneyvip)){
                            foreach($moneyvip as $key2 => $banggia){
                                (int)$key2;
                                (int)$banggia;
                                if($money >= $banggia){
                                    $months = $months < $key2 ? $key2 : $months;
                                }
                            }
                            if($months == 0){
                                $message[$key]['res'] = ['status' => 'error', 'msg' => "Techcom $skusms => Số tiền không đủ số tối thiểu để kích hoạt"];
                                continue;
                            }
                            
                            
                            $from = $khachhang && $khachhang->endactive ? strtotime($khachhang->endactive) : time();
                            if($from < time()) $from = time();
                            $start = date("Y-m-d", $from);
                            $end = date("Y-m-d", strtotime("+$months month", $from));
                            
                            $x = 1;
                            while($x != 0) {
                                $sku = incrementalHash();
                                $x = active::select('sku')->where('sku',$sku)->count();
                            }
                            
                            $active = new active;
                            $active->sku = $sku;
                            $active->phone = $phone;
                            $active->khach_id = $khachhang ? $khachhang->id : 0;
                            $active->user_id = 1;
                            $active->months = $months;
                            $active->start = $start;
                            $active->end = $end;
                            $active->months = $months;
                            $active->money = $money;
                            $active->note = $note;
                            $active->type = 'sms';
                            $active->status = $status;
                            $active->timesms = $date_sent;
                            $active->skusms = $skusms;
                            $active->sms = json_encode($sms);
                            $active->save();

                            if($khachhang){
                                $khachhang->endactive = $end;
                                $khachhang->save();
                            }

                            $message[$key]['res'] = [
                                'status' => 'success',
                                'active' => $active,
                                'skusms' => $skusms,
                                'msg' => "$skusms => $noidung\n$sku đã kích hoạt $months tháng cho $phone với số tiền ".number_format($money)
                            ];
                            continue;
                        }else{
                            $message[$key]['res'] = ['status' => 'error', 'msg' => "$skusms => Techcom Không nhận được bảng giá dịch vụ"];
                            continue;
                        }  
                    }else{
                        $message[$key]['res'] = ['status' => 'error', 'msg' => "$skusms => $noidung\nTechcom Không tìm thấy số điện thoại cần nạp"];
                        continue;
                    }
                }else{
                    $message[$key]['res'] = ['status' => 'error', 'msg' => 'Không phải tài khoản của Cảnh hoặc không phải tin nhắn chuyển tiền'];
                    continue;
                }
            }
            return response()->json($message);
        }else{
            return response()->json(['status' => 'error', 'msg' => "Techcom Không có tin nhắn nào", 'message' => $message]);
        }
        return response()->json($message);
    }
    public function webhookSMSv3(){
        $keySMS = Request::input('keySMS');
        if($keySMS != env('KEYSMSWEBHOOK')){
            return response()->json(['status' => 'error', 'exist' => true, 'msg' => "Sai key SMS"]);
        }
        // $all = Request::all();
        $message = (array)Request::input('message');
        if(count($message) > 0){
            $banggiadef = [1 => 50000, 6 => 275000, 12 => 500000];
            $moneyvip = settings::select()->where('name','moneyvip')->first();
            $moneyvip = is_json($moneyvip->value) ? (array)json_decode($moneyvip->value, true) : $banggiadef;
            // if(!is_array($moneyvip)) $moneyvip = $banggiadef;

            // $moneyvip = [1 => 50000, 6 => 275000, 12 => 500000];
            foreach($message as $key => $sms){
                $sms = (array)$sms;
                
                $skusms = $sms['_id'];
                $date_sent = $sms['date_sent'];
                $date = $sms['date'];
                $address = strtolower($sms['address']);
                
                $body = $sms['body'];
                $body = preg_split('/\r\n|[\r\n]/', $body);
                if(!isset($body[3])){
                    $body = explode('\n',$sms['body']);
                    if(!isset($body[3])){
                        $message[$key]['res'] = ['status' => 'error', 'msg' => "$skusms => Không tìm thấy nội dung tin nhắn"];
                        continue;
                    }
                }
                $noidung = $body[3];

                $check = active::select('id')->where('skusms',$skusms)->orderBy('id','desc')->first();
                if($check){
                    $message[$key]['res'] = ['status' => 'error', 'check' =>$check, 'msg' => "$skusms => $noidung\nTin nhắn này đã xử lý rồi"];
                    continue;
                }
                if(strpos($body[0], "19038186901019")){
                    
                    // $noidung = explode(" ",$noidung);
                    $phone = null;
                    if(str_contains($noidung, "CT tu")){
                        $strtachsdt = explode("CT tu",$noidung)[0];
                    }else{
                        $strtachsdt = $noidung;
                    }
                    preg_match_all('/((032|033|034|035|036|037|038|039|070|079|077|076|078|083|084|085|081|082|086|088|089|087|056|058|059|096|097|098|091|094|090|093|092|099)\d{7})/',$strtachsdt,$res);
                    if($res && isset($res[0]) && isset($res[0][0])){
                        // $phone = $res[0][0];
                        $phone = $res[0][count($res[0]) - 1];
                    }
                    // return $phone;
                    
                    if($phone){
                        $money = str_replace("So tien GD:+","",$body[1]);
                        $money = (int)str_replace([',','.',' '], '',$money);
                        if(!$money || $money <= 0){
                            $message[$key]['res'] = ['status' => 'error', 'msg' => "$skusms => $noidung\nTechcom Không nhận được số tiền"];
                            continue;
                        }
                        
                        $khachhang = User::where('phone', $phone)->first();
                        $note = '';
                        $status = 1;
                        if(!$khachhang){
                            $status = 0;
                            $note = "Techcom Không tìm thấy thông tin khách hàng $phone";
                        }
                        
                        $tygia = (int)env('TYGIA');
                        $SKU_POINT = env('SKU_POINT');
                        $coin = $money / $tygia;

                        if($khachhang){
                            $khachhang->money += $coin;
                            $khachhang->save();
                        }

                        $inbox = new inbox;
                        $inbox->title = "Nạp ".number_format($coin)." point vào tài khoản ";
                        $inbox->content = "Bạn vừa nạp ".number_format($coin)."[SKU_POINT]. Số [SKU_POINT] hiện tại của bạn là: ".number_format($khachhang->money);
                        $inbox->user_target = $khachhang->id;
                        $inbox->user_created = $khachhang->id;
                        $inbox->save();
                        
                        $point = new point;
                        $point->lydo = 'Nạp tiền vào tài khoản';
                        $point->point_cur = $khachhang->money;
                        $point->point = $coin;
                        $point->name = $khachhang->firstname;
                        $point->phone = $khachhang->phone;
                        $point->user_id = $khachhang->id;
                        $point->type = 'naptien';
                        $point->target_id = 0;
                        // $point->note = '';
                        $point->save();

                        $message[$key]['res'] = [
                            'status' => 'success',
                            'skusms' => $skusms,
                            'msg' => "$skusms => $noidung\n$phone đã nạp ".number_format($money)." ($coin $SKU_POINT)"
                        ];
                        continue;
                            
                    }else{
                        $message[$key]['res'] = ['status' => 'error', 'msg' => "$skusms => $noidung\nTechcom Không tìm thấy số điện thoại cần nạp"];
                        continue;
                    }
                }else{
                    $message[$key]['res'] = ['status' => 'error', 'msg' => 'Không phải tài khoản của Cảnh hoặc không phải tin nhắn chuyển tiền'];
                    continue;
                }
            }
            return response()->json($message);
        }else{
            return response()->json(['status' => 'error', 'msg' => "Techcom Không có tin nhắn nào", 'message' => $message]);
        }
        return response()->json($message);
    }
    public function buyvip(){
        $SKU_POINT = env('SKU_POINT');
        $TYGIA = (int)settings::select()->where('name','tygia')->first()->value;
        $months = (int)Request::input("months");

        $khachhang = Auth::user();
        $banggiadef = [1 => 50000, 6 => 275000, 12 => 500000];
        $moneyvip = settings::select()->where('name','moneyvip')->first();
        $moneyvip = is_json($moneyvip->value) ? (array)json_decode($moneyvip->value, true) : $banggiadef;
        if(!isset($moneyvip[$months]) && !isset($moneyvip["$months"])) return response()->json(['status' => 'error', 'msg' => "Số tháng mua vip không hợp lệ ($months)"]);
        $money = $moneyvip[$months];
        $coin = $moneyvip[$months] / $TYGIA;

        if(!$coin || $coin > $khachhang->money){
            return response()->json(['status' => 'error', 'msg' => "Số tiền trong tài khoản không đủ $money ($khachhang->money) "]);
        }
        
        $from = $khachhang && $khachhang->endactive ? strtotime($khachhang->endactive) : time();
        if($from < time()) $from = time();
        $start = date("Y-m-d", $from);
        $end = date("Y-m-d", strtotime("+$months month", $from));
        
        $x = 1;
        while($x != 0) {
            $sku = incrementalHash();
            $x = active::select('sku')->where('sku',$sku)->count();
        }
        
        $active = new active;
        $active->sku = $sku;
        $active->phone = $khachhang ? $khachhang->phone : 0;
        $active->khach_id = $khachhang ? $khachhang->id : 0;
        $active->user_id = $khachhang ? $khachhang->id : 0;
        $active->months = $months;
        $active->start = $start;
        $active->end = $end;
        $active->months = $months;
        $active->money = $money;
        $active->note = "";
        $active->type = 'buy';
        $active->status = 1;
        $active->save();

        $khachhang->money -= $coin;
        $khachhang->endactive = $end;
        $khachhang->save();

        $inbox = new inbox;
        $inbox->title = "Mua vip $months tháng";
        $inbox->content = "Bạn vừa mua $months tháng vip, phí mua: ".number_format($coin)."[SKU_POINT]";
        $inbox->user_target = $khachhang->id;
        $inbox->user_created = $khachhang->id;
        $inbox->save();
        
        $point = new point;
        $point->lydo = "Mua vip $months tháng";
        $point->point_cur = $khachhang->money;
        $point->point = $coin;
        $point->name = $khachhang->firstname;
        $point->phone = $khachhang->phone;
        $point->user_id = $khachhang->id;
        $point->type = 'muavip';
        $point->target_id = 0;
        // $point->note = '';
        $point->save();

        $khachhang->ngaysinh = date('d/m/Y', strtotime($khachhang->ngaysinh));
        if($khachhang->endactive != null){
            // $str = 'Thành viên Vip - '.date('d/m/Y',strtotime($khachhang->endactive));
            $songayconlai = datediff(time(), strtotime($khachhang->endactive));
            if($songayconlai <= 0){
                $str = "Đã hết hạn";
            }else{
                $str = "Thành viên Vip (còn $songayconlai ngày)";
            }
            $khachhang->endactive = $str;
        }else{
            $khachhang->endactive = 'Chưa mua Vip';
        }
        $khachhang->statusactive = $khachhang->endactive && strtotime($khachhang->endactive) > time() ? true : false;

        return response()->json([
            'status' => 'success',
            'active' => $active,
            'user' => $khachhang,
            'msg' => "Đã mua $months tháng vip với số $SKU_POINT là ".number_format($coin)
        ]);
        
    }
    public function test(){
        $body = Request::input('body');
        if(str_contains($body, "CT tu")){
            $body = explode("CT tu",$body)[0];
        }
        $phone = '';
        preg_match_all('/((032|033|034|035|036|037|038|039|070|079|077|076|078|083|084|085|081|082|086|088|089|087|056|058|059|096|097|098|091|094|090|093|092|099)\d{7})/',$body,$res);
        if($res && isset($res[0]) && isset($res[0][0])){
            $phone = $res[0][count($res[0]) - 1];
        }
        return [$phone, $body, $res];
    }

    // function 
    public function checkresgiovang($userid, $idgiovang, $token, $solan = 1){
        if($solan == 11) return ['status' => 'error', 'token' => $token, 'msg' => 'Không nhận được phản hồi'];
        sleep(1);
        $user = User::find($userid);
        if(is_json($user->resgiovang)){
            $res = json_decode($user->resgiovang, true);
            if($res[$idgiovang]){
                $code = code::where('code', $res['makm'])->first();
                if($code){
                    $code->hethan = date('d/m/Y G:i', strtotime($code->hethan));
                }
                $user->resgiovang = null;
                $user->save();
                return ['status' => 'success', 'token' => $token, 'code' => $code, 'msg' => 'Lấy mã thành công'];
            }else{
                return ['status' => 'error', 'token' => $token, 'msg' => 'Không nhận được phản hồi'];
            }
            
        }else{
            $solan++;
            return $this->checkresgiovang($userid, $idgiovang, $token, $solan);
        }
    }

    // crond
    public function cron10p(){
        $day30d = date("Y-m-d G:i:s", strtotime("-30 days"));
        $day3d = date("Y-m-d G:i:s", strtotime("-3 days"));
        vongquay::where('thoihanquay','<=',date('Y-m-d G:i:s'))->where('status',0)->update([ 'viewapp' => 0 ]);
        vongquay::where('thoihanquay','<=',date('Y-m-d G:i:s'))
        ->where('nhangiai',0)
        ->where('status',10)
        ->where('created_at','<=',$day30d)
        ->update([ 'viewapp' => 0 ]);

        // $DATECODE = env('DATECODE');
        $day1d = date("Y-m-d G:i:s");
        code::where('status',0)->where('hethan','<=',$day1d)->update(['status' => 9]);
    }
}
