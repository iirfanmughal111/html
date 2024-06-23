<div id="payment_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<?php 
	if(config('paypal.settings.mode') == 'live'){
		$client_id = config('paypal.live_client_id');
		$secret = config('paypal.live_secret');
		$api_url = config('paypal.live_api_url');
	} else {
		$client_id = config('paypal.sandbox_client_id');
		$secret = config('paypal.sandbox_secret');
		$api_url = config('paypal.sandbax_api_url');
	}
?>

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title">Payment</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="payment_body">
		<div id="paypal-button-container"></div>
			<input type="hidden" id="plan_id_popup" value="">
			<input type="hidden" id="plan_price_popup" value="">
			<input type="hidden" id="csrf_token_val" value="">
			<input type="hidden" name="path" id="path" value="{{$hidden_path ?? ''}}">
			<script src="https://www.paypal.com/sdk/js?client-id=ATS02aq6KyfLWqIob9YutwLmY_P2T_U8PobzSJbBPmSa6PrhjYbmciYpDfC-5CrqOdu1GUqyvtwqsMe4&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
			
			{{--<script src="{{ url('frontend/js/jquery-2.2.0.min.js')}}" type="text/javascript"></script>--}}

			<script>
			
				// var plan_id = document.getElementById("plan_id");
				//var planId = document.getElementById("plan_id_popup").value;
				
				var apiKey = '{{$client_id}}';
				var password = '{{$secret}}';
				var api_url = '{{$api_url}}';
				var user_id = '{{Auth::user() ? Auth::user()->id : ''}}';
				var plan_price = document.getElementById("plan_price_popup").value;
				
				var csrf_token = document.getElementById("csrf_token_val").vaue;
				
				paypal.Buttons({
				  style: {
					  shape: 'rect',
					  color: 'gold',
					  layout: 'vertical',
					  label: 'subscribe'
				  },
					createSubscription: function(data, actions) {
						//var planId = document.getElementById("plan_id_popup").value;
						return actions.subscription.create({
							'plan_id': document.getElementById("plan_id_popup").value
						});
					},
					onApprove: function(data, actions) {
						$.ajax({
							type: "POST",
							url: api_url+"/v1/oauth2/token",
							dataType: "json",
							data: {grant_type: "client_credentials"},
							beforeSend: function(xhr) { 
								xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
								xhr.setRequestHeader("Authorization", "Basic " + btoa([apiKey, password].join(":"))); 
							},
							 success: function (result) {
								// alert(JSON.stringify(result));
								//$(".loading_subscriber").show();
								//GET TOKEN 
								$.ajax(api_url+"/v1/billing/subscriptions/"+data.subscriptionID, {
									method: "GET",
									headers: {
									  "Content-Type": "application/json",
									  "Authorization": "Bearer "+result.access_token
									},
									dataType: "json",
									success: function (data) {
										var hiddenPath = $('#path').val();
										// alert(JSON.stringify(data));
										var subscription_id = data.id;
										var plan_id = data.plan_id;
										//var plan_price = data.plan_price;
										//var quantity = data.quantity;
										var PayerName = data.subscriber.name.given_name+' '+data.subscriber.name.surname;
										var PayerMail = data.subscriber.email_address;
										var payer_id = data.subscriber.payer_id;
										//var Total = data.shipping_amount.value;
										var CreateTime = data.start_time;
										var UpdateTime = data.status_update_time;
										var next_billing_time = data.billing_info.next_billing_time;
										var status = data.status;
										var planSelected = $('.updatePlan.selected');
									    if(planSelected.length >0){
									      var selectedPlanId = planSelected.data("planid");
									      var selectedPaymentMethod = $("input[name='payment_method']:checked").val()
									    }
										var formData = {_token:document.getElementById("csrf_token_val").value,subscription_id:subscription_id,plan_id:plan_id,user_id:user_id,PayerName:PayerName,PayerMail:PayerMail,payer_id:payer_id,plan_price:plan_price,CreateTime:CreateTime,UpdateTime:UpdateTime,status:status,next_billing_time:next_billing_time,plan:selectedPlanId,payment_method:selectedPaymentMethod
											}; 
										var ajax_url = base_url +'/update-plan';
										if(hiddenPath == 'register'){
											var form = $('#Signup-Form');
											ajax_url = form.attr('action');
											formData = $('#Signup-Form').serializeArray();

											formData.push({ name: "subscription_id", value: subscription_id });
											formData.push({ name: "PayerName", value: PayerName });
											formData.push({ name: "PayerMail", value: PayerMail });
											formData.push({ name: "payer_id", value: payer_id });
											formData.push({ name: "plan_price", value: plan_price });
											formData.push({ name: "CreateTime", value: CreateTime });
											formData.push({ name: "UpdateTime", value: UpdateTime });
											formData.push({ name: "next_billing_time", value: next_billing_time });
											formData.push({ name: "status", value: status });
										}
										$.ajax({
											url: ajax_url,
											dataType: 'json',
											type: 'post',
											contentType: 'application/x-www-form-urlencoded',
											data: formData,
											success: function(data){
												if(data.success){
													$("#payment_modal").modal('toggle');

													
													if(typeof (data.redirect_url) != 'undefined' && data.redirect_url != null && data.redirect_url != ''){
									              			window.location = data.redirect_url;
									            	}else{
									            		if(typeof (data.message) != 'undefined' && data.message != null && data.message != ""){
															notification('Success',data.message,'top-right','success',2000);
														}else{
															notification('Success','Your Plan is successfully activated.','top-right','success',2000);
														}
									            		//redirect current page
									            		setTimeout(function(){
									            			window.location.reload();
									            		},2000);
									            	}
										            
													
												}else{
													$(".loading_subscriber").hide();
													$('.user_subscribe_'+user_id).hide();  //Hide followed user from list 
													notification('Error','Something went wrong.','top-right','error',2000);
													$("#payment_modal").modal('toggle');
													setTimeout(function(){ $('.subscribeModal_'+user_id).modal('hide'); }, 1000);
												}	
											}
										});
									},
									error: (xhr, textStatus, errorThrown) => {
										console.log(textStatus, errorThrown);
										$('.error-message-box').show();
										notification('Error',' There is some issue processing your request.You can try later.','top-right','error',3000);
										
									}
								});
							},
							error: (xhr, textStatus, errorThrown) => {
								notification('Error',' There is some issue processing your request.You can try later.','top-right','error',3000);
								
							}
						});
					},
					onError: function (err) {
						notification('Error',' There is some issue processing your request.You can try later.','top-right','error',3000);
					},
					onCancel: function (data) {
						
					}
			  }).render('#paypal-button-container');
			</script>
			
		</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>