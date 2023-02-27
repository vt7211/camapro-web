@extends('layouts.admin')
@section("title","Cấu Hình Website")
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
<h1 class="main-title float-left">Cấu Hình Website</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Cấu Hình</li>
  <li class="breadcrumb-item active">Danh Sách</li>
</ol>
@endsection()
@section("content")
<div class="row">
 <div class="col-sm-12">
    <div class="panel with-nav-tabs panel-primary">
      <div class="panel-heading">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab1default" data-toggle="tab">Tổng Quan</a></li>
          <li><a href="#tab2default" data-toggle="tab">Slider</a></li>
          <li><a href="#tab3default" data-toggle="tab">Cột Trái - Phải</a></li>
        </ul>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div class="tab-pane fade in active" id="tab1default">
            <div>@include('admin.settings.tab1')</div>
          </div>
          <div class="tab-pane fade" id="tab2default">
            <div>@include('admin.settings.tab2')</div>
          </div>
          <div class="tab-pane fade" id="tab3default">
            <div>@include('admin.settings.tab3')</div>
          </div>
        </div>
      </div>
    </div>
    <!-- end card-->
 </div>
</div>
@endsection()
@section("script")

@include('media::partials.media')
<script src="{{ URL('vendor/media/packages/ckeditor/ckeditor.js') }}"></script>
<script src="{{ URL('vendor/media/js/jquery.addMedia.js') }}"></script>

<script>
if (jQuery().rvMedia) {
    $('.popup_selector').rvMedia({
        multiple: false,
        onSelectFiles: function (files, $el) {
            var firstItem = _.first(files);
            // console.log(firstItem);
            var id = $el.context.attributes['data-inputid'].nodeValue;
            $('#img'+id).attr('src',firstItem.full_url);
            $('#'+id).val(firstItem.full_url);
        }
    });
}

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
//CKEDITOR.replace('footer', mergeConfig);
</script>
<script src="{{ URL('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<script>
  $('form').parsley();
</script>
@endsection()
