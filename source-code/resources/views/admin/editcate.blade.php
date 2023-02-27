<?php
$namecat = '';
if($type=='news') $namecat = 'tin tức';
if($type=='products') $namecat = 'sản phẩm';
if($type=='tailieu') $namecat = 'tài liệu';
?>
@extends('layouts.admin')
@section("title","Sửa nhóm ".$namecat)
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
#cboxLoadedContent , #cboxContent{
    height: 422px !important;
}
#cboxWrapper{
    height: 442px !important;
}
#colorbox{
    top: 320px !important;
    height: 442px !important;
}

</style>
@endsection()
@section("breadcrumb")
<h1 class="main-title float-left">Sửa Nhóm {{$namecat}}</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">{{$namecat}}</li>
  <li class="breadcrumb-item active">Sửa</li>
</ol>
@endsection()
@section("content")
<form autocomplete="off" method="post" action="{{ URL::Route('admin.cate.postEdit',array($type,$data['id'])) }}" enctype="multipart/form-data" class="row">
 <div class="col-sm-12">
    <div class="card mb-3">
        <div class="card-header">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Lưu</button>
            <a href="#" onclick="goBack()" class="btn btn-danger"><i class="fa fa-times"></i> Bỏ Qua</a>
       </div>
       <div class="card-body">
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="POST">
                <div class="form-group col-md-6">
                    <label for="name">Tên Nhóm</label>
                    <input class="form-control" id="name" data-parsley-trigger="change" autocomplete="off" type="text" value="{{$data['name']}}" name="name" required="">
                </div>
                <div class="form-group col-md-6">
                    <label for="alias">Đường Dẫn</label>
                    <div class="input-group">
                        <input id="generalalias" type="text" class="form-control" value="{{isset($alias->alias) ? $alias->alias : ''}}" name="alias" required="">
                        <span class="input-group-btn"> <button id="createAlias" class="btn btn-default" type="button">Kiểm Tra</button> </span>
                    </div>
                    <p class="erroralias"></p>
                    <input type="hidden" name="id" class="idnews" value="{{ $data['id'] }}">
                    <input type="hidden" name="urlcheck" class="urlcheck" value="{{ URL::Route('checkalias') }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="parent_id">Nhóm Cha</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="0">Không Có</option>
                        <?php cate_parent($parent,0, $str = "--", $data["parent_id"]); ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="order">Thứ Tự</label>
                    <input class="form-control" id="order" data-parsley-trigger="change" autocomplete="off" type="number" min="0" value="{{$data['order']}}" name="order" required="">
                </div>
                <div class="form-group col-md-3">
                    <label for="keywords">Từ Khóa SEO</label>
                    <input class="form-control" id="keywords" data-parsley-trigger="change" autocomplete="off" type="text" value="{{$data['keywords']}}" name="keywords" >
                </div>
                <div class="form-group col-md-3">
                    <label for="description">Mô Tả SEO</label>
                    <input class="form-control" id="description" data-parsley-trigger="change" autocomplete="off" type="text" value="{{$data['description']}}" name="description">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <p for="parent_id">Ảnh Đại Diện</p>
                    <input class="form-control" id="feature_image" type="hidden" value="" name="feature_image">
                    <img style="max-height:150px" id="imgthumb" src="{{ $data['feature_image']!='' ? get_image_url($data['feature_image'],'thumb') : URL('/images/no-image.jpg') }}" alt="thumbnail">
                    <a href="#" class="choose_thumb btn btn-primary" data-inputid="feature_image"><i class="fa fa-upload"></i> Chọn Ảnh</a>
                </div>
                <div class="form-group col-md-12">
                    <label for="alias">Mô Tả Nhóm</label>
                    <textarea class="form-control" name="content" id="content" cols="30" rows="5" >{{$data['content']}}</textarea>
                </div>
                <input type="hidden" value="{{$type}}" name="post_type" required="">
            </div>
       </div>
       <div class="card-footer">
            <button type="submit"  class="btn btn-primary"><i class="fa fa-save"></i> Lưu</button>
          <a href="#" onclick="goBack()" class="btn btn-danger"><i class="fa fa-times"></i> Bỏ Qua</a>
       </div>
    </div>
    <!-- end card-->
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
    allowedContent: true
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