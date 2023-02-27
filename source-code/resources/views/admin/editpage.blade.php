@extends('layouts.admin')
@section("title","Sửa Trang Tĩnh")
@section("css")
<style>
.jFiler-input-dragDrop{
    max-width: 100%;
}
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
.wpchoosecate{
    max-height: 250px;
    overflow-y: auto
}
.wpchoosecate li{
    list-style: none;
    padding: 3px;
}
.wpchoosecate input{
    margin-right: 3px;
}
</style>
@endsection()
@section("breadcrumb")
<h1 class="main-title float-left">Sửa Trang</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Trang Tĩnh</li>
  <li class="breadcrumb-item active">Sửa</li>
</ol>
@endsection()
@section("content")
<form autocomplete="off" method="post" action="{{ URL::Route('admin.page.postEdit',$id) }}" enctype="multipart/form-data" class="row">
 <div class="col-sm-8">
    <div class="card mb-3">
       <div class="card-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="POST">
            <div class="form-group">
                <input id="name" class="form-control" data-parsley-trigger="change" autocomplete="off" type="text" value="{{ $data['name'] }}" name="name" required="" placeholder="Tiêu đề trang">
            </div>
            <div class="form-group">
                <div class="input-group">
                    <input id="generalalias" class="form-control" placeholder="Đường dẫn" value="{{ $Alias->alias }}" name="alias" required="" type="text">
                    <span class="input-group-btn"> <button id="createAlias" class="btn btn-default" type="button">Tạo URL</button> </span>
                </div>
                <p class="erroralias"></p>
                <input type="hidden" name="id" class="idnews" value="{{ $data['id'] }}">
                <input type="hidden" name="urlcheck" class="urlcheck" value="{{ URL::Route('checkalias') }}">
            </div>
            <div class="form-group">
              <textarea class="form-control wys" name="content" id="content" cols="30" rows="5" >{{ $data['content'] }}</textarea>
            </div>
       </div>
    </div>
    <!-- end card-->
    <div class="card mb-3">
        <div class="card-header"><b>SEO</b></div>
       <div class="card-body">
            <div class="form-group">
                <input class="form-control" data-parsley-trigger="change" autocomplete="off" type="text" value="{{ $data['keyworks'] }}" name="keyworks" placeholder="Từ khóa cho SEO">
            </div>
            <div class="form-group">
              <textarea  class="form-control" name="description" id="note" cols="30" rows="5" placeholder="Mô tả cho SEO">{{ $data['description'] }}</textarea>
            </div>
        </div>
    </div>
    <!-- end card-->
 </div>
 <div class="col-sm-4">
    <div class="card mb-3">
        <div class="card-header"><b>Thông Tin</b></div>
       <div class="card-body">
            <div class="form-group row">
                <label for="status" class="col-sm-4 col-form-label">Tình Trạng</label>
                <div class="col-sm-8">
                    <select id="status" name="status" class="form-control">
                      <option value="1">Đăng</option>
                      <option {{ $data['status']=='0' ? 'selected' : '' }} value="0">Lưu Nháp</option>
                    </select>
                </div>
            </div>
            <div>
                <a href="{{ URL::Route('admin.page.getList','all') }}" class="btn btn-danger"><i class="fa fa-times"></i> Bỏ Qua</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu</button>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><b>Ảnh Đại Diện ( maxsize: 400kb)</b></div>
        <div class="card-body">
            <img id="imgthumb" src="{{ $data['image']!='' ? get_image_url($data['image'],'featured'): URL('/images/no-image.jpg') }}" alt="thumbnail">
            <div class="form-group">
                <input class="form-control" id="feature_image" type="hidden" value="{{ $data['image'] }}" name="feature_image">
            </div>
            <a href="#" class="choose_thumb btn btn-primary" data-inputid="feature_image"><i class="fa fa-upload"></i> Chọn Ảnh</a>
        </div>
    </div>
  </div>
</form>
@endsection()
@section("script")
<!-- start media -->
@include('media::partials.media')

<script src="{{ URL('vendor/media/packages/ckeditor/ckeditor.js') }}"></script>
<script src="{{ URL('vendor/media/js/jquery.addMedia.js') }}"></script>

<script>
'use strict';
$("#feature_image").change(function(){
  console.log('da doi');
  $('#imgthumb').attr('src',$(this).val());
});

var config = {
    filebrowserImageBrowseUrl: RV_MEDIA_URL.base + '?media-action=select-files&method=ckeditor&type=image',
    filebrowserImageUploadUrl: RV_MEDIA_URL.media_upload_from_editor + '?method=ckeditor&type=image&_token=' + $('meta[name="csrf-token"]').attr('content'),
    filebrowserWindowWidth: '768',
    filebrowserWindowHeight: '500',
    height: 356,
    allowedContent: true,
    basicEntities: false,
    entities: false,
    entities_greek: false,
    entities_latin: false,
    htmlEncodeOutput: false,
    entities_processNumerical: false,
};
var mergeConfig = {};
var extraConfig = {};
$.extend(mergeConfig, config, extraConfig);
CKEDITOR.replace('content', mergeConfig);

if (jQuery().rvMedia) {
    $('.choose_thumb').rvMedia({
        multiple: false,
        onSelectFiles: function (files, $el) {
            var firstItem = _.first(files);
            console.log(firstItem);
            $('#feature_image').val(firstItem.url);
            $('#imgthumb').attr('src',firstItem.thumb);
        }
    });
}

</script>
<script src="{{ URL('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<script>
  $('form').parsley();
</script>
@endsection()