<meta name="csrf-token" content="{{ csrf_token() }}">

@foreach(config('media.libraries.stylesheets', []) as $css)
    <link href="{{ url($css) }}" rel="stylesheet" type="text/css"/>
@endforeach
@if(Route::current()->getName()=='media.index')
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@endif
@include('media::config')
