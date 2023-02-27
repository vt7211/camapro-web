<?php
$nametype = "tin tức";
if($type=="tailieu"){
  $nametype = "tài liệu";
}
?>
@extends('layouts.admin')
@section("title","Thêm ".$nametype)
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

</style>
@endsection()
@section("breadcrumb")
<h1 class="main-title float-left">Thêm {{$nametype}}</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">{{$nametype}}</li>
  <li class="breadcrumb-item active">Thêm</li>
</ol>
@endsection()
@section("content")
<form autocomplete="off" method="post" action="{{ URL::Route('admin.news.postadd',$type) }}" enctype="multipart/form-data" class="row">
 <div class="col-sm-8">
    <div class="card mb-3">
       <div class="card-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="POST">
            <div class="form-group">
                <input id="name" class="form-control" data-parsley-trigger="change" autocomplete="off" type="text" value="" name="name" required="" placeholder="Tiêu đề">
            </div>
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" id="alias" placeholder="Đường dẫn" value="" name="alias" required="" type="text">
                    <span class="input-group-btn"> <button id="createAlias" class="btn btn-default" type="button">Tạo URL</button> </span>
                </div>
                <p class="erroralias"></p>
                <input type="hidden" name="id" class="idnews" value="0">
                <input type="hidden" name="urlcheck" class="urlcheck" value="{{ URL::Route('checkalias') }}">
            </div>
            <div class="form-group">
              <textarea class="form-control wys" name="content" id="content" cols="30" rows="5" >{{ old('content') }}</textarea>
            </div>
       </div>
    </div>
    <!-- end card-->
    <div class="card mb-3">
        <div class="card-header"><b>Mô Tả Ngắn</b></div>
       <div class="card-body">
            <div class="form-group">
              <textarea  class="form-control wys" name="intro" id="note" cols="30" rows="5" placeholder="Mô tả ngắn cho {{$nametype}}"></textarea>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><b>SEO</b></div>
       <div class="card-body">
            <div class="form-group">
                <input class="form-control" data-parsley-trigger="change" autocomplete="off" type="text" value="" name="keyworks" placeholder="Từ khóa cho SEO">
            </div>
            <div class="form-group">
              <textarea  class="form-control" name="description" id="note" cols="30" rows="5" placeholder="Mô tả cho SEO">{{ old('description') }}</textarea>
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
                <label for="status" class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                    <select id="status" name="status" class="form-control">
                      <option value="1">Hiển Thị</option>
                      <option value="0">Lưu Nháp</option>
                    </select>
                </div>
            </div>
            <div>
                <a href="{{ URL::Route('admin.news.getList',array($type,'all')) }}" class="btn btn-danger"><i class="fa fa-times"></i> Bỏ Qua</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu</button>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><b>Nhóm Tin</b></div>
       <div class="card-body">
            <div class="wpchoosecate"><?php choose_cate($parent); ?></div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><b>Ảnh Đại Diện ( maxsize: 400kb)</b></div>
        <div class="card-body">
            <img id="imgthumb" src="{{ URL('/images/no-image.jpg') }}" alt="thumbnail">
            <div class="form-group">
                <input class="form-control" id="feature_image" type="hidden" value="" name="feature_image">
            </div>
            <a href="#" class="choose_thumb btn btn-primary" ><i class="fa fa-upload"></i> Chọn Ảnh</a>
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
config.extraPlugins='video';
var mergeConfig = {};
var extraConfig = {};
$.extend(mergeConfig, config, extraConfig);
var editor = CKEDITOR.replace('content', mergeConfig);


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