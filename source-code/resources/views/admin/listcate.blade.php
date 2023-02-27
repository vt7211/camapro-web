<?php
$namecat = '';
if($type=='news') $namecat = 'tin tức';
?>
@extends('layouts.admin')
@section("title","Danh Sách ".$namecat)
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
<h1 class="main-title float-left">Danh Sách Nhóm {{$namecat}}</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">{{$namecat}}</li>
  <li class="breadcrumb-item active">Danh Sách</li>
</ol>
<?php
$authu = Auth::User();
?>
@endsection()
@section("content")
<div class="row">
 <div class="col-sm-12">
    <div class="card mb-3">
       <div class="card-header">
         @if($authu->is('cate.create'))<a href="{!! URL::Route('admin.cate.getAdd',$type) !!}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm Nhóm {{$namecat}}</a>@endif
       </div>
       <div class="card-body">
          <table id="table" class="table table-responsive-xl table-striped">
             <thead>
                <tr>
                   <th>Ảnh</th>
                   <th scope="col">Tên</th>
                   <th scope="col">Số Lượng</th>
                   <th>Tạo Lúc</th>
                   <th scope="col">#</th>
                </tr>
             </thead>
             <tbody>
            <?php
            //dd($data);
            cate_echo($data, 0, $str = "--", $type,$type);
            ?>
            @if($count==0)
            <tr><td colspan="5">Chưa có nhóm nào</td></tr>
            @endif
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

    $('#table').on('click', '.confirm', function() {
       swal({
          title: "Bạn có chắc chắn?",
          text: "Sau khi bạn xóa, bạn không thể phục hồi nhóm này!",
          icon: "warning",
          buttons: ["Không","Có"],
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = $(this).attr('rel');
          }
        });
    });
});
</script>
@endsection()