@include('layouts.headerAdmin')
@include('layouts.headbarAdmin')
<!-- Left Sidebar -->
<div class="left main-sidebar">
@include('layouts.sidebarAdmin')
</div>
<!-- End Sidebar -->
<div class="content-page">
<!-- Start content -->
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xl-12">
            <div class="breadcrumb-holder">
                @yield("breadcrumb")
               <div class="clearfix"></div>
            </div>
         </div>
      </div>
      <!-- end row -->
    @if(Session::has("flash_message"))
         <div class="row">
            <div class="col-lg-12 alert alert-{!! Session::get('flash_level') !!}">
                  {!! Session::get("flash_message") !!}
            </div>
         </div>
    @endif
    @yield('content')
   </div>
   <!-- END container-fluid -->
</div>
<!-- END content -->
</div>
<!-- END content-page -->
@include('layouts.footerAdmin')