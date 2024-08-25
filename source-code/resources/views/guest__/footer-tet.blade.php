<footer class="tet">
   <ul>
      <li class="active"><a href="#" data-canSearch="1" data-title="Massage" class="tabfooter" data-tab="home">
         <img src="/tet/001-new-year.png" alt="home">
         <span>Home</span></a>
      </li>
      <li><a href="#" data-title="Tìm Quanh Đây" class="tabfooter action" data-nameaction="getCurAddress" data-tab="map">
         <img src="/tet/005-location.png" alt="địa điểm">
         <span>Quanh Đây</span></a>
      </li>
      <li><a href="#" data-title="Tin Tức" class="tabfooter action" data-nameaction="getnews" data-tab="news">
         <img src="/tet/007-news.png" alt="tin tức">
         <span>Tin Tức</span></a>
      </li>
      <li><a href="#" data-canRefesh="1" data-title="Vòng Quay May Mắn" class="tabfooter action requireLogin" data-requireLogin="1" data-nameaction="getvongquay" data-tab="spin">
         <img src="/tet/002-new.png" alt="vòng quay">
         <span>Vòng Quay</span></a>
      </li>
      <li><a href="#" data-title="Tài Khoản" data-canRefesh="1" class="tabfooter" data-tab="account">
         <img src="/tet/004-god-of-wealth.png" alt="tài khoản">
         <span>Tài Khoản</span></a>
      </li>
   </ul>
</footer>
<div class="clearfix"></div>
</div>
<img class='tet tet-left' src='/tet/canh-mai-ben-trai.png' style='width:160px;'/>
<img class='tet tet-right' src='/tet/canh-mai-ben-phai.png' style='width:160px;'/>

<script type="text/javascript" src="{{ URL('assets/guest/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ URL('/assets/guest/js/bootstrap.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{ URL('/assets/guest/js/sweetalert.min.js') }}"></script>
<script src="{{ URL('/assets/guest/js/bootstrap-rating.min.js') }}"></script>
<script src="{{ URL('/assets/js/jquery-qrcode-0.18.0.min.js') }}"></script>
<script src="{{ URL('/assets/guest/js/jquery.countdown.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0r4RTw3ZP0A2cU6mmh3dWjJSeD8hx3fg&libraries=places"></script> -->
<script type="text/javascript" src="{{ URL('v029/main.js?56') }}"></script>
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