<form method="post" action="{{ URL::Route('admin.setting.postSaveslider') }}" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="_method" value="POST">
<button href="" class="btn btn-primary btn-sm mb-10px"><i class="fa fa-save"></i> Lưu</button>
<?php
$y=1;
for($i=0;$i<=5;$i++){
    if(!isset($data['slider'][$i])){
        $data['slider'][$i] = new \stdClass();
        $data['slider'][$i]->img='';
        $data['slider'][$i]->url='';
    }
?>
<div class="form-row">
    <div class="form-group col-md-4 col-sm-8">
        <label for="sl{{$y}}">Slider {{$y}}</label>
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control groupimgselect" rel="imgsl{{$y}}" id="sl{{$y}}" value="{{$data['slider'][$i]->img }}" name="slider[{{$i}}][img]">
                <span class="input-group-btn"> <button data-inputid="sl{{$y}}" class="popup_selector btn btn-primary" type="button"><i class="fa fa-upload"></i></button> </span>
            </div>
        </div>
    </div>
    <div class="form-group col-md-2 col-sm-4">
        <img id="imgsl{{$y}}" class="maxw80px" src="{{ $data['slider'][$i]->img!='' ? $data['slider'][$i]->img : URL('/images/no-image.jpg') }}" alt="slider {{$y}}">
    </div>
    <div class="form-group col-md-6 col-sm-12">
        <label for="link{{$y}}">Link Slider {{$y}}</label>
        <input class="form-control" id="link{{$y}}" type="text" value="{{$data['slider'][$i]->url}}" name="slider[{{$i}}][url]">
    </div>
</div>
<?php $y++; } ?>
<button href="" class="btn btn-primary btn-sm mb-10px"><i class="fa fa-save"></i> Lưu</button>
</form>