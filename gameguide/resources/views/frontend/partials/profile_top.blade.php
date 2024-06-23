@if(auth::user()->banner_photo==NULL)
	@php
		$banner_photo =  url('frontend/images/game_guide_banner.png');
	@endphp
@else
	@php
		$banner_photo =  banner_photo(auth::user()->id);
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

<section class="profile-banner" style="background-image:url({{$banner_photo}})">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="content-holder">
					<div class="profile-image">
						<input type="hidden" value="{{auth::user()->role_id}}" id="user_role_id">
						@if(auth::user()->profile_photo==NULL)
					       {{--<div class="thumb profile_photo image"> --}}
								<a href="javascript:void(0)" data-toggle="modal" data-target="#upload_photo_modal" class="show_image"  >
									<span> 
										<img src="{{ url('frontend/images/user-profile.png')}}">
										{{-- substr(auth::user()->first_name,0,1) --}}
									</span>
								</a>
				            {{--</div>--}}
						@else
						 
							@php
								$photo =  profile_photo(auth::user()->id);
							@endphp

							{{--<div class="image profile_photo">--}}
								<a href="javascript:void(0)" data-toggle="modal" data-target="#upload_photo_modal" class="show_image"  >
									<div id='profile-upload'>
										<img class="profile_photo_change" src="{{$photo}}">
										{{--<img class="profile_photo_change" src="{{timthumb($photo,140,140)}}">--}}
									  	<i class="fa fa-camera"></i>
									</div>
								</a>	
							{{--</div>--}}
			 
						@endif

							
					</div>
					<div class="profile-info">
						@include('frontend.partials.name_edit')
					</div>
				</div>
			</div>
		</div>
	</div>
	
	@include('frontend.partials.tag')
	
	@if(auth::user()->role_id == 3)
		<div class="chat-now">
			<a href="javascript:void(0);" class="edit-description" data-toggle="modal" data-target="#edit_description_modal">
				<i class="fas fa-pencil-alt"></i> Edit Description
			</a>
		</div>
	@endif
	<a href="javascript:void(0);" data-toggle="modal" data-target=".upload_banner_modal" class="edit-option"><i class="fas fa-pencil-alt"></i></a>
</section>


<!-- ************ Profile Pic upload  POPUP **************** -->  

@php $className = ''; @endphp
@if(auth::user()->role_id == 3)
	@php $className = 'coache_user'; @endphp
@endif

<!-- Modal -->
<div class="modal fade upload_photo_modal {{$className}}" id="upload_photo_modal" tabindex="-1" role="dialog" aria-labelledby="upload_photo_modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Upload Photo </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
      	<input type="hidden" id="old_profile_image" value="{{auth::user()->profile_original_photo_url}}">
      	@if($className != '')
	      	<div class="row mb-4">
	      		<div class="col-lg-6">
	      			<div id="upload-demo"></div>
	      		</div>
	      		<div class="col-lg-6">
	      			<div id="upload-coache-demo"></div>
	      		</div>
	      	</div>
	    @else
			<div id="upload-demo"></div>
		@endif


		<h6>Choose image to crop:</h6>
		<!--input type="file" id="image_file"--> 
		 <input type="file" id="upload_profile_file" name="upload_profile_file" class="upload_input" accept="image/*" onChange="validate(this.value)"/>
		 <label for="upload_profile_file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg> <span>Choose a file…</span></label>
		<p class="upload_profile_file_error error"></p>
		<p class="upload_profile_crop_file_error error"></p>
		<a href="javascript:void(0)" class="upload-image btn modal-btn mt-0" id="upload-image"><i class="fa fa-spinner fa-spin loader_image_profile" style="display:none"></i> Upload </a>
		<div class="alert alert-success" id="upload-success" style="display: none;margin-top:10px;"></div>


      </div>
    </div>
  </div>
</div>


<!-- **************** BANNER UPLOAD PHOTO MODAL ************* -->  






<!-- Modal -->
<div class="modal fade upload_banner_modal" id="upload_banner_modal" tabindex="-1" role="dialog" aria-labelledby="upload_banner_modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Upload Banner</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
      	<div id="upload-banner-demo"></div>
		<form method="POST" action="{{url('/upload_banner_photo')}}" id="upload_banner_form">
			@csrf
			<div class="mod-content">
				<input type="file" id="upload_banner_file" name="upload_banner_file" class="upload_banner_file upload_input" accept="image/*"/>
				 <label for="upload_banner_file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg> <span>Choose a file…</span></label>
				<p class="upload_banner_file_error error"></p>
				<p class="upload_banner_crop_file_error error"></p>
			</div>
			<div class="mod-content1">
				<a href="javascript:void(0)" class="upload_banner btn modal-btn mt-0" id="upload_banner"><i class="fa fa-spinner fa-spin loader_banner" style="display:none"></i> Upload </a> 
			</div>
		</form>

      </div>
    </div>
  </div>
</div>