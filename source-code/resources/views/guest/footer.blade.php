<footer>
   <ul>
      <li class="active"><a href="#" data-canSearch="1" data-title="Massage" class="tabfooter" data-tab="home">
         <i class="fa fa-home"></i>
         <span>Home</span></a>
      </li>
      <li><a href="#" data-title="Tìm Quanh Đây" class="tabfooter action" data-nameaction="getCurAddress" data-tab="map">
         <i class="fa fa-map-marker"></i>
         <span>Quanh Đây</span></a>
      </li>
      <li><a href="#" data-title="Tin Tức" class="tabfooter action" data-nameaction="getnews" data-tab="news">
         <i class="fa fa-newspaper-o"></i>
         <span>Tin Tức</span></a>
      </li>
      <li><a href="#" data-canRefesh="0" data-title="Games" class="tabfooter action requireLogin" data-requireLogin="1" data-nameaction="listgame" data-tab="spin">
         <i class="fa fa-gamepad"></i>
         <span>Game</span></a>
      </li>
      <li><a href="#" data-title="Tài Khoản" data-canRefesh="1" data-header="2" class="tabfooter" data-tab="account">
         <i class="fa fa-user"></i>
         <span>Tài Khoản</span></a>
      </li>
   </ul>
</footer>
<div class="alertCode">
   <div class="img" style="background-image: url(/images/no-image.jpg)" alt=""></div>
   <div class="bodyalert">
      <div class="nameCSAlert">***</div>
      <div class="userAlert">
         <span class="nameUsserAlert">***</span>&nbsp;
         <span class="phoneUsserAlert">09*****992</span>
      </div>
      <div class="wpTimeAlert">Đã lấy code cách đây <span class="timeAlert">10 giây</span></div>
   </div>
</div>
<div class="clearfix"></div>
</div>

<script type="text/javascript" src="{{ URL('assets/guest/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ URL('/assets/guest/js/bootstrap.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{ URL('/assets/guest/js/sweetalert.min.js') }}"></script>
<script src="{{ URL('/assets/guest/js/bootstrap-rating.min.js') }}"></script>
<script src="{{ URL('/assets/js/jquery-qrcode-0.18.0.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ URL('/assets/guest/js/jquery.countdown.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0r4RTw3ZP0A2cU6mmh3dWjJSeD8hx3fg&libraries=places"></script> -->
<script type="text/javascript" src="{{ URL('v029/main.js?70') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLXFrtV2RCrwb_O8ZQgWdEdAL5w_ERJ3w"></script>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" defer></script>
<script>
   window.OneSignal = window.OneSignal || [];
   OneSignal.push(function() {
      OneSignal.init({
         appId: "da2b04f0-56f4-4e4c-ab42-7544f4152071",
      });
   });
</script>

<script type="text/javascript">
   function checkgup( name, url ) {
      if (!url) url = location.href;
      name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
      var regexS = "[\\?&]"+name+"=([^&#]*)";
      var regex = new RegExp( regexS );
      var results = regex.exec( url );
      return results == null ? null : results[1];
   }
</script>
{!! $data['codefooter']['value'] !!}
@yield("script")
</body>
</html>