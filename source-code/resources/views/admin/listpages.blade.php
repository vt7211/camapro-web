@extends('layouts.admin')
@section("title","Danh Sách Trang")
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
<h1 class="main-title float-left">Danh Sách Trang</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Trang</li>
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
          <a class="<?php if($status=='all') echo 'active'; ?>" href="{!! URL::route('admin.page.getList',array('all')) !!}">Tất cả ({{$total}})</a> | <a href="{!! URL::route('admin.page.getList',array(1)) !!}"  class="<?php if($status==1) echo 'active'; ?>">Được duyệt ({{$active}})</a> | <a href="{!! URL::route('admin.page.getList',array(0)) !!}"  class="<?php if($status=='0') echo 'active'; ?>">Lưu nháp ({{$pendding}})</a> | <a href="{!! URL::route('admin.page.getList',array(2)) !!}"  class="<?php if($status==2) echo 'active'; ?>">ThùngrRác ({{$draft}})</a>
          @if($authu->is('news.create'))<a href="{!! URL::Route('admin.page.getadd') !!}" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Thêm Trang</a>@endif
       </div>
       <div class="card-body">
          <table id="table" class="table">
             <thead>
                <tr>
                   <th scope="col">#</th>
                   <th scope="col">Tên</th>
                   <th scope="col">Người Đăng</th>
                   <th scope="col">Ảnh</th>
                   <th scope="col">Tình Trạng</th>
                   <th scope="col"></th>
                </tr>
             </thead>
             <tbody>
            <?php $i=1; ?>
            @foreach($pages as $page)
            <tr>
                <th scope="row">{{ $i++ }}</th>
                <td>
                  {{ $page->name}}
                  <p class="menulist">
                    @if($page->status==1)
                        <a href="{{URL::Route('admin.news.getStatus',array($page->id,0))}}" class="text-warning"><i class="fa fa-eye"></i> lưu nháp</a>
                    @elseif($page->status==0)
                        <a href="{{URL::Route('admin.news.getStatus',array($page->id,1))}}" class="text-success"><i class="fa fa-check"></i> hiển thị</a>
                    @elseif($page->status==2)
                        <a href="{{URL::Route('admin.news.getStatus',array($page->id,0))}}" class="text-success"><i class="fa fa-refresh"></i> phục hồi</a>
                    @endif
                  </p>
                </td>
                <td>{{$page->username }}</td>
                <td><img class="thumbnailList" src="{{ $page->image!='' ? $page->image : URL('/images/no-image.jpg') }}" alt="thumbnail"></td>
                <td>
                    @if($page->status == 1)
                        <span class="btn btn-success btn-xs">Đăng</span>
                    @elseif($page->status == 0)
                        <span class="btn btn-default btn-xs">Lưu Nháp</span>
                    @endif
                </td>
                <td>
                    <a href="{!! URL::Route('admin.page.getEdit', $page->id) !!}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>


                    @if($page->status==2)
                    <a title="xóa vĩnh viễn" href="#" rel="{!! URL::Route('admin.news.getDelete', $page->id) !!}" class="btn btn-danger btn-sm confirm"><i class="fa fa-times"></i></a>
                    @else
                    <a title="xóa tạm" href="#" rel="{!! URL::Route('admin.news.getStatus',array($page->id,2)) !!}" class="btn btn-danger btn-sm confirm"><i class="fa fa-times"></i></a>
                    @endif
                </td>
            </tr>
            @endforeach
             </tbody>
          </table>
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
    var table = $('#table').DataTable( {
        //"scrollY": "350px",
        "paging": true,
        "language": {
            "lengthMenu": "Hiển thị _MENU_ bài trên 1 trang",
            "zeroRecords": "Chưa có bài viết nào",
            "info": "Hiển trị trang _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(Lọc từ _MAX_ của tất cả bài viết)",
            "search": "Tìm bài viết:",
            "paginate": {
                "previous": "Trước",
                "next":"Tiếp"
            }
        }
    } );
    $('#table').on('click', '.confirm', function() {
       swal({
          title: "Bạn Có Muốn Xóa?",
          text: "bạn có thực sự muốn xóa không!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            swal("Lệnh xóa đã được chấp nhận, đang tiến hành xóa !", {
              icon: "success",
            });
            window.location.href = $(this).attr('rel');
          } else {
            swal("Trang này đã được giữ lại!");
          }
        });
    });
});
</script>
@endsection()