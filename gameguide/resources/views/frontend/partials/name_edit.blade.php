@if($className == 'profile-page')

    <div class="profile-name">
    	<h3 class="name">{{auth::user()->first_name}} {{auth::user()->last_name}}</h3>
      
      <a href="javascript:void(0);" class="edit-name" data-toggle="modal" data-target="#edit_name_modal"> <i class="fas fa-pencil-alt"></i></a>

    </div>

    <div class="profile-name">
      <h3 class="mail">{{auth::user()->email}}</h3>
  </div>

    <div class="modal fade add_rating_modal edit_name_modal" id="edit_name_modal" tabindex="-1" role="dialog" aria-labelledby="edit_name_modalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="holder">
              <form method="POST" action="{{url('/edit_profile_name')}}" class="grid-form" id="edit_profile_name">
  
                  {{ csrf_field() }}
  
                  <div class="form-group">
                      <label for="email">First Name</label>
                      <input type="text" class="input w-input" maxlength="256" name="first_name" placeholder="First Name" id="first_name" value="{{auth::user()->first_name}}">
					            @if ($errors->has('first_name'))
					            <div class="error_margin">
						          <span class="error" role="alert">
						        	{{ $errors->first('first_name') }}
						          </span>
					            </div>
					            @endif
                  </div>
      
                  <div class="form-group">
                    <label for="email">Last Name</label>
                      <input type="text" class="input w-input" maxlength="256" name="last_name" placeholder="Last Name" id="last_name" value="{{auth::user()->last_name}}">
                      @if ($errors->has('last_name'))
					            <div class="error_margin">
						          <span class="error" role="alert">
						        	{{ $errors->first('last_name') }}
						          </span>
					            </div>
					            @endif
                  </div>
      
                  <div class="form-group">
                      <label for="email">Email Address</label>
                      <input type="email" class="input w-input" maxlength="256" name="email" placeholder="Email Address" id="email" value="{{auth::user()->email}}">
                      @if ($errors->has('email'))
					            <div class="error_margin">
						          <span class="error" role="alert">
						        	{{ $errors->first('email') }}
						          </span>
					            </div>
					            @endif
                  </div>
                  
                  <div class="comment-box">
                    <input type="hidden" name="user_id" value="{{auth::user()->id}}">
                    <div class="user_id_error errors"></div>
                    <a href="javascript:void(0);" class="btn-comment btn save_name_profile" id="save_name_profile"> <i class="fa fa-spinner fa-spin request_loader" style="display:none"></i> Save</a>
                  </div>

              </form>		
            </div>
          </div>
        </div>
      </div>
    </div>
    
@else
   <h3 class="name">{{auth::user()->first_name}} {{auth::user()->last_name}}</h3> 
@endif

<div class="p-social profile-social">
    @include('frontend.partials.social_link_icons')
</div>
