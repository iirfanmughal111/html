@if(isset($coacheRate))
	<div class="coaches-prfile-list">
		@php
			$user_data = user_data_by_id($coacheRate->user_id);
		@endphp
		<div class="pic">
			@if(isset($user_data))
				<img alt="Profile Picture" src="{{$user_data->profile_photo_url}}">
				<span>{{$user_data->full_name}}</span>
			@endif
		</div>
		<div class="content">
			@if(!empty($coacheRate->rating))
				<div class="rating-comment" data-rating="{{$coacheRate->rating}}"></div>
			@endif
			{{--<span class="ratings">
			 <i class="fa fa-star" aria-hidden="true"></i>
			 <i class="fa fa-star" aria-hidden="true"></i>
			 <i class="fa fa-star" aria-hidden="true"></i>
			 <i class="fa fa-star" aria-hidden="true"></i>
			 <i class="fa fa-star" aria-hidden="true"></i>
			</span>--}}
			<p>{!! $coacheRate->comment !!}</p>		
		</div>		
	</div>
@endif