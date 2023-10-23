<footer>
   <ul>
      <li class="active"><a href="#" data-canSearch="1" data-title="Massage" class="tabfooter" data-tab="home"><i class="fa fa-home"></i><span>Home</span></a></li>
      <li><a href="#" data-title="Tìm Quanh Đây" class="tabfooter action" data-nameaction="getCurAddress" data-tab="map"><i class="fa fa-map-marker"></i><span>Quanh Đây</span></a></li>
      <li><a href="#" data-title="Tin Tức" class="tabfooter action" data-nameaction="getnews" data-tab="news"><i class="fa fa-newspaper-o"></i><span>Tin Tức</span></a></li>
      <li><a href="#" data-canRefesh="1" data-title="Vòng Quay May Mắn" class="tabfooter action requireLogin" data-requireLogin="1" data-nameaction="getvongquay" data-tab="spin"><i class="fa fa-dot-circle-o"></i><span>Vòng Quay</span></a></li>
      <li><a href="#" data-title="Tài Khoản" data-canRefesh="1" class="tabfooter" data-tab="account"><i class="fa fa-user"></i><span>Tài Khoản</span></a></li>
   </ul>
</footer>
<div class="clearfix"></div>
</div>

<script type="text/javascript" src="{{ URL('assets/guest/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ URL('/assets/guest/js/bootstrap.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{ URL('/assets/guest/js/sweetalert.min.js') }}"></script>
<script src="{{ URL('/assets/guest/js/bootstrap-rating.min.js') }}"></script>
<script src="{{ URL('/assets/js/jquery-qrcode-0.18.0.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0r4RTw3ZP0A2cU6mmh3dWjJSeD8hx3fg&libraries=places"></script> -->
<script type="text/javascript" src="{{ URL('assets/guest/v029/main.js?32') }}"></script>
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
   // mozilla/5.0 (macintosh; intel mac os x 10_15_7) applewebkit/537.36 (khtml, like gecko) chrome/112.0.0.0 safari/537.36
   // alert(navigator.userAgent.toLowerCase());
   if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('service-worker.js').then(() => {
         console.log('Service Worker Registered');
      });
   }

   let deferredPrompt;
   const addBtn = document.querySelector('.btnaddhome');
   window.addEventListener('beforeinstallprompt', (e) => {
      // Prevent Chrome 67 and earlier from automatically showing the prompt
      e.preventDefault();
      deferredPrompt = e;
      // addBtn.style.display = 'block';
   });
   
   function checkgup( name, url ) {
      if (!url) url = location.href;
      name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
      var regexS = "[\\?&]"+name+"=([^&#]*)";
      var regex = new RegExp( regexS );
      var results = regex.exec( url );
      return results == null ? null : results[1];
   }
   var checkgup = checkgup('app');
   let checkAddHome = getCookie('checkAddHome');
   if(!checkAddHome || checkgup){
      // if((os === 'android' || os === 'web' || checkgup) && os != 'ios' ){
      // alert(os +' - '+ getMobileOS());
      if(getMobileOS() == 'web' ){
         swal({
            title: "Cài App Camapro",
            text: "Bạn hãy cài đặt App Camapro đễ dễ dàng sử dụng hơn",
            icon: "warning",
            buttons: ["Bỏ Qua","Cài App Ngay"],
            dangerMode: true,
         }).then((willDelete) => {
            if (willDelete) {
               addBtn.style.display = 'none';
               deferredPrompt.prompt();
               deferredPrompt.userChoice.then((choiceResult) => {
                  if (choiceResult.outcome === 'accepted') {
                     console.log('User accepted the prompt');
                     setCookie('checkAddHome',1,500);
                  } else {
                     setCookie('checkAddHome',1,7);
                     console.log('User dismiœssed the prompt');
                  }
                  deferredPrompt = null;
               });
            } else {
               setCookie('checkAddHome',1,7);
            }
         });
      }
   }
</script>
{!! $data['codefooter']['value'] !!}
@yield("script")
</body>
</html>