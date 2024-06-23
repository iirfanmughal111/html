@extends('frontend.layouts.master')
@section('pageTitle','Game Guides')
@section('headtitle')
| Game Guides
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
				<h3 class="section-title">Game Guides</h3>
			</div>
		 </div>
         <div class="row">
            @php $limitText = 60; @endphp
            @if(isset($games) && count($games)>0)
               @foreach($games as $game)
                  <div class="w-xl-20 col-lg-4 col-md-6">
                     <div class="grid-box">
                        <div class="grid-image">
                           <a href="{{url('game-guide')}}/{{$game->slug}}" class="d-block">
						   <img class="img-fluid" src="{{$game->image_url}}" alt="image"></a>
                        </div>
                        <div class="grid-content">
                           <h4><a href="{{url('game-guide')}}/{{$game->slug}}">{{$game->title}} </a></h4>
                           <p>
                              {!! substr($game->short_description,0, $limitText) !!}
                              @if(strlen($game->short_description) > $limitText)
                                 ...
                              @endif
                           </p>
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