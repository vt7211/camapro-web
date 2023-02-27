@extends('layouts.admin')
@section("title","Edit Users")
@section("css")
<link href="{{ URL('assets/plugins/jquery.filer/css/jquery.filer.css') }}" rel="stylesheet" />
<link href="{{ URL('assets/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css') }}" rel="stylesheet" />
<style>
.parsley-error {
  border-color: #ff5d48 !important;
}
.parsley-errors-list.filled {
  display: block;
}
.parsley-errors-list {
  display: none;
  margin: 0;
  padding: 0;
}
.parsley-errors-list > li {
  font-size: 12px;
  list-style: none;
  color: #ff5d48;
  margin-top: 5px;
}
.form-section {
  padding-left: 15px;
  border-left: 2px solid #FF851B;
  display: none;
}
.form-section.current {
  display: inherit;
}
</style>
@endsection()
@section("breadcrumb")
<h1 class="main-title float-left">Chỉnh Sửa Thành Viên</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Thành Viên</li>
  <li class="breadcrumb-item active">Sửa</li>
</ol>
@endsection()
@section("content")
<?php
$authu = Auth::User();
$changerole = $authu->is('user.changerole');
?>
<div class="row">
 <div class="col-sm-12">
    <div class="card mb-3">
       <div class="card-header">
          <a href="{{ URL::Route('admin.user.getList') }}" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
       </div>
       <div class="card-body">
          <form autocomplete="off" method="post" action="{{ URL::Route('admin.user.postEdit',$data['id']) }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="POST">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="first">Tên</label>
                <input class="form-control" id="first" data-parsley-trigger="change" autocomplete="off" type="text" value="{!! $data['firstname'] !!}" name="first" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="last">Họ</label>
                <input class="form-control" id="last" data-parsley-trigger="change" autocomplete="off" type="text"  value="{!! $data['lastname'] !!}" name="last" >
              </div>
              <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input class="form-control" id="email" data-parsley-type="email" autocomplete="off" type="email"  value="{!! $data['email'] !!}" name="email" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="phone">SĐT</label>
                <input class="form-control" id="phone" autocomplete="off" type="text" value="{!! $data['phone'] !!}" name="phone" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="add">Địa Chỉ</label>
                <input class="form-control" id="add" autocomplete="off" type="text" value="{!! $data['address'] !!}" name="add" >
              </div>
              @if($changerole)
              <div class="form-group col-md-4">
                <label for="status">Tình Trạng</label>
                <select id="status" name="status" class="form-control">
                  <option {{ $data['status']==0 ? 'selected' : '' }} value="0">Suppend</option>
                  <option {{ $data['status']==1 ? 'selected' : '' }} value="1">Active</option>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="level">Quyền</label>
                <select id="level" name="level" class="form-control">
                  @foreach($roles as $role)
                    <option value="{{$role->id}}" {{ $data['level']==$role->id ? 'selected' : '' }}>{{$role->name}}</option>
                  @endforeach
                </select>
              </div>
              @endif
              <div class="form-group col-md-4">
                <label for="pass1">Mật Khẩu</label>
                <input class="form-control" id="pass1" minlength="6" data-parsley-minlength="6" name="pass" autocomplete="off" type="password" >
              </div>
              <div class="form-group col-md-4">
                <label for="pass2">Nhập Lại MK</label>
                <input class="form-control"  data-parsley-equalto="#pass1" id="pass2" autocomplete="off" type="password" >
              </div>
              <div class="form-group col-md-6">
                <label for="ava">Ảnh Đại Diện ( Size: 150x150 )</label>
                <input type="file" name="avarta" class="form-control" id="ava" >
              </div>
              <div class="col-sm-6">
                  <?php
                  $thumb = '/images/no-image.jpg';
                  if($data['image']!='') $thumb = URL(Config::get('siteVars.dirUploadUser')).'/'.$data['image']; ?>
                  <img src="{{ $thumb }}" alt="" style="max-height:80px">
              </div>
            </div>
            <div class="form-row"></div>
            <div class="form-group">
              <label for="note">Ghi chú</label>
              <textarea class="form-control" name="note" id="note" cols="30" rows="5">{!! $data['note'] !!}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu</button>
          </form>
       </div>
    </div>
    <!-- end card-->
 </div>
</div>
@endsection()
@section("script")
<script src="{{ URL('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ URL('assets/plugins/jquery.filer/js/jquery.filer.min.js') }}"></script>
<script>
  $('#ava').filer({
      limit: 3,
      maxSize: 3,
      extensions: ['jpg', 'jpeg', 'png', 'gif', 'psd'],
      changeInput: true,
      showThumbs: true,
      addMore: true
  });
  $('form').parsley();
</script>
@endsection()