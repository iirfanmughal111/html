/*Star Rating*/
if($(".my-rating").length > 0){
	$(".my-rating").starRating({
		totalStars: 5,
		callback: function(currentRating, $el){
			$('#rating').val(currentRating);
			//alert('rated ' +  currentRating);
		}
	});
}

if($(".rating-comment").length > 0){
	$(".rating-comment").starRating({
		totalStars: 5,
		starSize: 25,
		readOnly: true
	});
}

if($(".coache-rating").length > 0){
	$(".coache-rating").starRating({
		totalStars: 5,
		starSize: 25,
		readOnly: true
	});
}

if($(".index-coache-rating").length > 0){
	$(".index-coache-rating").starRating({
		totalStars: 5,
		starSize: 20,
		readOnly: true
	});
}

/*Submit Rating Form*/
$(document).on('click','.submit-rating', function(e) {
	e.preventDefault(); 
	$('.request_loader').css('display','inline-block');
	$('.errors').html('');
	var form = $('form#userRatingForm');
	var ajax_url = form.attr('action');
	var method = form.attr('method');
    $.ajax({
        type: method,
		dataType: 'json',
        url: ajax_url,
        data: form.serialize(),
        success: function(data) {
			//alert(data)
			$('.errors').html('');
			$('.request_loader').css('display','none');
			// If data inserted into DB
			 if(data.success){
			 	if(typeof (data.message) != "undefined" && data.message != "null"){
			 		notification('Success',data.message,'top-right','success',2000);
			 	}else{
			 		notification('Success','Rating Done Successfully','top-right','success',2000);
			 	}

			 	if(typeof (data.view) != "undefined" && data.view != null){
			 		$('.coache-rating-data').html(data.view);
			 	}
				
				setTimeout(function(){ 
					$('#add_rating_modal').modal('hide'); 
					$('.modal-backdrop').remove();  
					/*$('.add_rating_modal').find('.modal-header .close').trigger('click');*/
				}, 2000);
			}	 
        },
		error :function( data ) {
         if( data.status === 422 ) {
			$('.request_loader').css('display','none');
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
                	notification('Error','Something went wrong','top-right','error',2000);
                }
            }); 
          }
		}

    });
});

/*Chat now button check user subscribe*/
$(document).on('click','.coache_chat_now', function(e) {
	e.preventDefault();
	var coachId = $('#coacheid').val();
	var coachHash = $('#coachehash').val();
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	console.log(coachId);
	if(coachId != ''){
		$.ajax({
	        type: "POST",
			dataType: 'json',
	        url: base_url+'/chat-subscribe',
	        data: {_token:csrf_token,coache_id:coachId},
	        success: function(data) {
				 if(data.success){
				 	window.location = base_url+'/chat?c='+coachHash;
				 	/*if(typeof (data.message) != "undefined" && data.message != "null"){
				 		notification('Success',data.message,'top-right','success',2000);
				 	}else{
				 		notification('Success','Rating Done Successfully','top-right','success',2000);
				 	}*/
				}	 
	        },
			error :function( data ) {
	        	notification('Error','Something went wrong','top-right','error',2000);
			}
	    });
	}else{
		notification('Success','Something went wrong','top-right','success',2000);
	}
	
	
});
