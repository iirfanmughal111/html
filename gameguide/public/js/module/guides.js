/*Add More field in guides form
var max_fields      = 10; //maximum input boxes allowed*/
var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
var add_field_key = $(".add_field_key"); //Add button ID

var transcript_wrapper   		= $(".input_fields_trnscript_wrap"); //Fields wrapper
var add_field_transcript = $(".add_field_transcript"); //Add button ID

/*var x = 1; //initlal text box count*/
$(add_field_key).click(function(e){ //on add input button click
	e.preventDefault();
	/*if(x < max_fields){ //max input box allowed
		x++; //text box increment*/
		$(wrapper).append('<div class="row new_keynote"><div class="col-md-8 col-xs-8 field mb-4"><input type="text" name="mykey[]" class="key_input form-control"></div><div class="col-md-4 col-xs-4 field mb-4"><a href="#" class="remove_field">Remove</a></div></div>'); //add input box
	/*}*/
});

$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
	e.preventDefault(); $(this).parents('div.new_keynote').remove(); /*x--;*/
});

$(add_field_transcript).click(function(e){ //on add input button click
	e.preventDefault();
	$(transcript_wrapper).append('<div class="row new_transcript"><div class="col-md-5 col-xs-5 field mb-4"><input type="text" name="transcript[duration][]" class="transcript_input form-control" placeholder="Duration"></div><div class="col-md-5 col-xs-5 field mb-4"><input type="text" name="transcript[content][]" class="transcript_input form-control" placeholder="Transcript"></div><div class="col-md-2 col-xs-2 field mb-4"><a href="#" class="transcript_remove_field">Remove</a></div></div>'); //add input box
});

$(transcript_wrapper).on("click",".transcript_remove_field", function(e){ //user click on remove text
	e.preventDefault(); $(this).parents('div.new_transcript').remove(); /*x--;*/
});


/**********Delete Game ***********/

$(document).on('click', '.delete_guide' , function() {
	var guide_id = $(this).data('id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/game-guides/delete_game/'+guide_id,
        data: {_token:csrf_token,guide_id:guide_id},
        success: function(data) {
			if(data.success){
				notification('Success','Game Guide deleted Successfully','top-right','success',2000);
				if(typeof (data.view) != 'undefined' && data.view != null && data.view != ''){
					$('.game_guides_full').html(data.view);
				}else{
					$('.user_row_'+guide_id).hide();
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
	var guide_id = $(this).attr('data-guide_id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/game-guides/enable-disable',
        data: {status:game_status,guide_id:guide_id,_token:csrf_token},
        success: function(data) {
             // IF TRUE THEN SHOW SUCCESS MESSAGE  
			 if(data.success){
				notification('Success','Game Guide has been active.','top-right','success',4000);
				
			}else{
             notification('Error','Game Guide has been deactivate.','top-right','error',4000);
			}	
			
        },
		error :function( data ) {}
    });
	
})

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

/************************* Remove New category image  ***********************/
$(document).on('click','.new-module-file-trash',function(){
	if($('.old-file').length > 0){
		$('.old-file').show();
	}
	$("#fileupload").val('');
	$('.new-file').addClass('d-none').hide();
});

/**************** Delete Original Category Media Image   *************/

$(document).on('click','#delete_game_guide_image',function(){
	var game_id = $(this).data('id');
	if(game_id != ''){
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: base_url+'/game-guide/image_delete/'+game_id,
			data: {_token:csrf_token,game_id:game_id},
			success: function(data) {
				if(data.success){
					if(typeof (data.message) != 'undefined' && data.message != null)
						notification('Success',data.message,'top-right','success',2000);
					else
						notification('Success','Game Guide Image deleted Successfully','top-right','success',2000);
					
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


