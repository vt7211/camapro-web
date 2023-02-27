@extends('layouts.admin')
@section("title","Thêm Thành Viên")
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
<h1 class="main-title float-left">Thêm Thành Viên</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Thành Viên</li>
  <li class="breadcrumb-item active">Thêm</li>
</ol>
@endsection()
@section("content")
<div class="row">
 <div class="col-sm-12">
    <div class="card mb-3">
       <div class="card-header">
          <a href="{{ URL::Route('admin.user.getList') }}" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Cancel</a>
       </div>
       <div class="card-body">
          <form autocomplete="off" method="post" action="{{ URL::Route('admin.user.postadd') }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="POST">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="username">Tên Đăng Nhập</label>
                <input class="form-control" id="username" data-parsley-trigger="change" autocomplete="off" type="text" value="" name="username" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="first">Tên</label>
                <input class="form-control" id="first" data-parsley-trigger="change" autocomplete="off" type="text" value="" name="first" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="first">Họ</label>
                <input class="form-control" id="last" data-parsley-trigger="change" autocomplete="off" type="text"  value="" name="last" >
              </div>
              <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input class="form-control" id="email" data-parsley-type="email" autocomplete="off" type="email"  value="" name="email" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="phone">SĐT</label>
                <input class="form-control" id="phone" autocomplete="off" type="text" value="" name="phone" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="add">Địa Chỉ</label>
                <input class="form-control" id="add" autocomplete="off" type="text" value="" name="add" >
              </div>
              <div class="form-group col-md-4">
                <label for="status">Tình Trạng</label>
                <select id="status" name="status" class="form-control">
                  <option {{ old('status')==0 ? 'selected' : '' }} value="0">Bị Khóa</option>
                  <option {{ old('status')==1 ? 'selected' : '' }} value="1">Kích Hoạt</option>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="level">Quyền</label>
                <select id="level" name="level" class="form-control">
                  @foreach($roles as $role)
                    <option value="{{$role->id}}" {{ old('level')==$role->id ? 'selected' : '' }}>{{$role->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="pass1">Mật Khẩu</label>
                <input class="form-control" id="pass1" minlength="6" data-parsley-minlength="6" name="pass" autocomplete="off" type="password" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="pass2">Nhập lại Mật Khẩu</label>
                <input class="form-control"  data-parsley-equalto="#pass1" id="pass2" autocomplete="off" type="password" required="">
              </div>
              <div class="form-group col-md-4">
                <label for="ava">Ảnh Đại Diện ( Kích thước: 150x150, max: 200kb)</label>
                <input type="file" name="avarta" class="form-control" id="ava" >
              </div>
            </div>
            <div class="form-row"></div>
            <div class="form-group">
              <label for="note">Ghi Chú</label>
              <textarea class="form-control" name="note" id="note" cols="30" rows="5">{{ old('note') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Lưu</button>
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
      limit: 1,
      maxSize: 0.2,
      extensions: ['jpg', 'jpeg', 'png', 'gif', 'psd'],
      changeInput: true,
      showThumbs: true,
      addMore: true,
      captions: {
        button: "Chọn ảnh",
        feedback: "Chọn ảnh để upload",
        feedback2: "Ảnh đã chọn",
        drop: "Kéo và thả ảnh vào đây",
        removeConfirmation: "Bạn có chắc muốn xóa ảnh này?",
        errors: {
            filesLimit: "Dung lượng vượt quá kích thước cho phép.",
            filesType: "Chỉ upload được file ảnh.",
            filesSize: "File quá lớn! File tối đa là 200kb.",
            filesSizeAll: "Các file bạn chọn quá lớn! Dung lượng tối đa các file là 200kb."
        }
      }
  });
  $('form').parsley();
</script>
@endsection()