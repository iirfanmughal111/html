/* Check Access Code Have A Value Or Not ? */

$('#Signup-Form input[type="text"]').ready(function(){
    $(document).ready(function(){
		if($("#access_code").val()){
			$("#pay_plan").hide();
			$("#Fst_Lst").hide();
			$("#eml").hide();
			$("#c_pass").hide();
			
			var e = Date.now();
			$("#first_name").val("First" + "_" + e);
			$("#last_name").val("Last" + "_" + e);
			$("#email").val('Player' + "_" + e + "@esportsguides.se");

			$('.plan_checkbox').prop('checked', true);

		}else{	
			$("#pay_plan").show();
			$("#Fst_Lst").show();
			$("#eml").show();
			$("#c_pass").show();
			$('.plan_checkbox').prop('checked', false);	
		};
	});
	$(document).keyup(function(){
		if($("#access_code").val()){
			$("#pay_plan").hide();
			$("#Fst_Lst").hide();
			$("#eml").hide();
			$("#c_pass").hide();
			
			var e = Date.now();
			var a = $("#first_name").val("First" + "_" + e);
			var b = $("#last_name").val("Last" + "_" + e);
			var c = $("#email").val('Player' + "_" + e + "@esportsguides.se");

			$('.plan_checkbox').prop('checked', true);

		}else{	
			$("#pay_plan").show();
			$("#Fst_Lst").show();
			$("#eml").show();
			$("#c_pass").show();
			$('.plan_checkbox').prop('checked', false);	
		};
	});
});

$(document).on('click','.submit-button', function(e) {
    e.preventDefault(); 
    $('.error').html('');
	var radioValue = $("input[name='payment_method']:checked").val();
	console.log(radioValue);
	if(radioValue != '' && radioValue != 'undefined' && radioValue != null){
		var selectedName = $("input[name='payment_method']:checked").data("mname");
		if(selectedName != '' && selectedName != 'undefined' && selectedName != null)
			selectedName = selectedName.toLowerCase();

	    	/*Check valid parameter*/
	    	var form = $('#Signup-Form');
	        $('.register-spinner').css('display','inline-block');
	        var ajax_url = base_url+'/check_register';
	        var method = form.attr('method');
			
	        $.ajax({
	            type: method,
	            url: ajax_url,
	            data: form.serialize(),
	            success: function(data) {
	            	// $('.register-spinner').css('display','none');
	                 if(data.success){
	                 	if(selectedName == 'stripe'){
					        $("#stripeCardModal").modal("show")
					    }else{
					    	paypalModel();
					    }    
	                }
	            },
	            error :function( data ) {
	                
	                if( data.status === 422 ) {
	                	$('html, body').animate({
		                    scrollTop: $("#sign-up-sec").offset().top
		                }, 2000);
	                    $('.register-spinner').css('display','none');
	                    $('.errors').html('');
	                    //notification('Error','Please fill all the fields.','top-right','error',4000);
	                    var errors = $.parseJSON(data.responseText);
	                    $.each(errors, function (key, value) {
	                        // console.log(key+ " " +value);
	                        if($.isPlainObject(value)) {
	                            $.each(value, function (key, value) {                       
	                                //console.log(key+ " " +value); 
	                              var key = key.replace('.','_');
	                              $('.'+key+'_error').show().append(value);
	                            });
	                        }else{
	                        // $('#response').show().append(value+"<br/>"); //this is my div with messages
	                        }
	                    }); 
	                  }      
	            }
	        });
	}else{
		
		var form = $('#Signup-Form');
	        $('.register-spinner').css('display','inline-block');
	        var ajax_url = base_url+'/register';
	        var method = form.attr('method');
	        $.ajax({
	            type: method,
	            url: ajax_url,
	            data: form.serialize(),
	            success: function(data) {
	            	$('.register-spinner').css('display','none');
	
	                 if(data.success){
						window.location.replace(data.redirect_url);
	                }
					else
					{
						notification('Error','Access Code Is Invalid.','top-right','error',3000);
					}
	            },
	            error :function( data ) {
	                
	                if( data.status === 422 ) {
	                	$('html, body').animate({
		                    scrollTop: $("#sign-up-sec").offset().top
		                }, 2000);
	                    $('.register-spinner').css('display','none');
	                    $('.errors').html('');
	                    //notification('Error','Please fill all the fields.','top-right','error',4000);
	                    var errors = $.parseJSON(data.responseText);
	                    $.each(errors, function (key, value) {
	                        // console.log(key+ " " +value);
	                        if($.isPlainObject(value)) {
	                            $.each(value, function (key, value) {                       
	                                //console.log(key+ " " +value); 
	                              var key = key.replace('.','_');
	                              $('.'+key+'_error').show().append(value);
	                            });
	                        }else{
	                        // $('#response').show().append(value+"<br/>"); //this is my div with messages
	                        }
	                    }); 
	                  }   
	            }
	        });
	}
});


/*Open paypal Model*/
function paypalModel(){
	var csrf_token = $('input[name="_token"]').val();
	var planRadioValue = $("input[name='plan']:checked").val();
	if(planRadioValue != ''){
		var selectedPlan = $("input[name='plan']:checked").data("paypal");
		var selectedPlanCost = $("input[name='plan']:checked").data("cost");
		
		$("#plan_id").val(selectedPlan);
		$('#plan_id_popup').val(selectedPlan);
		$('#csrf_token_val').val(csrf_token);
		$('#plan_price_popup').val(selectedPlanCost);
		$("#payment_modal").modal('show');
	}else{
		/*Please select plan*/
		$('.plan_error').html('Please select Plan')
	}
}