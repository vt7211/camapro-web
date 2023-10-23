<?php
function stripUnicode($str){
	if(!$str) return false;
	$unicode = array(
	'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
	'd'=>'đ',
	'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
	'i'=>'í|ì|ỉ|ĩ|ị',
	'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
	'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
	'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
	);
	foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i",$nonUnicode,$str);
	return $str;
}
function curPageURL(){
    $pageURL = 'https://';
    return $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    if ($_SERVER["SERVER_PORT"] == "80" )
    {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else if ($_SERVER["SERVER_PORT"] == "443")
    {
        $pageURL = 'https://';
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    } else
    {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
function changeTitle($str){
	$str = trim($str);
	if($str == "") return "";
	$str = str_replace('"', '', $str);
	$str = str_replace("'", "", $str);
	$str = stripUnicode($str);
	$str = mb_convert_case($str, MB_CASE_LOWER, 'utf-8');
	//MB_CASE_TITLE // MB_CASE_LOWER
	$str = str_replace(" ", "-", $str);
    $str = str_replace("---", "-", $str);
    $str = str_replace("--", "-", $str);
	return $str;
}
function cate_parent($data, $parent = 0, $str = "--", $select = 0){
	foreach ($data as $key => $value) {
		if($parent == $value["parent_id"]){
			if($select != 0 && $select== $value["id"]){
				echo "<option value='".$value["id"]."' selected='selected'>$str".$value['name']."</option>";
			}else{
                if($parent == 0){
                    echo "<option value='".$value["id"]."'>".$value['name']."</option>";
                }else{
                    echo "<option value='".$value["id"]."'>$str ".$value['name']."</option>";
                }

			}
			cate_parent($data, $value["id"], $str."--", $select);
		}

	}
}
function menu_item($menus,$id,$i=1,$y=0){
    $y = $y+1;
    if($i==null) {
        global $i;
    }
    foreach ($menus as $menu) {
        echo '<li class="dd-item dd3-item" data-id="'.$menu->id.'" >';
        echo '<div class="dd-handle dd3-handle handle">Drag</div>';
        echo '<div class="dd3-content">
        <span class="nametitle" data-parent="#accordion" data-toggle="collapse" href="#menu'.$y.$i.'">'. $menu->name.' </span><i rel="/admin/menu/deletedetail/'.$id.'/'.$menu->id.'" class="fa fa-close confirm text-danger pull-right" title="xóa menu này"></i>
        <div id="menu'.$y.$i.'" class="panel-collapse collapse space">
            <div class="panel-body">
                <div class="row">
                <div class="input-group sm-12 form-group">
                    <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon'.$y.$i.'">Tên menu</span>
                    </div>
                    <input type="text" class="form-control" name="menu['.$menu->id.'][name]" value="'.$menu->name.'">
                </div>
                <div class="input-group sm-12 form-group">
                    <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon'.$y.$i.'">Link</span>
                    </div>
                    <input type="text" class="form-control" name="menu['.$menu->id.'][alias]" value="'.$menu->alias.'">
                </div>
                <div class="input-group sm-12">
                    <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon'.$y.$i.'">Class</span>
                    </div>
                    <input type="text" class="form-control" name="menu['.$menu->id.'][class]" value="'.$menu->class.'">
                </div>
                </div>
            </div>
        </div></div>';
        $i++;
        if(isset($menu->con) && count($menu->con)>0){
            echo '<ol class="dd-list">';
            menu_item($menu->con,$id,null,$y);
            echo '</ol>';
        }
        echo '</li>';
    }
}
function cate_echo($data, $parent = 0, $str = "",$withcount, $type){
    if($withcount == 'stores') $withcount = 'products';
    foreach ($data as $key => $value) {
        if($parent == $value["parent_id"]){
            $count = 'Không xác định';
            if(isset($value[$withcount."_count"])) $count = $value[$withcount."_count"];
            // echo '<tr><th scope="row">'.$i++.'</th>';
            echo '<td scope="row"><img class="thumbnailList" src="'.($value['feature_image']!='' ? $value['feature_image'] : URL('/images/no-image.jpg')).'" /></td>';
            if($parent == 0){
                echo '<td><a target="_blank" href="#">'.$value["name"].'</a>';
            }else{
                echo '<td><a target="_blank" href="#">'.$str.' '.$value["name"].'</a>';
            }

            echo '</td><td>'.$count.'</td>';
            echo '</td><td>'.date('d/m/Y G:i',strtotime($value["created_at"])).'</td>';

            echo '<td><a href="'.URL::Route('admin.cate.getEdit',array($type, $value['id'])).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a> ';
            echo '<a href="#" rel="'.URL::Route('admin.cate.getDelete',array($type, $value['id'])).'" class="btn btn-danger btn-sm confirm"><i class="fa fa-times"></i></a></td>';
            echo '</tr>';

            cate_echo($data, $value["id"], $str."---",$withcount, $type);
        }

    }
}
function choose_cate_one_select($data, $parent = 0, $str = "--", $select = 0){
    foreach ($data as $key => $value) {
        if($parent == $value["parent_id"]){
            if($select != 0 && in_array($value["id"],$select)){
                echo "<option value='".$value["id"]."' selected='selected'>$str".$value['name']."</option>";
            }else{
                if($parent == 0){
                    echo "<option data-txt='".$value['name']."' value='".$value["id"]."'>$str".$value['name']."</option>";
                }else{
                    echo "<option value='".$value["id"]."'>$str ".$value['name']."</option>";
                }

            }
            cate_parent($data, $value["id"], $str."--", $select);
        }

    }
}
function choose_cate($data,$check = '', $parent = 0, $str = ""){
    if(!is_array($check)) $checkvl = explode(',',$check); else $checkvl=$check;
    //return print_r($checkvl);
    foreach ($data as $key => $value) {
        $value = json_decode(json_encode($value), true);
        if($parent == $value["parent_id"]){
            echo '<li class="">'.$str.'<input type="checkbox" data-name="'.$value['name'].'" data-alias="'.$value['alias'].'"';
            if(in_array($value["id"],$checkvl)) echo 'checked';
            echo ' name="choosecate[]" value="'.$value["id"].'"> '.$value['name'].'</li>';
            choose_cate($data,$checkvl,  $value["id"], $str."&nbsp;&nbsp;&nbsp;&nbsp;");
        }
    }
}
function str_limit_html($strHTML, $limit = 250)
{
    $string = strip_tags($strHTML);
    if (strlen($string) > $limit) {
        $string = substr($string, 0, $limit);
    }
    return $string;
}
function active($currect_page){
    $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
    $url = "/".end($url_array);
    if($currect_page == $url){
        echo 'active';
    }
}
function curl($url, $method = 'get', $header = null, $postdata = null, $timeout = 60){
    $s = curl_init();
    // initialize curl handler 

    curl_setopt($s,CURLOPT_URL, $url);
    //set option  URL of the location 
    if ($header) 
        curl_setopt($s,CURLOPT_HTTPHEADER, $header);
    //set headers if presents
    curl_setopt($s,CURLOPT_TIMEOUT, $timeout);
    //time out of the curl handler  		
    curl_setopt($s,CURLOPT_CONNECTTIMEOUT, $timeout);
    //time out of the curl socket connection closing 
    curl_setopt($s,CURLOPT_MAXREDIRS, 3);
    //set maximum URL redirections to 3 
    curl_setopt($s,CURLOPT_RETURNTRANSFER, true);
    // set option curl to return as string ,don't output directly
    curl_setopt($s,CURLOPT_FOLLOWLOCATION, 1);
    // curl_setopt($s,CURLOPT_COOKIEJAR, 'cookie.txt');
    // curl_setopt($s,CURLOPT_COOKIEFILE, 'cookie.txt'); 
    //set a cookie text file, make sure it is writable chmod 777 permission to cookie.txt
    // curl_setopt($s, CURLOPT_SSL_VERIFYHOST, FALSE);
    // curl_setopt($s, CURLOPT_SSL_VERIFYPEER, FALSE);

    if(strtolower($method) == 'post')
    {
        curl_setopt($s,CURLOPT_POST, true);
        //set curl option to post method
        // curl_setopt($s,CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($s,CURLOPT_POSTFIELDS, http_build_query($postdata));
        //if post data present send them.
    }
    else if(strtolower($method) == 'delete')
    {
        curl_setopt($s,CURLOPT_CUSTOMREQUEST, 'DELETE');
        //file transfer time delete
    }
    else if(strtolower($method) == 'put')
    {
        curl_setopt($s,CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($s,CURLOPT_POSTFIELDS, $postdata);
        //file transfer to post ,put method and set data
    }

    curl_setopt($s,CURLOPT_HEADER, 0);			 
    // curl send header 
    curl_setopt($s,CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
    //proxy as Mozilla browser 
    curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
    // don't need to SSL verify ,if present it need openSSL PHP extension

    $html = curl_exec($s);
    //run handler
    if ($html === false) {
        throw new Exception(curl_error($s), curl_errno($s));
    }
    $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
    curl_close($s);

    // dd($status, $html);
    // if($status != 200) return false;
    if(is_json($html)){
        $html = json_decode($html);
        $html->tatuscode = $status;
    }
    return $html;
    //return output
}
function is_json($string,$return_data = false) {
    return ((is_string($string) &&
            (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
    // $data = json_decode($string);
    // return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : TRUE) : FALSE;
}
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    $isValid = filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    return $isValid ? $ipaddress : null;
}
?>
