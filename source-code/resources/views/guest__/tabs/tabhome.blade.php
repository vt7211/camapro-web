<div class="slider owl-theme owl-carousel">
    @foreach($data['settings']['slider'] as $item)
        <img src="{{$item}}" alt="">
    @endforeach
</div>
@if(count($data['data']['cosohot']) > 0)
    <h3 class="title-home col-md-12">Cơ Sở Hot</h3>
    @foreach($data['data']['cosohot'] as $item)
        @include('guest.inc.coso')
    @endforeach
@endif
@if(count($data['data']['cosos']) > 0)
    <h3 class="title-home col-md-12">Cơ Sở Đáng Đi</h3>
    @foreach($data['data']['cosos'] as $item)
        @include('guest.inc.coso')
    @endforeach
@endif