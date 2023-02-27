<?php
$namer = Route::current()->getName();
$authu = Auth::User();
?>
<div class="sidebar-inner leftscroll">
   <div id="sidebar-menu">
      <ul>
         <li class="">
            <a class="{{ $namer == 'admin.dashboard' ? 'active' : '' }}" href="{!! URL::route('admin.dashboard') !!}"><i class="fa fa-fw fa-bars"></i><span> Tổng Quan</span> </a>
         </li>
         <li class="">
            <a class="" href="/media" target="_blank"><i class="fa fa-fw fa-image"></i><span> Media</span> </a>
         </li>
         @if($authu->is('user.list'))
         <li class="submenu">
            <a class="{{ $namer == 'admin.user.getEdit' ? 'active' : '' }}" href="#"><i class="fa fa-fw fa-users"></i> <span> Thành Viên </span> <span class="menu-arrow"></span></a>
            <ul class="list-unstyled">
               <li><a class="{{ $namer == 'admin.user.getList' ? 'active' : '' }}" href="{!! URL::route('admin.user.getList') !!}">Danh Sách</a></li>
               @if($authu->is('user.create'))<li><a class="{{ $namer == 'admin.user.getadd' ? 'active' : '' }}" href="{!! URL::Route('admin.user.getadd') !!}">Thêm Thành Viên</a></li>@endif
            </ul>
         </li>
         @endif
         @if($authu->is('news.list'))
         <!-- <li class="submenu">
            <a class="{{ $namer == 'admin.news.getEdit' && $type == 'news' ? 'active' : '' }}" href="#"><i class="fa fa-fw fa-newspaper-o"></i> <span> Tin Tức</span> <span class="menu-arrow"></span></a>
            <ul class="list-unstyled">
               <li><a class="{{ $namer == 'admin.news.getList' && $type == 'news' ? 'active' : '' }}" href="{!! URL::route('admin.news.getList',array('news','all')) !!}">Danh Sách Tin</a></li>
               @if($authu->is('news.create'))<li><a class="{{ $namer == 'admin.news.getadd' && $type == 'news' ? 'active' : '' }}" href="{!! URL::Route('admin.news.getadd','news') !!}">Thêm Tin</a></li>@endif
               @if($authu->is('cate.list'))<li><a class="{{ ($namer == 'admin.cate.getList' || $namer == 'admin.cate.getEdit' || $namer == 'admin.cate.getAdd') && $type=='news' ? 'active' : '' }}" href="{!! URL::Route('admin.cate.getList','news') !!}">Nhóm Tin</a></li>@endif
            </ul>
         </li> -->
         @endif
         @if($authu->is('comment.list'))
         <!-- <li class="">
            <a class="{{ $namer == 'admin.comment.getList' ? 'active' : '' }}" href="{!! URL::route('admin.comment.getList',array('news','all')) !!}"><i class="fa fa-fw fa-comments"></i> <span> Bình Luận</span> </a>
         </li> -->
         @endif
         <li class="">
            <a class="{{ $namer == 'admin.visit.getList' ? 'active' : '' }}" href="{!! URL::route('admin.visit.getList') !!}"><i class="fa fa-fw fa-user"></i> <span> Lượt Truy Cập</span> </a>
         </li>
         @if($authu->is('news.list'))
         <!-- <li class="submenu">
            <a class="{{ $namer == 'admin.page.getEdit' ? 'active' : '' }}" href="#"><i class="fa fa-fw fa-file-o"></i> <span> Trang Tĩnh</span> <span class="menu-arrow"></span></a>
            <ul class="list-unstyled">
               <li><a class="{{ $namer == 'admin.page.getList' ? 'active' : '' }}" href="{!! URL::route('admin.page.getList','all') !!}">Danh Sách Trang</a></li>
               @if($authu->is('news.create'))<li><a class="{{ $namer == 'admin.page.getadd' ? 'active' : '' }}" href="{!! URL::Route('admin.page.getadd') !!}">Thêm Trang</a></li>@endif
            </ul>
         </li> -->
         @endif
         @if($authu->is('menu.list'))
         <!-- <li class="">
            <a class="{{ $namer == 'admin.menu.getList' ? 'active' : '' }}" href="{!! URL::route('admin.menu.getList',0) !!}"><i class="fa fa-fw fa-list"></i> <span> Danh Mục</span> </a>
         </li> -->
         @endif
         @if($authu->is('setting.list'))
         <li class="">
            <a class="{{ $namer == 'admin.setting.getList' ? 'active' : '' }}" href="{!! URL::route('admin.setting.getList') !!}"><i class="fa fa-fw fa-cogs"></i> <span> Cấu Hình</span> </a>
         </li>
         @endif
      </ul>
      <div class="clearfix"></div>
   </div>
   <div class="clearfix"></div>
</div>