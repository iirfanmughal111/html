@extends('frontend.layouts.master')
@section('headtitle')
| Chat
@endsection

@section('content')
	@include('frontend.common.header')

///<style>
	//.eapps-link{
	
		//display:none !important;
	//}
//</style>
	<div class="wrapper">
    	@include('frontend.partials.account_top')
    	@include('frontend.partials.guide_bar')

	    <div class="container pt-5">
<script src="https://apps.elfsight.com/p/platform.js" defer></script>
<div class="elfsight-app-b2a5f636-bcbf-47c3-baba-930208af65e5"></div>
        
	</div>
<script>

  //$(document).ready(function(){
	 
	//$('a.eapps-link').removeAttr('style');
//});
//</script>
	
	@include('frontend.common.footer')
@stop



	  