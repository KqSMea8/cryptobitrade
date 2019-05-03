<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ config('app.name', 'Solidus Gold') }}</title>
    @show @section('meta_description')
    <meta name="description" content="@lang('site.site_description_value')"/>@show 
    @show @section('og_title')
    <meta property="og:title" content="">@show 
    @show @section('og_type')
    <meta property="og:type" content="website">@show 
    @show @section('og_url')
    <meta property="og:url" content="">@show 
    @show @section('og_sitename')
    <meta property="og:site_name" content="">@show 
    @show @section('og_description')
    <meta property="og:description" content="">@show 
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link id="favicon" rel="shortcut icon" href="{{ url('img/cache/logo/'.$logo_ico)}}" type="image/png" />
    <link href="{{ asset('site/css/stack-interface.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('site/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('site/css/stack-interface.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('site/css/theme.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('site/css/custom.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:200,300,400,400i,500,600,700|Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet"/>
</head>
 <body data-smooth-scroll-offset="77">

  
@include('site.partials.header')
@yield('content')
@include('site.partials.footer')
@yield('topscripts')
<!-- Scripts -->
<!-- <script src="{{ asset('js/app.js') }}"></script> -->
<script src="{{ asset('site/js/jquery-3.1.1.min.js') }} "></script>
<script src="{{ asset('site/js/parallax.js') }} "></script>
<script src="{{ asset('site/js/smooth-scroll.min.js') }} "></script>
<script src="{{ asset('site/js/scripts.js') }} "></script>
@yield('scripts')
@if (isset($errors) && !$errors->isEmpty())
<script type="text/javascript">
swal("","@foreach ($errors->all() as $error){{ $error }}@endforeach","error");
</script>
@endif
@if (session()->has('flash_notification.message'))
  @if (session()->has('flash_notification.overlay'))
      <script type="text/javascript">
       swal("{!! Session::get('flash_notification.title') !!}","{!! Session::get('flash_notification.message') !!}","{!! Session::get('flash_notification.level') !!}");
      </script>
  @else
      <script type="text/javascript">
       swal("{!! session('flash_notification.level') !!}"," {!! session('flash_notification.message') !!}","{!! session('flash_notification.level') !!}");
      </script>
  @endif
@endif
</body>
</html>
