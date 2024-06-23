<!-- Modal -->
<div class="modal fade add_rating_modal edit_social_links_modal" id="edit_social_links_modal" tabindex="-1" role="dialog" aria-labelledby="edit_social_links_modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Social links</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="holder">
          <form method="POST" action="{{url('/edit_social_link')}}" class="grid-form" id="social-link-Form">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="facebook_link">Facebook</label>
                <input type="url" class="input w-input" placeholder="Facebook link" id="facebook_link" name="facebook_link" value="{{auth::user()->userProfile->facebook_link ?? ''}}">
                <div class="facebook_link_error errors"></div>
              </div>

              <div class="form-group">
                <label for="facebook_link">Twitter</label>
                <input type="url" class="input w-input" placeholder="Twitter link" id="twitter_link" name="twitter_link" value="{{auth::user()->userProfile->twitter_link ?? ''}}">
                <div class="twitter_link_error errors"></div>
              </div>

              <div class="form-group">
                <label for="facebook_link">Instagram</label>
                <input type="url" class="input w-input" placeholder="Instagram link" id="instagram_link" name="instagram_link" value="{{auth::user()->userProfile->instagram_link ?? ''}}">
                <div class="instagram_link_error errors"></div>
              </div>
              
              
            
              <div class="comment-box">
                <input type="hidden" name="user_id" value="{{auth::user()->id}}">
                <div class="user_id_error errors"></div>
                <a href="javascript:void(0);" class="btn-comment btn submit-social-link"> <i class="fa fa-spinner fa-spin request_loader" style="display:none"></i> Save</a>
            </div>
          </form>		
        </div>

      </div>
    </div>
  </div>
</div>