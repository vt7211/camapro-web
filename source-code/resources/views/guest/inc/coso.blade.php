<div class="col-md-12">
    <div class="coso">
        <a href="#" class="action" data-name="detailcoso">
            <div class="img" style="background-image: url({{$item['image']}})"></div>
            <h3 class="name">{{$item['name']}}</h3>
            <div class="star">
                <input type="hidden" data-filled="fa fa-star" data-empty="fa fa-star-o" class="rating" disabled="disabled" value="{{$item['star']}}"/>
            </div>
            <div class="price">
                <del>{{number_format($item['giatruockm'])}}</del>
                <ins>{{number_format($item['giachinhthuc'])}}</ins>
            </div>
            <div class="diachi mb-10px">{{$item['diachi']}}</div>
        </a>
        @if($item['getcode'])
        <a href="#" class="btn btn-primary btn-block action" data-name="getcode">
            <span>Lấy Mã Khuyến Mãi</span>
        </a>
        @else
        <a href="tel:{{ $item['phone'] }}" class="btn btn-primary btn-block">
            <span>{{ $item['phone'] }}</span>
        </a>
        @endif
        <a href="#" class="like action" data-name="like">
            <i class="fa fa-heart-o"></i>
            <p>{{$item['solike'] + $item['likecongthem']}}</p>
        </a>
        <div class="clearfix"></div>
    </div>
</div>