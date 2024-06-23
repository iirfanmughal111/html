/**** Upgrade plan  *****/
$(document).on('click','.pricing-block .updatePlan',function(){
	var $this = $(this);
	//Remove class selected
	$('.updatePlan').removeClass('selected');
	$(this).addClass('selected');
	$("#paymentMethodModal").modal("show")
});

$(document).on('click','.save-payment-method',function(){
	var $this = $(this);
	$('.error').html('');
	var radioValue = $("input[name='payment_method']:checked").val();
	if(radioValue != '' && radioValue != 'undefined' && radioValue != null){
		var selectedName = $("input[name='payment_method']:checked").data("mname");
		if(selectedName != '' && selectedName != 'undefined' && selectedName != null)
			selectedName = selectedName.toLowerCase();

		if(selectedName == 'stripe'){
			$("#paymentMethodModal").modal("hide")
	        $("#stripeCardModal").modal("show")
	    }else{
	    	paypalModel();
	    }
	}else{
		$('.payment_method_error').html('Please select Payment Method.')
	}
});

/*Open paypal Model*/
function paypalModel(){
	var csrf_token = $('input[name="_token"]').val();
	var planSelected = $('.updatePlan.selected');
	if(planSelected.length >0){
		var selectedPlan = planSelected.data("paypal");
		var selectedPlanCost = planSelected.data("cost");
		
		$("#plan_id").val(selectedPlan);
		$('#plan_id_popup').val(selectedPlan);
		$('#csrf_token_val').val(csrf_token);
		$('#plan_price_popup').val(selectedPlanCost);
		$("#paymentMethodModal").modal("hide")
		$("#payment_modal").modal('show');
	}else{
		/*Please select plan*/
		alert('Please select plan');
	}
}