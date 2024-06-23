{{-- Add paymethod method Model --}}
<div class="modal fade popupdiv popup_table" id="paymentMethodModal" class="paymentMethodModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		   <div class="modal-header">
		      <h4 class="modal-title">Please select Payment method</h4>
		      <button type="button" class="close" data-dismiss="modal">&times;</button>
		   </div>
		   <div class="modal-body form_div">
			   @if(isset($paymentMethod) && count($paymentMethod) > 0)
		        <div class="form-group">
		          <label for="plan">Payment Method</label>
		          @foreach($paymentMethod as $method)
		            <div class="radio">
		              <label class=" ml-3">
		                <input type="radio" name="payment_method" id="payment_method" class="payment_checkbox" value="{{$method->id}}" data-mname="{{$method->name}}" @if (old('payment_method') == "$method->id") {{ 'checked' }} @endif /> <span>{{$method->name}} </span>
		              </label>
		            </div>
		          @endforeach
		          <div class="error_margin"><span class="error payment_method_error" >  {{ $errors->first('payment_method')  }} </span></div>
		        </div>
		        @endif   
			   <input type="hidden" name="path" id="path" value="{{$hidden_path ?? ''}}">
			   <div class="submitbtn text-center confirmbtn">
			      <button type="submit" class="save-payment-method btn btn-primary t-2" value="Submit">Select Payment
				  <div class="spinner-border ml-3 stripe-spinner" role="status" style="display:none;">
				  	<span class="sr-only">Loading...</span>
					</div>   
				  </button>    
				</div>
				
		   </div>
		</div>
	</div>
</div>
{{-- Add paymethod method Model End--}}