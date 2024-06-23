<!-- Modal -->
<div class="modal fade add_rating_modal" id="add_rating_modal" tabindex="-1" role="dialog" aria-labelledby="add_rating_modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">ADD RATING</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ url('coache/user-rating') }}" method="POST" id="userRatingForm" >
        @csrf
        <div class="holder">
        	<div class="pic">
            @if(auth::user()->profile_photo==NULL)
              <img alt="Profile Picture" src="{{ url('frontend/images/user-profile.png')}}">
            @else
              @php
                $photo =  profile_photo(auth::user()->id);
              @endphp
              <img alt="Profile Picture" src="{{timthumb($photo,140,140)}}">
            @endif
        	</div>
        	<span class="name">
            @if(auth::user())
              {{auth::user()->first_name}} {{auth::user()->last_name}}
            @endif
            {{--Devid Loren--}}
          </span>
          <div class="my-rating"></div>
          <input type="hidden" name="rating" value="" id="rating">
          <div class="rating_error errors"></div> 

          <div class="comment-box">
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Message" name="comment"></textarea>
            <div class="comment_error errors"></div>
            <input type="hidden" name="user_id" value="{{auth::user()->id}}">
            <div class="user_id_error errors"></div>
            <input type="hidden" name="coache_id" value="{{$coache->id}}">
            <div class="coache_id_error errors"></div>
            <a href="javascript:void(0);" class="btn-comment btn submit-rating"><i class="fa fa-spinner fa-spin request_loader" style="display:none"></i>Post Comment</a>
          </div>		
        </div>
      </form>

      </div>
    </div>
  </div>
</div>