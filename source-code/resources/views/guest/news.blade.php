<?php
if(isset($news->description) && $news->description !=''){
    $des = $news->description;
}else{
    $des = $data['description']['value'];
}
?>
@extends('guest.index')
@section("description",$des)
@section("title",$news->name)
@section("css")
<link rel="stylesheet" href="{{ URL('assets/guest/css/owl.carousel.min.css') }}">
<style>

</style>
@endsection()
@section("breadcrumb")

@endsection()
@section("content")

<div class="container wpcatsp">
    <div class="row">
        <div class="col-sm-9 col-mm-8">
            <div class="content">
            <h1 class="title-single">{{$news->name}}</h1>
            {!! $news->content !!}
            </div>
        </div>
        @include('guest.sidebar')
    </div>
</div>

@endsection()
@section("script")
<script type="text/javascript" src="{{ URL('assets/guest/js/owl.carousel.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function(){
    $(window).scroll(function(){
        var top = $(this).scrollTop();
        var $this = $("#scrollbn");
        var pos = $this.parent().offset().top - 10;
        var height = $this.outerHeight(true);

        var limit= $('footer').offset().top - height - 20;
        var windowsize = $(window).width();

        //console.log('top: '+top+' pos: '+pos+' limit: '+limit+' height: '+height+' windowsize: '+windowsize);
        if(windowsize <768) return true;
        var maxwidth = $this.parent().width();
        $this.css({
            'max-width': maxwidth,
        });
        if((pos+height)>=limit){
            $this.css({
                position: 'static',
                top: 0
            });
        }else if (top >= limit) {
            $this.css({
                position: 'absolute',
                top: limit
            });
        }else if(top <= pos){
            $this.css({
                position: 'static',
                top: 0
            });
        }else if(top >= pos && top <= limit){
            $this.css({
                position: 'fixed',
                top: 10
            });
        }
    });

});
</script>
@endsection()
