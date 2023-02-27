<?php
$nametype = "tin tức";
if($type=="tailieu"){
  $nametype = "tài liệu";
}
?>
@extends('layouts.admin')
@section("title","Danh sách ".$nametype)
@section("css")
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
<style type="text/css">
.card-header a{
  color:#555;
}
.card-header .active{
  font-weight:bold;
  color:#007bff;
}
#table_wrapper{
    padding:0px !important;
}
.card-header .btn-primary{
  color:white;
}
td.details-control {
    background: url('assets/plugins/datatables/img/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('assets/plugins/datatables/img/details_close.png') no-repeat center center;
}
</style>
@endsection()
@section("breadcrumb")
<h1 class="main-title float-left">Danh Sách {{$nametype}}</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">{{$nametype}}</li>
  <li class="breadcrumb-item active">Danh Sách</li>
</ol>
@endsection()
@section("content")
<?php
$authu = Auth::User();
?>
<div class="row">
 <div class="col-sm-12">
    <div class="card mb-3">
       <div class="card-header">
        <form class="form-inline mb-10px" method="get" action="">
            <div class="form-group mr-2">
                <input type="text" name="txt" class="form-control" autocomplete="off" placeholder="Tên bài viết" value="{{$txt}}">
            </div>
            <div class="form-group mr-2">
                <select name="cat" class="form-control">
                    <option value="">Nhóm Bài Viết</option>
                    @foreach($cats as $item)
                    <option value="{{$item->id}}" <?php if($cat == $item->id) echo 'selected'; ?>>{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mr-2">
                <button class="btn btn-primary"><i class="fa fa-database"></i> Lọc</button>
            </div>
        </form>
        <a class="<?php if($status=='all') echo 'active'; ?>" href="{!! URL::route('admin.news.getList',array($type,'all')) !!}">Tất cả ({{$total}})</a> | <a href="{!! URL::route('admin.news.getList',array($type,1)) !!}"  class="<?php if($status==1) echo 'active'; ?>">Được duyệt ({{$active}})</a> | <a href="{!! URL::route('admin.news.getList',array($type,0)) !!}"  class="<?php if($status=='0') echo 'active'; ?>">Lưu nháp ({{$pendding}})</a> | <a href="{!! URL::route('admin.news.getList',array($type,2)) !!}"  class="<?php if($status==2) echo 'active'; ?>">ThùngrRác ({{$draft}})</a>
        @if($authu->is('news.create'))<a href="{!! URL::Route('admin.news.getadd',$type) !!}" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Thêm {{$nametype}}</a>@endif
       </div>
       <div class="card-body">
          <table id="table" class="table table-striped">
             <thead>
                <tr>
                   <th scope="col">#</th>
                   <th scope="col">Tên</th>
                   <th scope="col">Tác Giả</th>
                   <th scope="col">Nhóm Tin</th>
                   <th scope="col">Ảnh</th>
                   <th scope="col">Trạng Thái</th>
                   <th>#</th>
                </tr>
             </thead>
             <tbody>
            <?php $i=1; ?>
            @foreach($news as $new)
            <tr>
                <th scope="row">{{ $i++ }}</th>
                <td>
                  {{ $new->name }}
                  <p class="menulist">
                    @if($new->status==1)
                        <a href="{{URL::Route('admin.news.getStatus',array($new->id,0))}}" class="text-warning"><i class="fa fa-eye"></i> lưu nháp</a>
                    @elseif($new->status==0)
                        <a href="{{URL::Route('admin.news.getStatus',array($new->id,1))}}" class="text-success"><i class="fa fa-check"></i> hiển thị</a>
                    @elseif($new->status==2)
                        <a href="{{URL::Route('admin.news.getStatus',array($new->id,0))}}" class="text-success"><i class="fa fa-refresh"></i> phục hồi</a>
                    @endif
                  </p>
                </td>
                <td>{{ $new->username }}</td>
                <td><?php foreach($new->cname as $c) echo '<span class="btn btn-xs btn-default">'.$c->name.'</span> '; ?></td>
                <td><img class="thumbnailList" src="{{ $new->image!='' ? get_image_url($new->image,'featured') : URL('/images/no-image.jpg') }}" alt="thumbnail"></td>
                <td>
                    @if($new->status == 1)
                        <span class="btn btn-success btn-xs">Đã Đăng</span>
                    @elseif($new->status == 0)
                        <span class="btn btn-danger btn-xs">Lưu Nháp</span>
                    @elseif($new->status == 2)
                        <span class="btn btn-danger btn-xs">Xóa Tạm</span>
                    @else
                        <span class="btn btn-warning btn-xs">Không Rõ</span>
                    @endif
                </td>
                <td>
                    <a href="{!! URL::Route('admin.news.getEdit', array($type, $new->id)) !!}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                    @if($new->status==2)
                    <a title="xóa vĩnh viễn" href="#" rel="{!! URL::Route('admin.news.getDelete', $new->id) !!}" class="btn btn-danger btn-sm confirmforever"><i class="fa fa-times"></i></a>
                    @else
                    <a title="xóa tạm" href="#" rel="{!! URL::Route('admin.news.getStatus',array($new->id,2)) !!}" class="btn btn-danger btn-sm confirm"><i class="fa fa-times"></i></a>
                    @endif
                </td>
            </tr>
            @endforeach
            @if(count($news)==0)
            <tr>
              <td colspan="7">Không tìm thấy bài viết nào</td>
            </tr>
            @endif
             </tbody>
          </table>
          {{ $news->appends(request()->except('page'))->links() }}
       </div>
    </div>
    <!-- end card-->
 </div>
</div>
@endsection()
@section("script")
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {

    $('#table').on('click', '.confirm', function() {
       swal({
          title: "Bạn Có Chắc Chắn?",
          text: "Bạn có chắc chắn xóa bài viết này không ? sau khi xóa bài viết sẽ được chuyển qua thùng rác !",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            swal("Lệnh xóa đang được xử lý !", {
              icon: "success",
            });
            window.location.href = $(this).attr('rel');
          } else {
            swal("Bài viết đã được giữ lại!");
          }
        });
    });
    $('#table').on('click', '.confirmforever', function() {
       swal({
          title: "Bạn Có Chắc Chắn?",
          text: "Sau khi xóa bạn không thể phục hồi bài viết này !",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            swal("Lệnh xóa đang được xử lý !", {
              icon: "success",
            });
            window.location.href = $(this).attr('rel');
          } else {
            swal("Thao tác đã được hủy!");
          }
        });
    });

});
</script>
@endsection()
