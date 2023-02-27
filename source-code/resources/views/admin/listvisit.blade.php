
@extends('layouts.admin')
@section("title","Quản Lý Lượt Truy Cập ")
@section("css")
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
<h1 class="main-title float-left">Lượt Truy Cập</h1>
<ol class="breadcrumb float-right">
  <li class="breadcrumb-item">Tổng Quan</li>
  <li class="breadcrumb-item">Lượt Truy Cập</li>
  <li class="breadcrumb-item active">Danh Sách</li>
</ol>
@endsection()
@section("content")
<div class="row">
 <div class="col-sm-12">
    <div class="card mb-3">
        <form class="form-inline card-header" autocomplete="off" method="get" action="{{ URL::Route('admin.visit.getList') }}" >
            <div class="form-group mr-2 mb-2">
                <input class="form-control date" placeholder="Từ ngày" type="text" value="<?php echo $start; ?>" name="start">
            </div>
            <div class="form-group mr-2 mb-2">
                <input class="form-control date" placeholder="Đến ngày" type="text" value="<?php echo $end; ?>" name="end">
            </div>
            <div class="form-group mr-2 mb-2">
                <input class="form-control" placeholder="Referer" type="text" value="<?php echo $referer; ?>" name="referer">
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary mb-2">Lọc</button>
        </form>
       <div class="card-body">
          <table id="table" class="table ">
             <thead>
                <tr>
                   <th>#</th>
                   <th>IP</th>
                   <th>Referer</th>
                   <th>Trình Duyệt</th>
                   <th>Lúc</th>
                   <!-- <th></th> -->
                </tr>
             </thead>
             <tbody>
            <?php $i=1; ?>
            @foreach($visits as $item)
            <tr class=''>
                <td scope="row">{{ $i++ }}</td>
                <td>{{$item->ip}}</td>
                <td>{{$item->referer}}</td>
                <td>{{$item->user_agent}}</td>
                <td>{{date("d/m/Y G:i", strtotime($item->created_at))}}</td>
                <!-- <td></td> -->
            </tr>
            @endforeach
             </tbody>
          </table>
          @if(count($visits)==0) 
            Chưa có lượt truy cập nào.
          @endif
          {{ $visits->appends(request()->except('page'))->links() }}
       </div>
    </div>
    <!-- end card-->
 </div>
</div>
@endsection()
@section("script")
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    $('.date').datepicker($.extend({}, $.datepicker.regional['vn'], {
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    }));
    
});
</script>
@endsection()
