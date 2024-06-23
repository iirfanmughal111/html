@if($coache->banner_photo==NULL)
	@php
		$banner_photo =  url('frontend/images/game_guide_banner.png');
	@endphp
@else
	@php
		$banner_photo =  banner_photo($coache->id);
	@endphp
@endif
@php
	$className =  'other-page';
@endphp
@if(Request::segment(1) == 'user-profile')
	@php
		$className =  'profile-page';
	@endphp
@elseif(Request::segment(1) == 'coache-details')
	@php
		$className =  'coache-page';
	@endphp
@endif
@if($coache->banner_photo==NULL)
	@php $banner_photo = url('frontend/images/breadcrumb_bg@2x.png');@endphp 
@else
	@php $banner_photo = banner_photo($coache->id);@endphp
@endif


<section class="profile-banner" style="background-image:url({{ $banner_photo}})">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content-holder">
				<div class="profile-image">
					@if($coache->profile_photo==NULL)
						<img src="{{ url('frontend/images/user-profile.png')}}">
					@else
						@php
							$photo =  profile_photo($coache->id);
						@endphp
						<img class="profile_photo_change" src="{{$photo}}">
						{{--<img class="profile_photo_change" src="{{timthumb($photo,140,140)}}">--}}
					@endif
				</div>
				<div class="profile-info coache-rating-data">
					@include('frontend.partials.coache_rating')
					{{--<h3 class="name with-ratings">{{$coache->full_name}}
						<span class="ratings">
						 <i class="fa fa-star" aria-hidden="true"></i>
						 <i class="fa fa-star" aria-hidden="true"></i>
						 <i class="fa fa-star" aria-hidden="true"></i>
						 <i class="fa fa-star" aria-hidden="true"></i>
						 <i class="fa fa-star" aria-hidden="true"></i>
						</span>

						<a href="javascript:void(0);" class="edit-coache-rating" data-toggle="modal" data-target="#add_rating_modal">
							<i class="fas fa-pencil-alt"></i>
						</a>
					</h3>--}}
					<div class="p-social">
						@include('frontend.partials.social_link_icons')
                   </div>
				</div>
				</div>
			</div>
		</div>
	</div>
	{{--<a href="#/" class="edit-option"><i class="fas fa-pencil-alt"></i></a>--}}
	@if((isset(auth::user()->plan_id) && auth::user()->plan_id == 2) || (isset(auth::user()->role_id) && auth::user()->role_id == 3))
		<div class="chat-now">
			<input type="hidden" id="coacheid" value="{{$coache->id}}">
			<input type="hidden" id="coachehash" value="{{$coache->hash}}">
			<a href="javascript:void(0);" class="coache_chat_now">
				<span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="19.5" height="19.5" viewBox="0 0 19.5 19.5"><defs><style>.a{fill:#fff;}</style></defs><path class="a" d="M9.75,0A9.751,9.751,0,0,0,1.293,14.6L.037,18.5A.762.762,0,0,0,1,19.464l3.9-1.256A9.75,9.75,0,1,0,9.75,0Zm0,17.977A8.2,8.2,0,0,1,5.4,16.73a.762.762,0,0,0-.637-.079l-2.816.907.907-2.816A.762.762,0,0,0,2.77,14.1a8.227,8.227,0,1,1,6.98,3.872ZM10.7,9.75A.952.952,0,1,1,9.75,8.8.952.952,0,0,1,10.7,9.75Zm3.809,0a.952.952,0,1,1-.952-.952A.952.952,0,0,1,14.511,9.75Zm-7.617,0A.952.952,0,1,1,5.941,8.8.952.952,0,0,1,6.894,9.75Zm0,0"/></svg></span>Chat Now
			</a>
		</div>
	@endif
	
   </section>