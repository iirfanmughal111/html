@extends('frontend.layouts.master')
@section('headtitle')
| Tournament
@endsection
@section('content')
  @include('frontend.common.header')

	<div class="wrapper">
		@include('frontend.partials.account_top')
		@include('frontend.partials.guide_bar')

		<section class="guide-grid">
			<div class="container">
			 <div class="row">
				<div class="col-md-12">
					<h3 class="section-title">Tournament</h3>
				</div>
			 </div>
			 <div class="row">
			 	@if(isset($tournaments) && count($tournaments) > 0)
			 		@foreach($tournaments as $tournament)
			 			@php $anchorLink = "javascript:void(0);"; @endphp
			 			@if(isset($tournament->link) && !empty($tournament->link))
			 				@php $anchorLink = trim($tournament->link); @endphp
			 			@endif
			 			<div class="w-xl-20 col-lg-4 col-md-6">
					       	<div class="grid-box">
								<div class="grid-image">
									<a href="{{$anchorLink}}" class="d-block t-box" target="_blank"><img class="img-fluid image" src="{{ $tournament->image_url}}" alt="image">
										<div class="middle">
										   <div class="text">View More</div>
										</div>
								 	</a>

								</div>
								<div class="grid-content">
									<h4><a href="{{$anchorLink}}" target="_blank">{{$tournament->title}}</a></h4>
								</div>
					       	</div>
					    </div>

			 		@endforeach
			 	@endif
			 </div>		 
			 
			</div>
   		</section>
	</div>

	@include('frontend.common.footer')
@stop
