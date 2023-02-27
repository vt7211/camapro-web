
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

<script type="text/javascript" src="{{ URL('/assets/guest/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL('assets/guest/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ URL('/assets/guest/js/bootstrap.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{ URL('/assets/guest/js/sweetalert.min.js') }}"></script>
<script src="{{ URL('/assets/guest/js/bootstrap-rating.min.js') }}"></script>
<script src="{{ URL('/assets/js/jquery-qrcode-0.18.0.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0r4RTw3ZP0A2cU6mmh3dWjJSeD8hx3fg&libraries=places"></script> -->
<script type="text/javascript" src="{{ URL('assets/guest/v015/main.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLXFrtV2RCrwb_O8ZQgWdEdAL5w_ERJ3w"></script>

{!! $data['codefooter']['value'] !!}
@yield("script")
</body>
</html>