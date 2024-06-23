<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ showSiteTitle('title') }} @yield('headtitle')</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="{{ url('frontend/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ url('frontend/css/fontawesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ url('frontend/css/style.css?v=').time() }}" rel="stylesheet">
    <link href="{{ url('frontend/css/hover-min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ url('frontend/css/custom.css') }}" />
	<link rel="stylesheet" href="{{ url('frontend/css/vendor/notifications.css')}}"/>
	<link href="{{ url('frontend/css/bootstrap-select.min.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="{{ url('frontend/css/jquery.fancybox.min.css')}}">
  @yield('additionalcss')
	@yield('chatcss')
	<script> 
	 base_url ="{{ url('/') }}";
	</script> 
  </head>
 
  <body>

    @yield('content')
    @yield('appJs')
    {{--<script src="{{ url('js/app.js')}}"></script>--}}
   	<script src="{{ url('frontend/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{ url('frontend/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ url('frontend/js/vendor/notifications.js')}}"></script>
    <script src="{{ url('frontend/js/common.js')}}"></script>
    <script type="text/javascript" src="{{ url('frontend/js/jquery.fancybox.min.js')}}"></script>
	{{-- <script src="{{ url('frontend/js/jquery.js')}}"></script>
    <script src="{{ url('frontend/js/bootstrap.min.js')}}"></script>
    <script src="{{ url('frontend/js/bootstrap-select.min.js')}}"></script> --}}

	
<!---   additional js for page -->
@yield('additionJs')
   
  </body>
</html>