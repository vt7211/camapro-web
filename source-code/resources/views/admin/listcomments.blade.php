<?php
$str = '';
if($status==0) $str = 'chờ duyệt';
if($status==1) $str = 'đã duyệt';
if($status==2) $str = 'xóa tạm';
if($status==3) $str = 'spam';
?>
@extends('layouts.admin')
@section("title","Quản Lý Bình Luận ".$str)
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
.card-header .btn-primary{
  color:white;
}
#table_wrapper{
    padding:0px !important;
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
<h1 class="main-title float-left">Bình Luận {{$str}}</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Bình Luận</li>
  <li class="breadcrumb-item active">{{$str}}</li>
</ol>
@endsection()
@section("content")
<div class="row">
 <div class="col-sm-12">
    <div class="card mb-3">
       <div class="card-header">
          <a class="<?php if($status=='all') echo 'active'; ?>" href="{!! URL::route('admin.comment.getList',array('news','all')) !!}">Tất cả ({{$total}})</a> | <a href="{!! URL::route('admin.comment.getList',array('news',0)) !!}"  class="<?php if($status=='0') echo 'active'; ?>">Đang chờ ({{$pendding}})</a> | <a href="{!! URL::route('admin.comment.getList',array('news',1)) !!}"  class="<?php if($status==1) echo 'active'; ?>">Được duyệt ({{$active}})</a> | <a href="{!! URL::route('admin.comment.getList',array('news',3)) !!}"  class="<?php if($status==3) echo 'active'; ?>">Spam ({{$spam}})</a> | <a href="{!! URL::route('admin.comment.getList',array('news',2)) !!}"  class="<?php if($status==2) echo 'active'; ?>">ThùngrRác ({{$draft}})</a>
       </div>
       <div class="card-body">
          <table id="table" class="table ">
             <thead>
                <tr>
                   <th>#</th>
                   <th>Người Đăng</th>
                   <th>Nội Dung</th>
                   <th>Tại</th>
                   <th>Tình Trạng</th>
                   <th>Lúc</th>
                </tr>
             </thead>
             <tbody>
            <?php $i=1; ?>
            @foreach($comments as $comment)
            <tr class='{{ $comment["status"]==0 ? "danger" : "" }}'>
                <td scope="row">{{ $i++ }}</td>
                <td>
                    <p>{{ $comment["name_author"] }}</p>
                    <p>{{ $comment["ip_author"] }}</p>
                </td>
                <td>
                    <div>{{$comment["comment_content"] }}</div>
                    <p class="menulist">
                        @if($comment["status"]==1)
                            <a href="{{URL::Route('admin.comment.getStatus',array('news',$comment['id'],0))}}" class="text-danger"><i class="fa fa-eye"></i> đợi duyệt</a>
                        @elseif($comment["status"]==0)
                            <a href="{{URL::Route('admin.comment.getStatus',array('news',$comment['id'],1))}}" class="text-success"><i class="fa fa-check"></i> chấp nhận</a>
                        @elseif($comment["status"]==2)
                            <a href="{{URL::Route('admin.comment.getStatus',array('news',$comment['id'],0))}}" class="text-success"><i class="fa fa-refresh"></i> phục hồi</a>
                        @endif
                        <a href="#" class=""><i class="fa fa-edit"></i> sửa</a>

                        @if(!$comment["status"]==3)
                            <a href="{{URL::Route('admin.comment.getStatus',array('news',$comment['id'],3))}}" class="text-danger"><i class="fa fa-check"></i> spam</a>
                        @endif
                        
                        
                        @if($comment["status"]==2)
                            <a href="{{URL::Route('admin.comment.getStatus',array('news',$comment['id'],2))}}" class="text-danger"><i class="fa fa-recycle"></i> xóa tạm</a>
                        @else
                            <a href="{{URL::Route('admin.comment.getDelete',$comment['id'])}}" class="text-danger"><i class="fa fa-close"></i> xóa vĩnh viễn</a>
                        @endif
                    </p>
                </td>
                <td> {{$comment["post_id"] }}</td>
                <td>
                    @if($comment["status"] == 1)
                        <span class="btn btn-success btn-xs">Đã Đăng</span>
                    @elseif($comment["status"] == 0)
                        <span class="btn btn-danger btn-xs">Đợi Duyệt</span>
                    @elseif($comment["status"] == 2)
                        <span class="btn btn-danger btn-xs">Xóa Tạm</span>
                    @elseif($comment["status"] == 3)
                        <span class="btn btn-danger btn-xs">Spam</span>
                    @else
                        <span class="btn btn-warning btn-xs">Unkonw</span>
                    @endif
                </td>
                <td>
                    {{$comment["created_at"] }}
                </td>
            </tr>
            @endforeach
             </tbody>
          </table>
          @if(count($comments)==0) 
            Chưa có bình luận {{$str}} nào.
          @endif
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
            "lengthMenu": "Hiển thị _MENU_ đánh giá trên 1 trang",
            "zeroRecords": "Chưa có bình luận nào",
            "info": "Hiển trị trang _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(Lọc từ _MAX_ của tất cả đánh giá)",
            "search": "Tìm đánh giá:",
            "paginate": {
                "previous": "Trước",
                "next":"Tiếp"
            }
        }
    } );

    $('#table').on('click', '.confirm', function() {
       swal({
          title: "Bạn Chắn Chắc Xóa ?",
          text: "Sau khi bạn xóa bạn không thể phục hồi được bình luận này!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            swal("Đang xử lý xóa bình luận!", {
              icon: "success",
            });
            window.location.href = $(this).attr('rel');
          } else {
            swal("Bình luận đã được giữ lại!");
          }
        });
    });
});
</script>
@endsection()
