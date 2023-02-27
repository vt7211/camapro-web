@extends('layouts.admin')
@section("title","Danh Sách Danh Mục")
@section("css")
<link href="{{ URL('/css/jquery.nestable.css') }}" rel="stylesheet" />
<style>
</style>
@endsection()
@section("breadcrumb")
<h1 class="main-title float-left">Danh Mục</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Danh Mục</li>
  <li class="breadcrumb-item active">Danh Sách</li>
</ol>
@endsection()
@section("content")
<?php
$authu = Auth::User();
?>
<di class="row">
  <div class="col-sm-12 form-inline">
    @if($authu->is('menu.create'))
    <div>
      <a href="#" class="btn btn-primary mb-15px" data-toggle="modal" data-target=".addmenu"><i class="fa fa-plus"></i> Thêm Nhóm Danh Mục</a>
    </div>
    @endif
    <div class="form-group">
      &nbsp;&nbsp;&nbsp;
      <select name="choomenus" class="choosemenu form-control mb-15px">
      @foreach($menu as $item)
        <option {{ Route::current()->getName() == 'admin.menu.getList' && $id == $item->id ? 'selected' : '' }} value="{{URL::Route('admin.menu.getList',$item->id)}}">{{$item->name}}</option>
      @endforeach
      </select>
    </div>
  </div>
  <di class="col-sm-4">
    <di class="panel-group" id="accordion">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#tuychon">Link Tùy Chọn</a>
          </h4>
        </div>
        <div id="tuychon" class="panel-collapse in collapse">
          <div class="panel-body">
            <div class="input-group sm-12 form-group">
              <div class="input-group-prepend">
                <span class="input-group-text" >Tên menu</span>
              </div>
              <input type="text" class="form-control name" name="name" value="">
            </div>
            <div class="input-group sm-12 form-group">
              <div class="input-group-prepend">
                <span class="input-group-text" >Link</span>
              </div>
              <input type="text" class="form-control alias" name="alias" value="">
            </div>
            <div class="input-group sm-12">
              <button class="btn btn-primary btn-sm addcustom"><i class="fa fa-plus"></i> Thêm</button>
            </div>
          </div>
        </div>
      </div><!-- custom link -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#page">Trang Tĩnh</a>
          </h4>
        </div>
        <div id="page" class="panel-collapse collapse">
          <div class="panel-body" style="max-height:300px">
            @if(count($pages)>0)
            <ul class="wpchoosecate">
              @foreach($pages as $page)
              <li class=""><input data-name="{{$page->name}}" data-alias="{{$page->alias}}" class="choosepage" type="checkbox" name="choopage[]" value="{{$page->id}}"> {{$page->name}}</li>
              @endforeach
            </ul>
            <div>
              <button class="btn btn-primary btn-sm addcheckbox"><i class="fa fa-plus"></i> Thêm</button>
              <div class="clearfix"></div>
            </div>
            @endif
          </div>
        </div>
      </div><!-- page -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#cat">Nhóm Tin Tức</a>
          </h4>
        </div>
        <div id="cat" class="panel-collapse collapse">
          <div class="panel-body" style="max-height:300px">
            @if(count($categories_news)>0)
            <ul class="wpchoosecate"><?php choose_cate($categories_news); ?></ul>
            <button class="btn btn-primary btn-sm addcheckbox"><i class="fa fa-plus"></i> Thêm</button>
            @endif
          </div>
        </div>
      </div><!-- category -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#cat2">Nhóm Sản Phẩm</a>
          </h4>
        </div>
        <div id="cat2" class="panel-collapse collapse">
          <div class="panel-body" style="max-height:300px">
            @if(count($categories_pro)>0)
            <ul class="wpchoosecate"><?php choose_cate($categories_pro); ?></ul>
            <button class="btn btn-primary btn-sm addcheckbox"><i class="fa fa-plus"></i> Thêm</button>
            @endif
          </div>
        </div>
      </div><!-- category -->
    </di>
  </di>
 <div class="col-sm-8">
    <div class="card mb-3">
       <div class="card-header">
        <h2 class="pull-left">{{ count($menu) > 0 ? $menu[0]->name : 'Chưa Có Menu' }}</h2>
        @if($authu->is('menu.delete'))<a href="#" rel="{{ URL::Route('admin.menu.getDelete',$id) }}" class="btn btn-danger btn-sm deletemenu pull-right"><i class="fa fa-close"></i> Xóa Nhóm Danh Mục</a>@endif
        <div class="clearfix"></div>
       </div>
       <div class="card-body">
          <form autocomplete="off" method="post" action="{{ URL::Route('admin.menu.postedit',$id) }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="_method" value="POST">
          <?php if(count($data)>0){ $i=1; ?>
          <div class="">
            <button class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Lưu</button>
            <div class="clearfix"></div>
          </div>
          <div class="dd">
            <ol class="dd-list">
              <?php menu_item($data,$id); ?>
            </ol>
          </div>
          <div class="">
            <input type="hidden" class="form-control" name="struct" value="">
            <button class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Lưu</button>
            <div class="clearfix"></div>
          </div>
          <?php
          }else{
            echo '<div class="dd"><ol class="dd-list"></ol></div>';
            echo '<div class="mess mb-10px">Hiện tại chưa có thành phần nào trong menu này, bạn hãy thêm vào nhé.</div>';
            echo '<div class=""><input type="hidden" class="form-control" name="struct" value=""><button class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Lưu</button><div class="clearfix"></div></div>';
          }
          ?>
          </form>
       </div>
    </div>
    <!-- end card-->
 </div>
</di>
<div class="modal fade addmenu" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
  <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title">Thêm Danh Mục</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <form autocomplete="off" method="post" action="{{ URL::Route('admin.menu.postadd') }}">
    <div class="modal-body row">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="_method" value="POST">
      <div class="form-group col-md-12">
          <label for="name">Tên Danh Mục</label>
          <input class="form-control" id="name" data-parsley-trigger="change" autocomplete="off" type="text" value="" name="name" required="">
      </div>
    </div>
    <div class="modal-footer">
        <button type="submit"  class="btn btn-primary"><i class="fa fa-save"></i> Lưu</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
    </div>
    </form>
  </div>
  </div>
</div>
@endsection()
@section("script")

<script type="text/javascript" src="{{ URL('/js/jquery.nestable.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).ready(function() {
  $('.choosemenu').change(function(){
    var link = $(this).val();
    window.location.href = link;
  });

  $('.dd').nestable({'maxDepth':3}).on('change', function(e){
    updateStruct();
  });
  updateStruct();
  function getMenuStruct() {
    return JSON.stringify($('.dd').nestable('serialize'));
  }
  function updateStruct() {
    console.log(getMenuStruct());
    $('input[name="struct"]').val(getMenuStruct());
  }

  $('.addcustom').click(function(){
    var name = $(this).parents('.panel-body:first').find('.name').val();
    if(name==''){
      alert('Tên menu không được để trống'); return false;
    }
    var alias = $(this).parents('.panel-body:first').find('.alias').val();
    if(alias==''){
      alert('Đường dẫn không được để trống'); return false;
    }
    var id = getID();

    $('.dd > .dd-list').append(li(id, name, alias));
    updateStruct();
  });

  $('.addcheckbox').click(function(){
    var checkboxs= $(this).parents('.panel-body:first').find('input');
    checkboxs.each(function(){
      if($(this).is(':checked'))
      {
        var alias = $(this).attr('data-alias');
        var name = $(this).attr('data-name');
        var id = getID();
        $('.dd > .dd-list').append(li(id, name, alias));
        $( this ).prop( "checked", false );
      }
    });

    updateStruct();
  });



  $('.js-remove').on('click', function(){
    $(this).parents('li:first').remove();
    updateStruct();
  });

  function getID(){
    var $i=1;
    $('.dd-item').each(function(){
      var $it = parseInt($(this).attr('data-id'));
      if($it > $i) $i=$it;
    });
    $i++;
    return $i;
  }

  function li(id, name, alias){
    var li = '<li class="dd-item dd3-item" data-id="'+id+'" >';
        li += '<div class="dd-handle dd3-handle handle">Drag</div>';
        li += '<div class="dd3-content"><span class="nametitle" data-parent="#accordion" data-toggle="collapse" href="#menu'+id+'">'+ name+' </span><i class="fa fa-remove text-danger js-remove pull-right" title="xóa menu này"></i><div id="menu'+id+'" class="panel-collapse collapse space"><div class="panel-body"><div class="row"><div class="input-group sm-12 form-group"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon'+id+'">Tên menu</span></div><input type="text" class="form-control " name="menu['+id+'][name]" value="'+name+'"></div><div class="input-group sm-12 form-group"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon'+id+'">Link</span></div><input type="text" class="form-control" name="menu['+id+'][alias]" value="'+alias+'"></div><div class="input-group sm-12"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon'+id+'">Class</span></div><input type="text" class="form-control "  name="menu['+id+'][class]" value=""></div></div></div></div></div>';
    $('.mess').remove();
    return li;
  }
  $('.confirm').click(function(){
    swal({
      title: "Bạn Chắc Là Muốn Xóa?",
      text: "Sau khi bạn xóa, bạn không thể phục hồi được Danh Mục này!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Lệnh xóa đã được duyệt, đang tiến hành xóa !", {
          icon: "success",
        });
        window.location.href = $(this).attr('rel');
      } else {
        swal("Danh mục này đã được giữ lại !");
      }
    });
  });
  $('.deletemenu').click(function(){
    swal({
      title: "Bạn Chắc Là Muốn Xóa Menu Này ?",
      text: "Sau khi bạn xóa, bạn không thể phục hồi được được menu này, các menu con và nhóm menu này sẽ bị xóa !",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Lệnh xóa đã được duyệt, đang tiến hành xóa !", {
          icon: "success",
        });
        window.location.href = $(this).attr('rel');
      } else {
        swal("Danh mục này đã được giữ lại !");
      }
    });
  });


});
</script>
@endsection()