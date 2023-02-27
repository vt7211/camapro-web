<div class="news news-full col-xxs-12">
    <a href="{{URL::route('slug',$n->alias)}}">
        <img alt="{{$n->name}}" src="{{$n->image!='' ? get_image_url($n->image,'thumb') : URL('asset/guest/images/300x400.jpg') }}">
        <h3 class="titlenews">{{$n->name}}</h3>
    </a>
    <?php
    $txt = $n->intro;
    if($txt=="") $txt= $n->content;
    $txt = str_limit_html($txt, 250);
    ?>
    <div class="ex"><span>{!! $txt !!}</span></div>
</div>