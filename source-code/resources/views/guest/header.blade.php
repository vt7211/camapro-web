<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>@yield("title")</title>
   <meta name="description" content="@yield('description')">
   <meta name="keywords" content="@yield('keywords')">
   <meta name="author" content="Development by Van Tan">
   <meta property="og:image" content="{{ URL('/images/camapro.jpg') }}" />
   <meta property="og:url" content="<?php echo curPageURL(); ?>" />
   <meta property="og:title" content="@yield('title')" />
   <meta property="og:description" content="@yield('description')" />
   <meta property="og:type" content="article" />
   <meta name="theme-color" content="#1a73a9" >
   <!-- Favicon -->
   <link rel="shortcut icon" href="{{ URL('/images/logo.jpg') }}">
   <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700&display=swap" rel="stylesheet"> 
   <link href="{{ URL('assets/guest/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link href="{{ URL('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

   <link href="{{ URL('assets/guest/css/bootstrap-rating.css') }}" rel="stylesheet" type="text/css" />
   <link href="{{ URL('assets/guest/v015/guest.css') }}" rel="stylesheet" type="text/css" />
   <!-- BEGIN CSS for this page -->
   @yield("css")
   <!-- END CSS for this page -->
   <script>
      window.Laravel = <?php echo json_encode([
         'csrfToken' => csrf_token(),
         ]); ?>
   </script>

<?php echo $data['codeheader']['value']; ?>
</head>
<body class="<?php if(Route::current()->getName()=='home') echo 'home'; ?> guest">
<div class="application--wrap">
<div class="wploading">
 <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
</div>

<header>
   <div class="container">
      <div class="row">
         <div class="col-sm-2 col-xs-2 col-xxs-2 collefthead">&nbsp;
            <a href="#" class="backbtn hidden action" data-nameaction="back"><i class="fa fa-angle-left"></i> </a>
         </div>
         <div class="col-sm-8 col-xs-8 col-xxs-8 colmidhead text-center">
            <h1>Massage</h1>
         </div>
         <div class="col-sm-2 col-xs-2 col-xxs-2 colrighthead text-right">
            <a href="#" class="searchbtn action" data-nameaction="searchcoso" data-id="searchcoso" data-title="Tìm Kiếm Cơ Sở"><i style="font-size: 17px;" class="fa fa-search"></i> </a>
            <a href="#" class="refreshbtn hidden action" data-nameaction="refresh"><i class="fa fa-refresh"></i> </a>
         </div>
      </div>
   </div>
</header>