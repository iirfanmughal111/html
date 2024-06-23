@if(isset($game->gameGuide) && count($game->gameGuide) > 0)
   @php $gameGuides = $game->gameGuide; @endphp
   <div class="row mt-3 mb-4">
      @foreach($gameGuides as $guide)
         <div class="col-lg-4 col-md-4">
            <div class="grid-box">
               <div class="grid-image">
			     @php 
				 $game_guideTag = explode('|',$guide->guide_tag)
				 @endphp 
                  <a href="{{url('game-guide/guide')}}/{{$guide->guideType->name}}/{{$guide->slug}}" class="d-block t-box tagging">
				   @if(count($game_guideTag)>0)
				    <div class="gameguide">
				    @foreach($game_guideTag as $key =>$value)
					 <span class="guidetag">{{ $value}}</span>
				    @endforeach	
					</div>
					@endif 
				  <img class="img-fluid image" src="{{$guide->image_url}}" alt="image">
              
                  </a>
               </div>
			   
			   <!--div class="grid-image">
                  <a href="{{url('game-guide/guide')}}/{{$guide->guideType->name}}/{{$guide->slug}}" class="d-block t-box"><img class="img-fluid image" src="{{$guide->image_url}}" alt="image">
                     <div class="middle">
                        <div class="text">{{ucwords($guide->guideType->name)}} Guide</div>
                     </div>
                  </a>
               </div-->
			   
               <div class="grid-content gc-txt">
                  <h4><a href="{{url('game-guide/guide')}}/{{$guide->guideType->name}}/{{$guide->slug}}">{{ucwords($guide->title)}}</a></h4>
               </div>
            </div>
         </div>
      @endforeach
      {{--<div class="col-lg-4 col-md-4">
         <div class="grid-box">
            <div class="grid-image">
               <a href="{{('/guide-details')}}" class="d-block t-box"><img class="img-fluid image" src="{{ url('frontend/images/Fortnite.jpg')}}" alt="image">
               <div class="middle">
                <div class="text">Video Guide</div>
               </div>
   		 </a>

            </div>
            <div class="grid-content gc-txt">
               <h4><a href="{{('/guide-details')}}">Game Guid 1</a></h4>
            </div>
         </div>
      </div>
   			
   	<div class="col-lg-4 col-md-4">
         <div class="grid-box">
            <div class="grid-image">
               <a href="{{('/guide-details')}}" class="d-block t-box"><img class="img-fluid image" src="{{ url('frontend/images/valorant.jpg')}}" alt="image">
   		 					 <div class="middle">
   		   <div class="text">Text Guide</div>
   		   </div>
   		 </a>
   		 
            </div>
            <div class="grid-content gc-txt">
               <h4><a href="{{('/guide-details')}}">Game Guid 2</a></h4>
            </div>
         </div>
      </div>
   			
   	<div class="col-lg-4 col-md-4">
            <div class="grid-box">
               <div class="grid-image">
                  <a href="{{('/guide-details')}}" class="d-block t-box"><img class="img-fluid image" src="{{ url('frontend/images/league-of-legends.jpg')}}" alt="image">
   			 					 <div class="middle">
   			   <div class="text">Video Guide</div>
   			   </div></a>
               </div>
               <div class="grid-content gc-txt">
                  <h4><a href="{{('/guide-details')}}">Game Guid 3</a></h4>
               </div>
            </div>
         </div>--}}

   </div>
@endif