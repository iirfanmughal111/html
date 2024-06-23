@extends('frontend.layouts.master')
@section('headtitle')
| Guide Details
@endsection
@section('content')
  @include('frontend.common.header')
  @include('frontend.partials.account_top')
  @include('frontend.partials.guide_bar')

  	@if(isset($gameGuide) && !empty($gameGuide))
	  	<div class="wrapper">
	  		@if(isset($gameGuide->embed_video) && !empty($gameGuide->embed_video))
		   		<section class="csgo-one-wb-sec">
					<div class="container">		 
						<div class="row">
							<div class="col-md-12">
								<div class="embed-responsive embed-responsive-16by9">
									@php
										$pattern = '/<iframe/i';
									@endphp
									@if(preg_match($pattern, $gameGuide->embed_video))
										{{$gameGuide->embed_video}}
									@else
										<iframe width="420" height="315" src="{{$gameGuide->embed_video}}" allowfullscreen></iframe>
									@endif
									{{--<iframe width="420" height="315" src="https://www.youtube.com/embed/tgbNymZ7vqY?controls=0" allowfullscreen></iframe>--}}
									{{--<iframe class="embed-responsive-item" src="{{$gameGuide->embed_video}}?controls=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
								</div>
							</div>			
						</div>
					</div>	  
		 		</section>
		 	@endif 


	   		<section class="csgo-one-wb-sec">
				<div class="container">		 
					<div class="row">
						<div class="col-lg-8">
							<div class="row">	

								<div class="col-md-12">
								    <h3 class="section-title mb-3 mb-3">{{$gameGuide->title ?? ''}}</h3>
									<p>{!! $gameGuide->short_description !!}</p>
								</div>
								
								<div class="col-md-12">
									<nav class="mb-4">
										<div class="nav nav-tabs" id="nav-tab" role="tablist">
											<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Notes</a>
											<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Transcript</a>
										</div>
									</nav>
									<div class="tab-content" id="nav-tabContent">
										<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
											<div class="video-notes w-richtext">
												{!! $gameGuide->description !!}
											</div>

										</div>
										<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">


										<div class="video-transcript w-richtext">
											@if(isset($gameGuide->gameGuidetranscript) && count($gameGuide->gameGuidetranscript) > 0)
												<ul role="list">
													@foreach($gameGuide->gameGuidetranscript as $transcript)
														<li>
															<strong>{{$transcript->duration ?? ''}}</strong> - {!! $transcript->content !!}
														</li>
													@endforeach
												</ul>
											@endif
										</div>
										</div>
									</div>
								</div>
							</div>		
						</div>

						@if(isset($gameGuide->gameGuideKey) && count($gameGuide->gameGuideKey) > 0)
						<div class="col-lg-4">
							<div class="sticky">
								<div class="card-wrapper margin-bottom-large">
									<h3 class="card-wrapper-title">Key takeaways</h3>
									<div class="key-takeaways w-richtext">
										<ul role="list">
											@foreach($gameGuide->gameGuideKey as $guidekey)
												@if(!empty($guidekey))
													<li>{!! $guidekey->content !!}</li>
												@endif
											@endforeach
										</ul>
									</div>
								</div>
							</div>
						</div>
						@endif	
					</div>
				</div>
			</section>
		</div>
	@endif

  @include('frontend.common.footer')
@stop
    