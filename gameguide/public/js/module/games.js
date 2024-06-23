/**********Delete Game ***********/

$(document).on('click', '.delete_game' , function() {
	var game_id = $(this).data('id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/games/delete_game/'+game_id,
        data: {_token:csrf_token,game_id:game_id},
        success: function(data) {
			if(data.success){
				notification('Success','Game deleted Successfully','top-right','success',2000);
				if(typeof (data.view) != 'undefined' && data.view != null && data.view != ''){
					$('.games_full').html(data.view);
				}else{
					$('.user_row_'+game_id).hide();
				}
			}else if(data.message){
				notification('Error',data.message,'top-right','error',4000);
			}else{	
				notification('Error','Something went wrong.','top-right','error',3000);
			}	
        },
    });
});

/*==============================================
	ENABLE/DISABLE USER ACCOUNT 
============================================*/
$(document).on('click','.switch_status', function(e) {
	
	if($(this).is(":checked")){
		var game_status = 1;
	}
	else if($(this).is(":not(:checked)")){
		var game_status = 0;
	}
	var game_id = $(this).attr('data-game_id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/game/enable-disable',
        data: {status:game_status,game_id:game_id,_token:csrf_token},
        success: function(data) {
             // IF TRUE THEN SHOW SUCCESS MESSAGE  
			 if(data.success){
				notification('Success','Game has been active.','top-right','success',4000);
				
			}else{
             notification('Error','Game has been deactivate.','top-right','error',4000);
			}	
			
        },
		error :function( data ) {}
    });
	
});

/*==============================================
	SEARCH FILTER FORM 
============================================*/
$(document).on('submit','#searchForm', function(e) {
    e.preventDefault(); 
	$('.search_spinloder').css('display','inline-block');
	$this = $(this);
	var ajax_url = $this.attr('action');
	var method = $this.attr('method');
    $.ajax({
        type: method,
		//dataType: 'json',
        url: ajax_url,
        data: $(this).serialize(),
        success: function(data) {
			 $('.search_spinloder').css('display','none');
             $("#tag_container").empty().html(data);	
        },
		error :function( data ) {}
    });
});

/**************** File Preview *******************************/
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
    	$('.new-file .module_image').attr('src', e.target.result);
    	$('.new-file').removeClass('d-none').show();
    	$('.old-file').hide();
      	//$('#blah').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$(document).ready(function(){

	$("#fileupload").change(function() {
	  	readURL(this);
	});
});

/************************* Remove New category image  ********************************/
$(document).on('click','.new-module-file-trash',function(){
	if($('.old-file').length > 0){
		$('.old-file').show();
	}
	$("#fileupload").val('');
	$('.new-file').addClass('d-none').hide();
});

/**************** Delete Original Category Media Image   *************/

$(document).on('click','#delete_game_image',function(){
	var game_id = $(this).data('id');
	if(game_id != ''){
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: base_url+'/game_image/delete/'+game_id,
			data: {_token:csrf_token,game_id:game_id},
			success: function(data) {
				if(data.success){
					if(typeof (data.message) != 'undefined' && data.message != null)
						notification('Success',data.message,'top-right','success',2000);
					else
						notification('Success','Game Image deleted Successfully','top-right','success',2000);
					
					$('.old-file').remove();
				}else if(data.message){
					notification('Error',data.message,'top-right','error',4000);
				}else{	
					notification('Error','Something went wrong.','top-right','error',3000);
				}	
			},
		});
	}else{
		notification('Error','Something went wrong.','top-right','error',3000);
	}
});
