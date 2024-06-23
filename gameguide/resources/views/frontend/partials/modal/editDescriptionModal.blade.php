<!-- Modal -->
<div class="modal fade add_rating_modal edit_description_modal" id="edit_description_modal" tabindex="-1" role="dialog" aria-labelledby="add_rating_modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Description</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{url('/edit_description')}}" class="grid-form" id="userDescriptionForm">
          {{ csrf_field() }}
          <div class="holder">
            <div class="comment-box">
              {{ Form::textarea('user_description',old('user_description', ''),array('class'=>'form-control','placeholder'=>'Description','rows'=>10)) }}
              {{--<textarea class="form-control" id="exampleFormControlTextarea1" rows="10" placeholder="Description" name="user_description">{{auth::user()->userProfile->description ?? ''}}</textarea>--}}
              <div class="user_description_error errors"></div>
              <input type="hidden" name="user_id" value="{{auth::user()->id}}">
              <div class="user_id_error errors"></div>
              
              <a href="javascript:void(0);" class="btn-comment btn submit-description"><i class="fa fa-spinner fa-spin request_loader" style="display:none"></i>Submit</a>
            </div>		
          </div>
        </form>

      </div>
    </div>
  </div>
</div>