<form method="post" action="{{ URL::Route('admin.setting.postSavegeneral') }}" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="_method" value="POST">
<button href="" class="btn btn-primary btn-sm mb-10px"><i class="fa fa-save"></i> Lưu</button>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="first">Tiêu Đề Web</label>
        <input class="form-control" id="first" type="text" value="{{$data['titleweb']['value']}}" name="titleweb" required="">
    </div>
    <div class="form-group col-md-4">
        <label for="web">Địa Chỉ Web</label>
        <input placeholder="htt(s)://abc.com" class="form-control" id="web" type="text"  value="{{$data['urlweb']['value']}}" name="urlweb" required="">
    </div>
    <div class="form-group col-md-4">
        <label for="dc">Địa Chỉ</label>
        <input class="form-control" id="dc" type="text" value="{{$data['address']['value']}}" name="address" required="">
    </div>
    <div class="form-group col-md-4">
        <label for="email">Email</label>
        <input class="form-control" id="email" type="email" value="{{$data['email']['value']}}" name="email" required="">
    </div>
    <div class="form-group col-md-4">
        <label for="sdt">Số Điện Thoại</label>
        <input class="form-control" id="sdt" type="text" value="{{$data['sdt']['value']}}" name="sdt" required="">
    </div>
    <div class="form-group col-md-4">
        <label for="fp">Fanpage Facebook</label>
        <input class="form-control" id="fp" type="text" value="{{$data['fanpage']['value']}}" name="fanpage" required="">
    </div>
    <div class="form-group col-md-4">
        <label for="sdtzalo">Số ĐT Zalo</label>
        <input class="form-control" id="sdtzalo" type="text" value="{{$data['sdtzalo']['value']}}" name="sdtzalo">
    </div>
    <div class="form-group col-md-4">
        <label for="youtube">Youtube</label>
        <input class="form-control" id="youtube" type="text" value="{{$data['youtube']['value']}}" name="youtube">
    </div>
    <div class="form-group col-md-4">
        <label for="google">Google Plus</label>
        <input class="form-control" id="google" type="text" value="{{$data['google']['value']}}" name="google">
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="first">Logo</label>
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control groupimgselect" rel="imglogo" id="logo" value="{{$data['logo']['value']}}" name="logo">
                <span class="input-group-btn"> <button data-inputid="logo" class="popup_selector btn btn-primary" type="button"><i class="fa fa-upload"></i></button> </span>
            </div>
        </div>
    </div>
    <div class="form-group col-md-2">
        <img id="imglogo" class="maxw80px" src="{{ $data['logo']['value']!='' ? $data['logo']['value'] : URL('/images/no-image.jpg') }}" alt="logo">
    </div>
    <div class="form-group col-md-4">
        <label for="favicon">Favicon</label>
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control groupimgselect" rel="imgfavicon" id="favicon" value="{{$data['favicon']['value']}}" name="favicon">
                <span class="input-group-btn"> <button data-inputid="favicon" class="popup_selector btn btn-primary" type="button"><i class="fa fa-upload"></i></button> </span>
            </div>
        </div>
    </div>
    <div class="form-group col-md-2">
        <img id="imgfavicon" class="maxw80px" src="{{ $data['favicon']['value']!='' ? $data['favicon']['value'] :URL('/images/no-image.jpg') }}" alt="favicon">
    </div>
    <div class="form-group col-md-12">
        <label for="codeheader">Code Header</label>
        <textarea placeholder="code header trong html" rows="7" class="form-control" id="codeheader" value="" name="codeheader">{{$data['codeheader']['value']}}</textarea>
    </div>
    <div class="form-group col-md-12">
        <label for="codefooter">Coder Footer</label>
        <textarea placeholder="code footer trong html" rows="7" class="form-control" id="codefooter" value="" name="codefooter">{{$data['codefooter']['value']}}</textarea>
    </div>
</div>
<button href="" class="btn btn-primary btn-sm mb-10px"><i class="fa fa-save"></i> Lưu</button>
</form>