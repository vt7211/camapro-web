<div class="news news-xs">
    <a href="{{URL::route('slug',$n->alias)}}">
        <img alt="{{$n->name}}" src="{{$n->image!='' ? get_image_url($n->image,'featured') : URL('assets/guest/images/no-image.jpg') }}">
        <h3 class="titlenews">{{$n->name}}</h3>
    </a>
    <div class="clearfix"></div>
</div>