<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>@yield("title")</title>
      <meta name="description" content="Admin dashboard build by www.netsa.vn">
      <meta name="author" content="Development by Van Tan - https://netsa.vn">
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ URL('assets/images/ico.png') }}">
      <!-- Bootstrap CSS -->
      <link href="{{ URL('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
      <!-- Font Awesome CSS -->
      <link href="{{ URL('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
      <!-- Custom CSS -->
      <link href="{{ URL('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
      <!-- BEGIN CSS for this page -->
      @yield("css")
      <!-- END CSS for this page -->
      <script>
         window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            ]); ?>
      </script>
   </head>
   <body class="adminbody">
      <div id="main">