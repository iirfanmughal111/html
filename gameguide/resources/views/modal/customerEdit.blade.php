<div class="modal-dialog" role="document">
	<div class="modal-content">
	<div class="modal-header py-1">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body">
	<form action="{{ url('update-customer/') }}/{{ $user->id }}" method="POST" id="updateUser" >
	 @csrf
		
		<div class="form-group form-row-parent">
			<label class="col-form-label">{{ trans('global.first_name') }}<em>*</em></label>
			<div class="d-flex control-group">
				<input type="text" name="first_name" value="{{$user->first_name}}" class="form-control" placeholder="First Name">									
			</div>	
			<div class="first_name_error errors"></div>	
		</div>
		
		
	
		<div class="form-group form-row-parent">
			<label class="col-form-label">{{ trans('global.last_name') }}<em>*</em></label>
			<div class="d-flex control-group">
				<input type="text" name="last_name" value="{{$user->last_name}}" class="form-control" placeholder="Last Name">									
			</div>	
			<div class="last_name_error errors"></div>	
		</div>
		
		
		
		<div class="form-group form-row-parent">
			<label class="col-form-label">{{ trans('global.email') }}</label>
			<div class="d-flex control-group">
			<input type="email" name="email" disabled="disabled" value="{{$user->email}}" readonly class="form-control" placeholder="{{ trans('global.email') }}">								
			</div>								
		</div>

		<div class="form-group form-row-parent">
			<label class="col-form-label">Role<em>*</em></label>
			<div class="d-flex control-group">
				<select  id="role_id"  class="form-control select2-single"  name="role_id"  data-width="100%">
								
					<option value=" ">Select Role</option>
					@foreach($roles as $key=>$role)
					
						<option value="{{$role->id}}" @if($user->role_id == $role->id) selected @endif >{{$role->title}}</option>
					
					@endforeach
				</select>
			</div>
			 <div class="role_id_error errors"></div>	
		</div>

		<div class="form-group form-row-parent">
			<label class="col-form-label">Tag Line</label>
			<div class="d-flex control-group">
				<textarea class="form-control" placeholder="Tag Line" rows="3" name="tag_line">{{$user->tag_line}}</textarea>
			</div>
			 <div class="tag_line_error errors"></div>	
		</div>


		{{--<div class="form-group form-row-parent">
			<label class="col-form-label">Description</label>
			<div class="d-flex control-group">
				<textarea class="form-control" placeholder="Description" rows="10" name="description">@if(isset($user->userProfile)){{$user->userProfile->description}}@endif
				</textarea>
			</div>
			 <div class="description_error errors"></div>	
		</div>

		<div class="form-group form-row-parent">
			<label class="col-form-label">Rating</label>
			<div class="d-flex control-group">
				<select id="rating" class="form-control select2-single" name="rating" data-width="100%">		
					<option value=" ">Select Rating</option>
					@for($i=1;$i<=5;$i++)
						<option value="{{$i}}" @if(isset($user->userProfile))@if($user->userProfile->rating == $i) selected @endif @endif>
							{{$i}}
						</option>
					@endfor
				</select>
			</div>
			<div class="rating_error errors"></div>	
		</div>--}}

		<div class="form-group form-row-parent">
			<label class="col-form-label">Facebook Link</label>
			<div class="d-flex control-group">
				<input type="text" name="facebook_link" value="@if(isset($user->userProfile)){{$user->userProfile->facebook_link}}@endif" class="form-control" placeholder="Facebook Link">									
			</div>	
			<div class="facebook_link_error errors"></div>	
		</div>

		<div class="form-group form-row-parent">
			<label class="col-form-label">Instagram Link</label>
			<div class="d-flex control-group">
				<input type="text" name="instagram_link" value="@if(isset($user->userProfile)){{$user->userProfile->instagram_link}}@endif" class="form-control" placeholder="Instagram Link">									
			</div>	
			<div class="instagram_link_error errors"></div>	
		</div>

		<div class="form-group form-row-parent">
			<label class="col-form-label">Twitter Link</label>
			<div class="d-flex control-group">
				<input type="text" name="twitter_link" value="@if(isset($user->userProfile)){{$user->userProfile->twitter_link}}@endif" class="form-control" placeholder="Twitter Link">									
			</div>	
			<div class="twitter_link_error errors"></div>	
		</div>
	
		<div class="form-row mt-4">
		<div class="col-md-12">
		<input id ="user_id" class="form-check-input" type="hidden" value="{{$user->id}}">
		<button type="submit" class="btn btn-primary default btn-lg mb-2 mb-sm-0 mr-2 col-12 col-sm-auto">{{ trans('global.submit') }}</button>
		<div class="spinner-border text-primary request_loader" style="display:none"></div>
		</div>
		</div>
		
		</form>

				</div>
			</div>
		</div>