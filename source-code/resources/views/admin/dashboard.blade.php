@extends('layouts.admin')
@section("title","Quản Lý Admin")
@section("css")
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
@endsection()
@section("breadcrumb")
    <h1 class="main-title float-left">Quản Lý Admin</h1>
    <ol class="breadcrumb float-right">
      <li class="breadcrumb-item">Tổng Quan</li>
      <li class="breadcrumb-item active">Admin</li>
    </ol>
@endsection()
@section("content")
    <div class="alert alert-success" role="alert">
         <h4 class="alert-heading">Chào Mừng Bạn Đến Với Admin Netsa.vn !</h4>
         <p>Đây là trang quản trị admin, chứa các thông tin chung về website.</p>
         <p>Các thông tin quản lý: thống kê tin tức, nhóm tin tức, thành viên... Mọi thắc mắc về việc sử dụng hoặc cần nâng cấp chức năng bạn có thể liên hệ theo thông tin bên dưới.</p>
         <p>Email: <a href="mailto:vantan@netsa.vn">vantan@netsa.vn</a> - SĐT: <a href="tel:0909979367">0909 979 367</a></p>
    </div>
    <div class="row">
     <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box noradius noborder bg-default">
           <i class="fa fa-file-text-o float-right text-white"></i>
           <h6 class="text-white text-uppercase m-b-20">Tin Tức</h6>
           <h1 class="m-b-20 text-white counter">{{$data['news']}}</h1>
           <span class="text-white">{{$data['news']}} bài tin tức</span>
        </div>
     </div>
     <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box noradius noborder bg-warning">
           <i class="fa fa-user-o float-right text-white"></i>
           <h6 class="text-white text-uppercase m-b-20">Thành Viên</h6>
           <h1 class="m-b-20 text-white counter">{{$data['users']}}</h1>
           <span class="text-white">{{$data['users']}} thành viên</span>
        </div>
     </div>
     <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box noradius noborder bg-info">
           <i class="fa fa-comments float-right text-white"></i>
           <h6 class="text-white text-uppercase m-b-20">Bình Luận</h6>
           <h1 class="m-b-20 text-white counter">{{$data['comments']}}</h1>
           <span class="text-white">{{$data['comments']}} bình luận mới</span>
        </div>
     </div>
     <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box noradius noborder bg-danger">
           <i class="fa fa-bell-o float-right text-white"></i>
           <h6 class="text-white text-uppercase m-b-20">Thông Báo</h6>
           <h1 class="m-b-20 text-white counter">{{$data['alerts']}}</h1>
           <span class="text-white">{{$data['alerts']}} thông báo mới</span>
        </div>
     </div>
    </div>
    <div class="row">
     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <div class="card mb-3">
           <div class="card-header">
              <h3><i class="fa fa-newspaper-o"></i> Tin Tức Mới Nhất</h3>
           </div>
           <div class="card-body">
              <table class="table table-bordered table-responsive-xl table-hover display table">
                 <thead>
                    <tr>
                       <th>Tiêu Đề</th>
                       <th>Lúc</th>
                       <th>#</th>
                    </tr>
                 </thead>
                 <tbody>
                    @foreach($data['datanews'] as $new)
                    <tr>
                       <td>{{$new->name}}</td>
                       <td>{{$new->created_at}}</td>
                       <td>
                        @if($new->status==0)
                            <span class="btn btn-warning btn-xs">lưu nháp</span>
                        @else
                            <span class="btn btn-success btn-xs">đã đăng</span>
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
     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
        <div class="card mb-3">
           <div class="card-header">
              <h3><i class="fa fa-comments"></i> Bình Luận Mới Nhất</h3>
           </div>
           <div class="card-body">
              <table class="table table-bordered table-responsive-xl table-hover display table">
                 <thead>
                    <tr>
                       <th>Nội Dung</th>
                       <th>Lúc</th>
                       <th>#</th>
                    </tr>
                 </thead>
                 <tbody>
                    @foreach($data['datacomments'] as $comment)
                    <tr>
                       <td>{{$comment->comment_content}}</td>
                       <td>{{$comment->created_at}}</td>
                       <td>
                        @if($comment->status==0)
                            <span class="btn btn-warning btn-xs">chờ duyệt</span>
                        @else
                            <span class="btn btn-success btn-xs">đã đăng</span>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script>
 $(document).ready(function() {
    var table = $('.table').DataTable( {
        //"scrollY": "350px",
        "paging": true,
        "language": {
            "lengthMenu": "Hiển thị _MENU_",
            "zeroRecords": "Chưa có dữ liệu",
            "info": "Hiển trị trang _PAGE_ / _PAGES_",
            "infoEmpty": "",
            "infoFiltered": "(Lọc từ _MAX_ của tất cả dữ liệu)",
            "search": "Tìm dữ liệu:",
            "paginate": {
                "previous": "Trước",
                "next":"Tiếp"
            }
        }
    } );
     // counter-up
     $('.counter').counterUp({
         delay: 10,
         time: 600
     });
 } );
</script>
@endsection()