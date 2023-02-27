@extends('layouts.admin')
@section("title","Danh Sách Thành Viên")
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
<h1 class="main-title float-left">Thành Viên</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Thành Viên</li>
  <li class="breadcrumb-item active">Danh Sách</li>
</ol>
@endsection()
@section("content")
<?php
$namerole = array();
$color = array(
    1 =>'danger',
    2=>'warning',
    3=>'info',
    4=>'default'
);
foreach($role as $r){
    $namerole[$r->id] = $r->name;
}
$authu = Auth::User();
?>
<div class="row">
 <div class="col-sm-12">
    <div class="card mb-3">
       <div class="card-header">
        @if($authu->is('user.create'))<a href="{!! URL::Route('admin.user.getadd') !!}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm Thành Viên</a>@endif
       </div>
       <div class="card-body">
          <table id="table" class="table">
             <thead>
                <tr>
                   <th scope="col">#</th>
                   <th scope="col">Username</th>
                   <th scope="col">Tên</th>
                   <th scope="col">Họ</th>
                   <th scope="col">SĐT</th>
                   <th scope="col">Email</th>
                   <th scope="col">Quyền</th>
                   <th scope="col">Tình Trạng</th>
                   <th scope="col"></th>
                </tr>
             </thead>
             <tbody>
            <?php $i=1; ?>
            @foreach($users as $user)
            <tr>
                <th scope="row">{{ $i++ }}</th>
                <td>{{ $user["username"] }}</td>
                <td>{{ $user["firstname"] }}</td>
                <td>{{ $user["lastname"] }}</td>
                <td>{{ $user["phone"] }}</td>
                <td>{{ $user["email"] }}</td>
                <td>
                    <span class="btn btn-{{$color[$user["level"]]}} btn-xs">{{$namerole[$user["level"]]}}</span>
                </td>
                <td>
                    @if($user["status"] == 1)
                        <span class="btn btn-success btn-xs">Kích Hoạt</span>
                    @elseif($user["status"] == 0)
                        <span class="btn btn-danger btn-xs">Bị Khóa</span>
                    @else
                        <span class="btn btn-warning btn-xs">Không Xác Định</span>
                    @endif
                </td>
                <td>
                    <a href="{!! URL::Route('admin.user.getEdit', $user['id']) !!}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                    @if($user["email"] != "vantan7211@gmail.com")
                    <a href="#" rel="{!! URL::Route('admin.user.getDelete', $user['id']) !!}" class="btn btn-danger btn-sm confirm"><i class="fa fa-times"></i></a>
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
            "lengthMenu": "Hiển thị _MENU_",
            "zeroRecords": "Chưa có thành viên nào",
            "info": "Hiển trị trang _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(Lọc từ _MAX_ của tất cả thành viên)",
            "search": "Tìm thành viên:",
            "paginate": {
                "previous": "Trước",
                "next":"Tiếp"
            }
        }
    } );
    $('#table').on('click', '.confirm', function() {
       swal({
          title: "Bạn Chắc Là Muốn Xóa?",
          text: "Sau khi bạn xóa, bạn không thể phục hồi được thành viên này!",
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
            swal("Thành viên này đã được giữ lại !");
          }
        });
    });
});
</script>
@endsection()