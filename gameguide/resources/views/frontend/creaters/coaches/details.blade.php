@extends('frontend.layouts.master')
@section('pageTitle','Coache Guides')
@section('headtitle')
| Coaches Details
@endsection
@section('additionalcss')
	<link href="{{ url('frontend/css/star-rating-svg.css')}}" rel="stylesheet">
@stop
@section('content')
  @include('frontend.common.header')

  	@if(isset($coache))
  	<div class="wrapper">
	    @include('frontend.partials.coache_account_top')
	    @include('frontend.partials.guide_bar')
	    
	    <section class="coaches-prfile-grid">
	      	<div class="container">
	         	<div class="row">
					<div class="col-12">
					
					
						@if(isset($coache->userProfile) && !empty($coache->userProfile->description))
				   			{!! $coache->userProfile->description !!}
				   		@endif
					</div>	
					@if(isset($coache->coacheRating) && count($coache->coacheRating) > 0)	
					<div class="_coach_name">
						<span>{{$coache->full_name}} Reviews</span>
					</div>
					<div class="col-12"><hr> </div>
					<div class="col-12">
						@foreach($coache->coacheRating as $key=>$coacheRate)
							@include('frontend.creaters.coaches.coache_rating')
						@endforeach
					</div>
					@endif			
				
	         </div>		 
			 
			 
	      </div>
	      @include('frontend.partials.modal.starRatingModal')
	   </section>
	

  	</div>
  	@endif

    @include('frontend.common.footer')
@stop

@section('additionJs')
	<script src="{{ asset('frontend/js/jquery.star-rating-svg.js')}}"></script>
	<script src="{{ asset('frontend/js/module/coache-details.js')}}"></script>
@stop