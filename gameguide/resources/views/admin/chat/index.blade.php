@extends('admin.layouts.admin')
@section('headtitle')
| Chat
@endsection
@section('additionalCss')
	<link href="{{ url('frontend/css/chat.css')}}" rel="stylesheet">		
@stop
@section('content')
	<div class="row">
		<div class="col-12">
			@if(isset($user))
				<h1>{{$user->full_name}} Chat </h1>
			@else
				<h1>Chat </h1>
			@endif
			<div class="separator mb-5"></div>
		</div>
	</div>
	
		<div class="main-container" id="app">
			<div class="container">
				<chat-component  :user="{{ Auth::user()->id }}"></chat-component>
			</div>
		</div>
@stop

@section('appJs')
	<script src="{{ url('js/app.js')}}"></script>		
@stop

	  