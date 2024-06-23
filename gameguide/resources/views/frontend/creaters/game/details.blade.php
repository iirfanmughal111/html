@extends('frontend.layouts.master')
@section('pageTitle','Game Guides')
@section('headtitle')
| Game Details
@endsection
@section('content')
  @include('frontend.common.header')

  <div class="wrapper">
    @include('frontend.partials.account_top')
    @include('frontend.partials.guide_bar')
    @if(isset($game))
      <section class="gamers-details-sec">
        <div class="container">
		 <div class="row">
      			<div class="col-md-12 game-details-inner">
      			    <h3 class="section-title mb-3">{{$game->title}}</h3>
						{!! $game->description !!}
        				{{--<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</p>
        				<p><img class="img-fluid" src="{{ url('frontend/images/7je6to51@2x.png')}}"></p>
        				<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</p>
                <ul class="list-unstyled icon-list">
                <li>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam</li> 
                <li>voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</li>  
                <li>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</li>
                </ul>				
        				<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</p>
        				<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</p>--}}
      			</div>
      		</div>
          @if(isset($game->gameGuide) && count($game->gameGuide) > 0)
        	 @include('frontend.creaters.game.game_guide')
          @endif		 
  		   
        </div>
     </section>
    @endif
  </div>

  @include('frontend.common.footer')
@stop