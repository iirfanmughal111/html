<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ showSiteTitle('title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('font/iconsmind-s/css/iconsminds.css')}}" />
    <link rel="stylesheet" href="{{ url('font/simple-line-icons/css/simple-line-icons.css') }}" />
	<link rel="stylesheet" href="{{ url('css/vendor/dataTables.bootstrap4.min.css')}}" />
    <link rel="stylesheet" href="{{ url('css/vendor/datatables.responsive.bootstrap4.min.css')}}" />
    <link rel="stylesheet" href="{{ url('css/vendor/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ url('css/vendor/bootstrap-datepicker3.min.css')}}" />
    <link rel="stylesheet" href="{{ url('css/vendor/bootstrap-float-label.min.css') }}" />
	<link rel="stylesheet" href="{{ url('css/dore.light.blue.min.css') }}" />
    <link rel="stylesheet" href="{{ url('css/vendor/dropzone.min.css')}}" />
    <link rel="stylesheet" href="{{ url('css/main.css') }}" />
    <link rel="stylesheet" href="{{ url('css/custom.css') }}" />
    @yield('additionalCss')
	{{--<link rel="shortcut icon" href="{{ asset('img/favicon.png')}}">--}}
	<script> 
		base_url ="{{ url('/') }}";
	</script> 
</head>
<body class="background show-spinner no-footer">
     <div class="fixed-background"></div>
       <main>
        <div class="container login">
            @yield("content")
        </div>
	  </main>
	  
	  
	  
	<script src="{{ url('js/vendor/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ url('js/vendor/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ url('js/vendor/bootstrap-datepicker.js')}}"></script>
    <script src="{{ url('js/dore.script.js') }}"></script>
    <script src="{{ url('js/vendor/dropzone.min.js')}}"></script>
    <script src="{{ url('js/scripts.single.theme.js') }}"></script>
	@yield('strpieJs')
    <!---Add additionalJs-->
    @yield('additionalJs')
</body>

</html>