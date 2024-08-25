<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, target-densityDpi=device-dpi, minimal-ui' /> -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>@yield("title")</title>
   <meta name="description" content="@yield('description')">
   <meta name="keywords" content="@yield('keywords')">
   <meta name="author" content="Development by Van Tan">
   <meta property="og:image" content="{{ URL('/images/banner-camapro.jpg') }}" />
   <meta property="og:url" content="<?php echo curPageURL(); ?>" />
   <meta property="og:title" content="@yield('title')" />
   <meta property="og:description" content="@yield('description')" />
   <meta property="og:type" content="article" />
   <meta property="og:image:alt" content="Camapro Săn vé Massage Miễn Phí" />
   <meta name="theme-color" content="#a01503" >
   <meta name="domainchat" content="{{str_replace('/api','',env('DOMAIN_API'))}}" >

   <link rel="manifest" href="/manifest.json">     
   <meta name="msapplication-TileColor" content="#ffffff">
   <meta name="msapplication-TileImage" content="/logos/icon_144.png">
   <meta name="theme-color" content="#a01503">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <meta name="apple-mobile-web-app-status-bar-style" content="green">
   <meta name="apple-mobile-web-app-title" content="FreeCodeCamp">
   
   <link rel="icon shortcut" href="/logos/icon_32.png" sizes="32x32" />
   <link rel="apple-touch-icon" href="/logos/icon_72.png" sizes="72x72">
   <link rel="apple-touch-icon" href="/logos/icon_96.png" sizes="96x96">
   <link rel="apple-touch-icon" href="/logos/icon_128.png" sizes="128x128">
   <link rel="apple-touch-icon" href="/logos/icon_144.png" sizes="144x144">
   <link rel="apple-touch-icon" href="/logos/icon_152.png" sizes="152x152">
   <link rel="apple-touch-icon" href="/logos/icon_192.png" sizes="192x192">
   <link rel="apple-touch-icon" href="/logos/icon_384.png" sizes="384x384">
   <link rel="apple-touch-icon" href="/logos/icon_512.png" sizes="512x512">
   <!-- Favicon -->
   <link rel="shortcut icon" href="{{ URL('/images/logo.jpg') }}">
   <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700&display=swap" rel="stylesheet"> 
   <link href="{{ URL('assets/guest/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link href="{{ URL('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

   <link href="{{ URL('assets/guest/css/owl.theme.default.css') }}" rel="stylesheet" type="text/css" />
   <link href="{{ URL('assets/guest/css/owl.carousel.min.css') }}" rel="stylesheet" type="text/css" />
   <link href="{{ URL('assets/guest/css/bootstrap-rating.css') }}" rel="stylesheet" type="text/css" />
   <link href="{{ URL('v029/guest.css?64') }}" rel="stylesheet" type="text/css" />
   <link href="{{ URL('tet/tet.css?5') }}" rel="stylesheet" type="text/css" />
   <!-- BEGIN CSS for this page -->
   @yield("css")
   <!-- END CSS for this page -->
   
   <script type="text/javascript" src="{{ URL('/assets/guest/js/jquery.min.js') }}"></script>
   <!-- <script src="{{ URL('/assets/guest/js/js.cookie.js') }}"></script> -->
   <script>
      window.Laravel = <?php echo json_encode([
         'csrfToken' => csrf_token(),
      ]); ?>
   </script>
   <script>
      // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
      let vh = window.innerHeight * 0.01;
      let suyncdata = false;
      // Then we set the value in the --vh custom property to the root of the document
      document.documentElement.style.setProperty('--vh', `${vh}px`);
      // We listen to the resize event
      window.addEventListener('resize', () => {
         // We execute the same script as before
         let vh = window.innerHeight * 0.01;
         console.log('vh', vh);
         document.documentElement.style.setProperty('--vh', `${vh}px`);
         $('.fulliframe .tabcontent.active, .fulliframe .tabchild.active').height(window.innerHeight);
      });

      function sendDataToReactNativeApp() {
         if(window.ReactNativeWebView) {
            window.ReactNativeWebView.postMessage(JSON.stringify({type: 'getdevice'}));
         }
      }
      function setCookie(name,value,days) {
         var expires = "";
         if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
         }
         document.cookie = name + "=" + (value || "")  + expires + "; path=/";
         // alert('setCookie idDevice: ' + value);
      }
      function getCookie(name) {
         var nameEQ = name + "=";
         var ca = document.cookie.split(';');
         for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
         }
         return null;
      }
      function eraseCookie(name) {   
         document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      }
      function getMessage(message){
         if(message.data){ 
            let datareceived = message.data && IsJsonString(message.data)? JSON.parse(message.data) : null;
            if(typeof datareceived === 'object' && !Array.isArray(datareceived) && datareceived !== null){
               let type = datareceived.type ? datareceived.type : 'action';
               let value = datareceived.value ? datareceived.value : null;
               if(type === 'alert' && value) alert(value);
               else if (type === 'getdevice' && value) {
                  alert('getdevice: ' + message.data);
                  idDevice = value;
                  doAction(null, {nameaction: 'save_device', device: datavalue.idDevice, token: datavalue.token});
                  // setCookie('idDevice',idDevice,365);
                  // alert('idDevice: ' + value);
               } else if (type === 'nof' && value) {
                  alert('nof: ' + message.data);
                  let motinnof = value.motinnof;
                  if(motinnof) {
                     let typemotin = value.typemotin;
                     if(typemotin == 'coso'){

                     } else if(typemotin == 'news'){

                     }
                  }
               } else if (type === 'suyncdata' && value) {
                  if(!suyncdata) doAction(null, {nameaction: 'save_device', device: value.idDevice, token: value.token, hasNotch: value.hasNotch, os: value.os});
                  suyncdata = true;
                  // alert('suyncdata: '+ value.token);
               } else if (type === 'newtab' && value) {
                  // console.log('newtab', value);
                  window.open(value.url, '_blank').focus();
               } else if (type === 'loadone' && value) {
                  loading.value = false;
               } else if (type === 'kbopen') {
                  if(value) $('body').addClass('kbopen');
                  else $('body').removeClass('kbopen');
               } else if (type === 'back') {
                  doAction(null, {
                     nameaction: 'back',
                     id: 5,
                  });
               }
            }
         }
      }
      // sendDataToReactNativeApp();
      if (navigator.appVersion.includes('Android') && navigator.userAgent.includes('CAMAPRO_APP')) {
         document.addEventListener("message", function (message) { // android
            getMessage(message);
         });
      } else {
         window.addEventListener("message", function (message) { // iOS
            getMessage(message);
         });
      }
      
      // cr
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