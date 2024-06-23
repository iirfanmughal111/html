@extends('frontend.layouts.master')
@section('headtitle')
| Chat
@endsection
@section('chatcss')
	<link href="{{ url('frontend/css/chat.css')}}" rel="stylesheet">		
@stop
@section('content')
	@include('frontend.common.header')

	<div class="wrapper">
    	@include('frontend.partials.account_top')
    	@include('frontend.partials.guide_bar')
	
		<div class="main-container" id="app">
			<div class="container">
				<chat-component  :user="{{ Auth::user()->id }}"></chat-component>
			</div>
		</div>
	</div>
	@include('frontend.common.footer')
@stop

@section('appJs')
	<script src="{{ url('js/app.js')}}"></script>		
@stop

	  