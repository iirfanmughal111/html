@extends('frontend.layouts.master')
@section('pageTitle','Coaches')
@section('headtitle')
| Coaches
@endsection
@section('additionalcss')
	<link href="{{ url('frontend/css/star-rating-svg.css')}}" rel="stylesheet">
@stop
@section('content')
	@include('frontend.common.header')

	<div class="wrapper">
		@include('frontend.partials.account_top')
		@include('frontend.partials.guide_bar')

		<section class="coaches-grid">
			<div class="container">
			  <div class="row">
				  <div class="col-12">
					<h3 class="section-title text-center mb-3 mb-md-5">Games Coach</h3>
				  </div>
			  </div>
			</div>
			@if(isset($coaches) && count($coaches) > 0)
				<div class="container coaches-grid-container">
					@foreach($coaches as $key=>$coache)
						<div class="row align-items-xl-center justify-content-between">
							<div class="coaches-grid-thumbnail">
								<a href="{{'/coache-details'}}/{{$coache->hash}}">
									@if(isset($coache->userProfile) && !empty($coache->userProfile->coache_photo))
										<img class="img-fluid" src="{{ $coache->userProfile->coache_photo_url}}" alt="image">
									@else
										<img class="img-fluid" src="{{ url('frontend/images/jk8i88ey@2x.png')}}" alt="image">
									@endif
								</a>
							</div>
							<div class="coaches-grid-content">
							    <div class="icon-set"><img class="img-fluid" src="{{ url('frontend/images/video.svg')}}" alt="image"></div>
							    <h3 class="post-title">
								  	<a href="{{'/coache-details'}}/{{$coache->hash}}">Coach {{$coache->first_name}} </a>									
							   	</h3>
								<h4 class="c-name"><span>Name:</span> {{$coache->full_name}}</h4>	
							   	<h4 class="c-game"><span>Coaching:</span> {{$coache->tag_line}}</h4>
							   	<div class="post-content">
							   		@if(isset($coache->userProfile) && !empty($coache->userProfile->description))
							   			{!! $coache->userProfile->description !!}
							   		@endif
								  	@if(isset($coache->userProfile) && !empty($coache->userProfile->rating))
								  		<div class="index-coache-rating" data-rating="{{$coache->userProfile->rating}}"></div>
										{{--<span class="ratings">
											@for($i=1;$i<=$coache->userProfile->rating;$i++)
												<i class="fa fa-star" aria-hidden="true"></i>
											@endfor
										</span>--}}
									@endif   									
							   	</div>
							</div>	 
							
						</div>
					@endforeach	 		 	 
				</div>
			@endif
	  
	  {{--<div class="container">
		  <div class="row">
			  <div class="col-12 mt-3 mt-md-5 pt-0 pt-md-3">
				<a href="#/" class="btn view-more">View More Coaches</a>
			  </div>
		  </div>
	  </div>--}}	
	
   </section>
	</div>

	@include('frontend.common.footer')
@stop

@section('additionJs')
	<script src="{{ asset('frontend/js/jquery.star-rating-svg.js')}}"></script>
	<script src="{{ asset('frontend/js/module/coache-details.js')}}"></script>
@stop
