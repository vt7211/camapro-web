<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="control netsa.vn">
    <meta name="author" content="Dinh Van Tan">

    <link href="{{ URL('/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL('/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL('/css/login.css') }}" rel="stylesheet">
    <title>Control Netsa</title>
</head>
<body>
<div class="main">
    <div class="container">
       <center>
          <div class="middle">
             <div id="login">
             <div class="logomobile hidden-desktop">
                <img src="{{ URL('/images/logo-trang.png') }}" alt="logo">
                <div class="clearfix"></div>
               </div>
              @if(Auth::check())
                <script>window.location = "/admin/dashboard";</script>
              @else
                <form action="{!! route('postlogin') !!}" method="POST" name="login">
                    @include("blocks.errors")
                    @include("blocks.flashmessage")
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset class="clearfix">
                      <p ><span class="fa fa-user"></span><input type="text" name="username" Placeholder="Username" required value="{!! old('username') !!}"></p>
                      <!-- JS because of IE support; better: placeholder="Username" -->
                      <p><span class="fa fa-lock"></span><input name="password" type="password"  Placeholder="Password" required></p>
                      <!-- JS because of IE support; better: placeholder="Password" -->
                      <p class="mb-10px remember">
                        <label>
                          <input type="checkbox" name="remember" value="1"> <span class="label-text">Nhớ đăng nhập</span>
                        </label>
                        </p>
                      <div>
                         <span style="width:48%; text-align:left;  display: inline-block;"><a class="small-text" href="#">Quên mật khẩu ?</a></span>
                         <span style="width:50%; text-align:right;  display: inline-block;"><input type="submit" value="Đăng Nhập"></span>
                      </div>
                   </fieldset>
                   <div class="clearfix"></div>
                </form>
              @endif
                <div class="clearfix"></div>
             </div>
             <!-- end login -->
             <div class="logo hidden-mobile">
                <img src="{{ URL('/images/logo-trang.png') }}" alt="logo">
                <div class="clearfix"></div>
             </div>
          </div>
       </center>
    </div>
</div>


<script src="{{ URL('/js/jquery.min.js') }}"></script>
<script src="{{ URL('/js/bootstrap.min.js') }}"></script>

</body>
</html>
