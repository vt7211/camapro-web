<?php
if($cat->description!=''){
    $des = $cat->description;
}else{
    $des = $data['description']['value'];
}
?>
@extends('guest.index')
@section("description",$des)
@section("title",$cat->name)
@section("css")
<link rel="stylesheet" href="{{ URL('assets/guest/css/owl.carousel.min.css') }}">
<style>

</style>
@endsection()
@section("breadcrumb")

@endsection()
@section("content")

<div class="container category">
    <div class="row">
        <div class="col-sm-9 col-mm-8">
            <div class="relative">
                <h1 class="title-home">{{$cat->name}}</h1>
            </div>
            @if($cat->content!='')
                <div class="content">{!! $cat->content !!}</div>
            @endif
                <div class="row row2">
                <?php $i = 1; ?>
                    @foreach($news as $n)
                        @if($i==1)
                            <div class="cat1 catnews">
                            <div class="col-sm-6 col-mm-6">
                            @include("guest.loop.news-lg-nocol")
                        @elseif($i==2)
                            </div>
                            <div class="col-sm-6 col-mm6">
                            @include("guest.loop.news-xs-nocol")
                        @elseif($i==6)
                            @include("guest.loop.news-xs-nocol")
                            </div>
                            <div class="clearfix"></div>
                            </div>
                        @elseif($i< 6)
                            @include("guest.loop.news-xs-nocol")
                        @else
                            @include("guest.loop.contentnews")
                        @endif
                        <?php $i++; ?>
                    @endforeach
                    <?php if($i ==2 ) echo '</div></div>'; ?>
                    <?php if($i >=3 && $i < 6 ) echo '</div>'; ?>
            </div>

            {{ $news->appends(request()->except('page'))->links() }}
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
