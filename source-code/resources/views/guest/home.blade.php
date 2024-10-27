<?php

?>
@extends('guest.index')
@section("description",$data['description']['value'])
@section("title",$data['titleweb']['value'])
@section("css")
<link rel="stylesheet" href="{{ URL('assets/guest/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ URL('assets/guest/css/nivo-slider.css') }}">
<style>

</style>
@section("content")
<div id="wppopup" class="">
    <div id="contentpopup">
        <div class="wpcontentpopup">
            <a href="#" class="btnclosePopup">Đóng</a>
            <a class="linkBNPop" href="#">
                <img class="imgBNPop" src="" alt="combo massage">
            </a>
        </div>
    </div>
</div>
<div class="tabcontent active scrollpane" data-tab="home">
    <div class="main tabchild active listitems" id="dscoso"></div>
    <div class="tabchild listitems pt-10px" id="searchcoso"><div class="wplistcoso"></div><div class="clearfix"></div></div>
    <div class="tabchild bgxam" id="detailcoso"></div>
    <div class="tabchild" id="singlenews_home"></div>
    <div class="tabchild" id="detaildeal" style="padding-bottom: 60px"></div>
    <div class="tabchild" id="detailcombo" style="padding-bottom: 60px"></div>
    <div class="tabchild bgxam" style="padding: 0px;" id="chatcoso"></div>
    <div class="col-md-12 tabchild modal" id="banggiave" >
        <div class="contentmodal">
            <div class="mainmodal">
                <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                <h3 class="titlemodal">Bảng Giá Vé</h3>
                <div id="ndbanggia"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12 tabchild modal" id="successbook" >
        <div class="contentmodal">
            <div class="mainmodal">
                <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                <h3 class="titlemodal blue text-center">Mã: <span class="code">12344</span></h3>
                <div class="noidung">
                    <div class="text-center">
                        <p><b>Thời hạn sử dụng đến: <span class="text-danger hansudung">12/12/2022</span></b></p>
                        <p class="text-success txt">Bạn đã lấy mã thành công</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 tabchild modal" id="candanhagia" >
        <div class="contentmodal">
            <div class="mainmodal">
                <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                <h3 class="titlemodal blue text-center">Có Mã Cần Đánh Giá</h3>
                <div class="noidung">
                    <div class="text-center">
                        <p class="mb-10px">Bạn có mã cần đánh giá</p>
                        <span class="btn btn-block btn-primary action" data-nameaction="view_danhgiacode">Đánh Giá Ngay</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 tabchild modal" id="modalsearchcoso" >
        <div class="contentmodal">
            <div class="mainmodal">
                <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                <h3 class="titlemodal blue text-center">Lọc Cơ Sở</h3>
                <div class="noidung">
                    <select name="diachi_id" id="diachi_id" class="form-control mb-15px">
                        <option value="">Tất cả khu vực</option>
                    </select>
                    <input type="text" name="tencoso" id="tencoso" class="form-control mb-15px" placeholder="Tên cơ sở">
                    <span class="btn btn-block btn-primary action" data-nameaction="act_searchcoso">Tìm Kiếm</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tabcontent" data-tab="map">
    <div class="main tabchild active" style="padding-bottom: 0px;" id="map">
        <div id="acf-map" style="height: calc( 100% - 0px );" data-zoom="16"></div>
    </div>
</div>
<div class="tabcontent" data-tab="news">
    <div class="main tabchild active pt-10px" id="listnews"></div>
    <div class="tabchild" id="singlenews"></div>
</div>
<div class="tabcontent" data-tab="spin">
    <div class="main tabchild active bgxam" id="listgame"></div>
    <div class="tabchild bgxam" id="getvongquay">
        <div class="col-md-12 relative">
            <div id="wpquydinhquay">
                <div id="quydinhquay" class="content mb-10px"></div>
                <a href="#" class="btnviewmore btn-xs btn btn-primary btn-circle"></a>
            </div>
        </div>
        <div class="col-md-12">
            <a href="#" class="btn btn-block btn-primary action mb-15px" data-nameaction="doiluotquay">Đổi Lượt Quay</a>
        </div>
        <div id="listquay"></div>
    </div>
    <div class="tabchild " style="padding: 0px;" id="singlespin">
        <iframe id="iframespin" src="" style="height: calc( 100% - 120px ); width: 100%;" frameborder="0"></iframe>
    </div>
</div>
<div class="tabcontent " data-tab="account">
    <div class="container">
        <div class="row">
            <div class="tabchild pb mt-15px bgxam main" id="accountlogin">
                <div class="listitem red"><span class="title">Số <span class="SKU_POINT"></span></span><span class="sopoint"></span></div>
                <div class="listitem"><span class="title">Tên</span><span class="nameaccount"></span></div>
                <div class="listitem"><span class="title">SĐT</span><span class="sdtaccount"></span></div>
                <div class="listitem"><span class="title">Cấp</span><span class="capaccount"></span></div>
                <div class="listitem"><span class="title">Điểm</span><span class="diemaccount"></span></div>
                <div class="beakline"></div>
                <div class="wpupvip">
                    <div class="col-sm-12 col-xs-12 col-xxs-12 mb-10px">
                        <h3 class="namesing" style="color: red;">NÂNG CẤP THÀNH VIÊN VIP</h3>
                        <p>(Chọn mua gói bên dưới để lấy được mã khuyến mãi)</p>
                    </div>
                    <div class="col-sm-4 col-xs-4 col-xxs-4">
                        <a class="buyvip action" data-confirm="true" data-msgconfirm="Bạn có chắc chắn muốn nâng cấp vip không?" data-month="1" data-nameaction="buyvip" href="#">
                            <div class="sothangbuy">1 Tháng</div>
                            <b class="sopointbuy"><span class="money1thang"></span> <span class="SKU_POINT"></span></b>
                            <p class="buygiam">&nbsp;</p>
                        </a>
                    </div>
                    <div class="col-sm-4 col-xs-4 col-xxs-4">
                        <a class="buyvip action" data-confirm="true" data-msgconfirm="Bạn có chắc chắn muốn nâng cấp vip không?" style="background-color: #d9dadb" data-nameaction="buyvip" data-month="6" href="#">
                            <div class="sothangbuy">6 Tháng</div>
                            <b class="sopointbuy"><span class="money6thang"></span> <span class="SKU_POINT"></span></b>
                            <p class="buygiam">Giảm <span class="tk6"></span> <span class="SKU_POINT"></span></p>
                        </a>
                    </div>
                    <div class="col-sm-4 col-xs-4 col-xxs-4">
                        <a class="buyvip action" data-confirm="true" data-msgconfirm="Bạn có chắc chắn muốn nâng cấp vip không?" style="background-color: #f8cd59" data-nameaction="buyvip" data-month="12" href="#">
                            <div class="sothangbuy">12 Tháng</div>
                            <b class="sopointbuy"><span class="money12thang"></span> <span class="SKU_POINT"></span></b>
                            <p class="buygiam">Giảm <span class="tk12"></span> <span class="SKU_POINT"></span></p>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="beakline"></div>
                <div class="listitem red"><i class="fa fa-lock ico"></i><span class="hanaccount"></span></div>
                <div class="listitem"><a class="action" data-nameaction="single" id="actionhdnt" data-id="huongdannaptien" data-title="Hướng dẫn nạp tiền Camapro Vip" href="#"><i class="fa fa-money ico"></i> Hướng dẫn nạp tiền vào ví Camapro <i class="fa fa-angle-right"></i></a> </div>
                <div class="listitem"><a class="action" data-nameaction="lichsupoint" data-title="Lịch Sử Giao Dịch {{env('SKU_POINT')}}" href="#"><i class="fa fa-clock-o ico"></i> Lịch Sử Giao Dịch {{env('SKU_POINT')}} <i class="fa fa-angle-right"></i></a> </div>
                <div class="beakline"></div>
                <div class="listitem"><a class="action" data-nameaction="listcode" data-title="Mã khuyến mãi của tôi" href="#"><i class="fa fa-tag ico"></i> Mã khuyến mãi của tôi <i class="fa fa-angle-right"></i></a> </div>
                <div class="listitem"><a class="action" data-nameaction="listfavourite" data-title="Cơ sở yêu thích" href="#"><i class="fa fa-heart-o ico"></i> Cơ sở yêu thích <i class="fa fa-angle-right"></i></a> </div>
                <div class="listitem"><a class="action" data-nameaction="single" data-id="chinhsuathongtin" data-title="Chỉnh sửa thông tin tài khoản" href="#"><i class="fa fa-edit ico"></i> Chỉnh sửa thông tin tài khoản <i class="fa fa-angle-right"></i></a> </div>
                <div class="listitem"><a class="action" data-nameaction="single" data-id="quyenloitv" data-title="Quyền lợi thành viên" href="#"><i class="fa fa-user ico"></i> Quyền lợi thành viên <i class="fa fa-angle-right"></i></a> </div>
                <div class="listitem"><a class="action" data-nameaction="nguoigioithieu" href="#"><i class="fa fa-group ico"></i> Người giới thiệu </a> </div>
                <div class="listitem"><a class="action" data-nameaction="modal" data-id="support" href="#"><i class="fa fa-support ico"></i> Hỗ trợ kỹ thuật </a> </div>
                <div class="listitem red"><a class="action" data-nameaction="single" data-title="Đăng ký làm cơ sở Camapro" data-id="dklamcoso" href="#"><i class="fa fa-handshake-o ico"></i> Đăng ký làm cơ sở Camapro </a> </div>
                <div class="beakline"></div>
                <div class="listitem red"><a class="action" href="#" data-nameaction="logout"><i class="fa fa-sign-out ico"></i> Đăng xuất </a> </div>
            </div>
            <div class="tabchild" id="huongdannaptien"></div>
            <div class="tabchild" id="quyenloitv"></div>
            <div class="tabchild listitems pt-10px" id="lichsupoint"></div>
            <div class="tabchild listitems pt-10px" id="listcode"></div>
            <div class="tabchild listitems pt-10px" id="listfavourite"></div>
            <div class="col-md-12 text-center tabchild" id="chinhsuathongtin" style="padding-top: 30px;">
                <div style="width: 70%; margin: 0px auto;">
                    <img src="/images/logo-md.png" alt="logo" class="mb-20px" style="">
                    <h2 class="title-form">Chỉnh Sửa Tài Khoản</h2>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-user"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="Tên của bạn">
                    </div>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-road"></i></span>
                        <input type="text" name="address" class="form-control" placeholder="Địa chỉ">
                    </div>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-envelope"></i></span>
                        <input type="text" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-calendar"></i></span>
                        <input type="text" name="ngaysinh" class="form-control date" placeholder="Ngày sinh">
                    </div>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-key"></i></span>
                        <input type="password" name="pass1" class="form-control" placeholder="Mật khẩu">
                    </div>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-key"></i></span>
                        <input type="password" name="pass2" class="form-control" placeholder="Nhập lại mật khẩu">
                    </div>
                    <a href="#" class="btn btn-primary btn-block mb-10px action" data-nameaction="edituser">Lưu Thay Đổi</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-12 text-center tabchild modal" id="support" >
                <div class="contentmodal">
                    <div class="mainmodal">
                        <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                        <h3 class="titlemodal">Hỗ Trợ Kỹ Thuật</h3>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-4 col-xxs-5">
                                <a href="" target="_blank" id="linksp_fb"><img src="/images/logo-facebook.png" alt="facebook"><p>Facebook</p></a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 col-xxs-5">
                                <a href="" target="_blank" id="linksp_zalo"><img src="/images/logo-zalo.png" alt="facebook"><p>Zalo</p></a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 col-xxs-5">
                                <a href="" target="_blank" id="linksp_hotline"><img src="/images/icon-hotline.png" alt="facebook"><p>Hotline</p></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 tabchild modal" id="danhgiacode" >
                <div class="contentmodal">
                    <div class="mainmodal">
                        <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                        <h3 class="titlemodal text-center">Đánh Giá <span class="sku"></span></h3>
                        <div class="noidung">
                            <section class='rating-widget text-center mb-15px'>
                                <!-- Rating Stars Box -->
                                <div class='rating-stars text-center'>
                                    <ul id='stars'>
                                        <li class='star' title='Poor' data-value='1'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star' title='Fair' data-value='2'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star' title='Good' data-value='3'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star' title='Excellent' data-value='4'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star' title='WOW!!!' data-value='5'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class='success-box hidden'>
                                    <div class='clearfix'></div>
                                    <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                                    <div class='text-message'>Bạn hãy đánh giá khách hàng</div>
                                    <div class='clearfix'></div>
                                </div>
                            </section>
                            <input type="text" name="tenktv" autocomplete="off" class="form-control mb-15px" placeholder="Số ktv">
                            <input type="text" name="note" autocomplete="off" class="form-control mb-15px" placeholder="Nội dung đánh giá">
                            <input type="hidden" name="code" >
                            <input type="hidden" name="star" class="stardgcode">
                            <span class="btn btn-block btn-primary action" data-nameaction="act_danhgiacode">Gửi Đánh Giá</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 tabchild modal" id="showqrcode" >
                <div class="contentmodal">
                    <div class="mainmodal">
                        <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                        <h3 class="titlemodal text-center"><span class="sku"></span></h3>
                        <div class="noidung"></div>
                        <span style="width: 330px; margin: 0px auto;" class="btn btn-block btn-danger action" data-nameaction="closemodal">Đóng</span>
                    </div>
                </div>
            </div>
            <div class="col-md-12 tabchild modal" id="lydohethan" >
                <div class="contentmodal">
                    <div class="mainmodal">
                        <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                        <h3 class="titlemodal text-center">Lý Do Hết Hạn <span class="sku"></span></h3>
                        <div class="noidung">
                            <select name="lydohh_id" id="lydohh_id" class="form-control mb-15px">
                                <option value="">Chọn lý do hết hạn</option>
                            </select>
                            <input type="text" name="note" class="form-control mb-15px" placeholder="Ghi chú thêm">
                            <input type="hidden" name="code" >
                            <span class="btn btn-block btn-primary action" data-nameaction="act_lydohethan">Gửi Lý Do</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center tabchild" id="dklamcoso" style="padding-top: 30px;">
                <div style="width: 70%; margin: 0px auto;">
                    <img src="/images/logo-md.png" alt="logo" class="mb-20px" style="">
                    <h2 class="title-form">Đăng Ký Làm Cơ Sở Camapro</h2>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-building"></i></span>
                        <input type="text" name="namecoso" class="form-control" placeholder="Tên cơ sở">
                    </div>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-phone"></i></span>
                        <input type="text" name="phonecoso" class="form-control" placeholder="Số điện thoại cơ sở">
                    </div>
                    <div class="input-group mb-15px">
                        <span class="input-group-addon" id=""><i class="fa fa-road"></i></span>
                        <input type="text" name="addresscoso" class="form-control" placeholder="Địa chỉ cơ sở">
                    </div>
                    <a href="#" class="btn btn-primary btn-block mb-10px action" data-login="1" data-nameaction="dklamcoso">Đăng Ký Cơ Sở</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-12 text-center accountnotlogin tabchild active" id="accountnotlogin" style="padding-top: 30px;">
                <div style="width: 80%; margin: 0px auto;">
                    <div class="ndmain gr active" id="grlogin">
                        <h2 class="title-form">Đăng Nhập</h2>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-phone"></i></span>
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại">
                        </div>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-key"></i></span>
                            <input type="password" name="pass" class="form-control" placeholder="Mật khẩu">
                        </div>
                        <p class="text-right"><a href="#" class="change_gr" data-gr="grforget">Quên mật khẩu</a></p>
                        <a href="#" class="btn btn-primary btn-block mb-10px btnlogin action" data-nameaction="login">Đăng Nhập</a>
                        <div class="tabbtndn">
                            <div class="row">
                                <span class="item col-xs-6"><a href="#" class="change_gr" data-gr="grdktv">Đăng ký thành viên</a></span>
                                <span class="item col-xs-6"><a href="#" class="change_gr red" data-gr="grdkcs">Đăng Ký làm cơ sở</a></span>
                            </div>
                        </div>
                        <div class="alert" style="color: #888;">
                            <p>Hỗ trợ kỹ thuật: <a href="" class="linkhotline" style="color: red;">xxx</a></p>
                        </div>
                    </div>
                    <div class="ndmain gr" id="grdktv">
                        <h2 class="title-form">Đăng Ký Thành Viên</h2>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-phone"></i></span>
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại">
                        </div>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-user"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="Tên của bạn">
                        </div>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-key"></i></span>
                            <input type="password" name="pass1" class="form-control" placeholder="Mật khẩu">
                        </div>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-key"></i></span>
                            <input type="password" name="pass2" class="form-control" placeholder="Nhập lại mật khẩu">
                        </div>
                        <a href="#" class="btn btn-primary btn-block mb-10px action" data-nameaction="register">Đăng Ký</a>
                        <div class="tabbtndn">
                            <div class="row">
                                <span class="item col-xs-6"><a href="#" class="change_gr" data-gr="grlogin">Đăng nhập</a></span>
                                <span class="item col-xs-6"><a href="#" class="change_gr red" data-gr="grdkcs">Đăng Ký làm cơ sở</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="ndmain gr " id="grotp">
                        <h2 class="title-form">Nhập Mã OTP</h2>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-tag"></i></span>
                            <input type="text" name="otp" class="form-control" placeholder="Mã OTP trong tin nhắn sms">
                            <a href="#" class="input-group-addon action" data-nameaction="resentcode"><i class="fa fa-send"></i> Gửi Lại</a>
                        </div>
                        <a href="#" class="btn btn-primary btn-block mb-10px action" data-nameaction="confirm">Xác Nhận</a>
                    </div>
                    <div class="ndmain gr" id="grforget">
                        <h2 class="title-form">Quên Mật Khẩu</h2>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-phone"></i></span>
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại">
                        </div>
                        <a href="#" class="btn btn-primary btn-block mb-10px action" data-nameaction="resetpass">Lấy Lại Mật Khẩu</a>
                        <div class="tabbtndn">
                            <div class="row">
                                <span class="item col-xs-6"><a href="#" class="change_gr" data-gr="grlogin">Đăng nhập</a></span>
                                <span class="item col-xs-6"><a href="#" class="change_gr red" data-gr="grdkcs">Đăng Ký làm cơ sở</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="ndmain gr" id="grotpChangePass">
                        <h2 class="title-form">Nhập Mã OTP</h2>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-tag"></i></span>
                            <input type="text" name="grotpreset" class="form-control" placeholder="Mã OTP trong tin nhắn sms">
                        </div>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-key"></i></span>
                            <input type="password" name="pass1" class="form-control" placeholder="Mật khẩu mới">
                        </div>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-key"></i></span>
                            <input type="password" name="pass2" class="form-control" placeholder="Nhập lại mật khẩu mới">
                        </div>
                        <a href="#" class="btn btn-primary btn-block mb-10px action" data-nameaction="confirmresetpass">Xác Nhận Đổi Mật Khẩu</a>
                    </div>
                    <div class="ndmain gr" id="grdkcs">
                        <h2 class="title-form">Đăng Ký Làm Cơ Sở Camapro</h2>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-building"></i></span>
                            <input type="text" name="namecoso" class="form-control" placeholder="Tên cơ sở">
                        </div>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-phone"></i></span>
                            <input type="text" name="phonecoso" class="form-control" placeholder="Số điện thoại cơ sở">
                        </div>
                        <div class="input-group mb-15px">
                            <span class="input-group-addon" id=""><i class="fa fa-road"></i></span>
                            <input type="text" name="addresscoso" class="form-control" placeholder="Địa chỉ cơ sở">
                        </div>
                        <a href="#" class="btn btn-primary btn-block mb-10px action" data-nameaction="dklamcoso">Đăng Ký Cơ Sở</a>
                        <div class="tabbtndn">
                            <div class="row">
                                <span class="item col-xs-6"><a href="#" class="change_gr" data-gr="grlogin">Đăng nhập</a></span>
                                <span class="item col-xs-6"><a href="#" class="change_gr red" data-gr="grdkcs">Đăng Ký làm cơ sở</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="tabchild bgxam" id="detailcoso2"></div>
            <div class="tabchild bgxam" style="padding: 0px;" id="chatcoso2"></div>
            <div class="col-md-12 tabchild modal" id="banggiave2" >
                <div class="contentmodal">
                    <div class="mainmodal">
                        <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                        <h3 class="titlemodal">Bảng Giá Vé</h3>
                        <div id="ndbanggia2"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 tabchild modal" id="successbook2" >
                <div class="contentmodal">
                    <div class="mainmodal">
                        <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                        <h3 class="titlemodal blue text-center">Mã: <span class="code">12344</span></h3>
                        <div class="noidung">
                            <div class="text-center">
                                <p><b>Thời hạn sử dụng đến: <span class="text-danger hansudung">12/12/2022</span></b></p>
                                <p class="text-success txt">Bạn đã lấy mã thành công</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 tabchild modal" id="searchlspoint" >
                <div class="contentmodal">
                    <div class="mainmodal">
                        <span class="action btnclose" data-nameaction="closemodal"><i class="fa fa-close"></i></span>
                        <h3 class="titlemodal blue text-center">Lọc Lịch Sử</h3>
                        <div class="noidung">
                            <input type="text" name="tensearch" id="tenlspointsearch" class="form-control mb-15px" placeholder="Tên giao dịch">
                            <input type="text" name="ngaybatdau" id="ngaybatdaulsp" class="form-control date mb-15px" placeholder="Ngày bắt đầu">
                            <input type="text" name="ngayketthuc" id="ngayketthuclsp" class="form-control date mb-15px" placeholder="Ngày kết thúc">
                            <span class="btn btn-block btn-primary action" data-nameaction="act_lichsupoint">Lọc</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="loadmore"></div>
@endsection()
@if(Session::has("flash_message"))<div id="noticesucc"></div>@endif
@section("script")

@endsection()
