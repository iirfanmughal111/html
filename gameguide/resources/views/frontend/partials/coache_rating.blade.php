<h3 class="name with-ratings">{{$coache->full_name}}
	@if(isset($coache->userProfile) && !empty($coache->userProfile->rating))
		<div class="coache-rating" data-rating="{{$coache->userProfile->rating}}"></div>
	@endif
	<!--<span class="ratings">
	 <i class="fa fa-star" aria-hidden="true"></i>
	 <i class="fa fa-star" aria-hidden="true"></i>
	 <i class="fa fa-star" aria-hidden="true"></i>
	 <i class="fa fa-star" aria-hidden="true"></i>
	 <i class="fa fa-star" aria-hidden="true"></i>
	</span>-->
	@if(isset($isUserReview) && $isUserReview == 0)
		<a href="javascript:void(0);" class="edit-coache-rating" data-toggle="modal" data-target="#add_rating_modal">
			<i class="fas fa-pencil-alt"></i>
		</a>
	@endif
</h3>