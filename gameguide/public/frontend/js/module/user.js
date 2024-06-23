/*==============================================
	FORGOT PASSWORD 
============================================*/
$(document).on('click', '#sendForget' , function() {
	var csrf_token = $('input[name="_token"]').val();
	$('.request_loader').css('display','inline-block');
	var email = $('#email').val();
	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/send_reset_link',
        data: {_token:csrf_token,email:email},
        success: function(data) {
			if(data.success){
				$('.request_loader').css('display','none');
				notification('Success',data.message,'top-right','success',3000);
				$('#email').val('');
				$('#forget_modal').modal('hide');
				
			}else{
				
				notification('Error',data.message,'top-right','error',3000);
				$('.email_error').html('');
			}	
        },
		error :function( data ) {
         if( data.status === 422 ) {
			$('.request_loader').css('display','none');
			$('.email_error').html('');
			//notification('Error','Please fill all the fields.','top-right','error',4000);
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (key, value) {
                // console.log(key+ " " +value);
                if($.isPlainObject(value)) {
                    $.each(value, function (key, value) {                       
                       // alert(key+ " " +value);	
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
})
	
/*==============================================
	CONTACT  REQUEST FORM 
============================================*/
$(document).on('click','.contact_us', function(e) {
	$('.contact_form').submit();
});
	
	
	
	
/*==============================================
	UPDATE USER PROFILE 
============================================*/
 $(document).on('click','.update_profile', function(e) {
    e.preventDefault(); 
	//var user_id = $('#user_id').val();
	$('.request_loader').css('display','inline-block');
	var csrf_token = $('input[name="_token"]').val();
	var first_name = $('#first_name').val();
	var last_name = $('#last_name').val();
	var username = $('#username').val();
	var user_bio = $('#user_bio').val();
    $.ajax({
        type: "POST",
		dataType: 'json',
         url: base_url+'/update-profile',
        data: {_token:csrf_token,first_name:first_name,last_name:last_name,username:username,user_bio:user_bio},
        success: function(data) {
			$('.error').html('');
			//$('.request_loader').css('display','none');
			 if(data.success){
				notification('Success','User Updated Successfully','top-right','success',2000);
			}else{
				notification('Error','Username not available.','top-right','error',2000);
			}	 
        },
		error :function( data ) {
         if( data.status === 422 ) {
			//$('.request_loader').css('display','none');
			$('.error').html('');
			//notification('Error','Please fill all the fields.','top-right','error',4000);
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (key, value) {
                if($.isPlainObject(value)) {
                    $.each(value, function (key, value) {                       
                        //console.log(key+ " " +value);	
					  var key = key.replace('.','_');
					  $('.'+key+'_error').show().append(value);
                    });
                }
            }); 
          }
		}

    });
});	


/*==============================================
	UPDATE PAYPAL SETTING
============================================*/
 $(document).on('click','.update_paypal', function(e) {
    e.preventDefault(); 
	//var user_id = $('#user_id').val();
	$('.paypal_loader').css('display','inline-block');
	var csrf_token = $('input[name="_token"]').val();
	var paypal_id = $('#paypal_id').val();
    $.ajax({
        type: "POST",
		dataType: 'json',
         url: base_url+'/update_paypal_setting',
        data: {_token:csrf_token,paypal_id:paypal_id},
        success: function(data) {
			$('.error').html('');
			$('.paypal_loader').css('display','none');
			 if(data.success){
				notification('Success','Setting Updated Successfully','top-right','success',2000);
			}else{
				notification('Error','Username not available.','top-right','error',2000);
			}	 
        },
		error :function( data ) {
         if( data.status === 422 ) {
			$('.paypal_loader').css('display','none');
			$('.error').html('');
			//notification('Error','Please fill all the fields.','top-right','error',4000);
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (key, value) {
                if($.isPlainObject(value)) {
                    $.each(value, function (key, value) {                       
                        //console.log(key+ " " +value);	
					  var key = key.replace('.','_');
					  $('.'+key+'_error').show().append(value);
                    });
                }
            }); 
          }
		}

    });
});	



	
/*==============================================
	CHANGE USER PASSWORD  
============================================*/
 $(document).on('click','.update_password', function(e) {
    e.preventDefault(); 
	//var user_id = $('#user_id').val();
	$('.request_loader').css('display','inline-block');
	var csrf_token = $('input[name="_token"]').val();
	var old_password = $('input[name="old_password"]').val();
	var password = $('input[name="password"]').val();
	var confirm_password = $('input[name="confirm_password"]').val();
    $.ajax({
        type: "POST",
		dataType: 'json',
         url: base_url+'/changepassword',
        data: {_token:csrf_token,old_password:old_password,password:password,confirm_password:confirm_password},
        success: function(data) {
			$('.error').html('');
			//$('.request_loader').css('display','none');
			 if(data.success){
				notification('Success','User Updated Successfully','top-right','success',2000);
			}else{
				notification('Error',data.errors.old_password,'top-right','error',2000);
			}	 
        },
		error :function( data ) {
         if( data.status === 422 ) {
			//$('.request_loader').css('display','none');
			$('.error').html('');
			//notification('Error','Please fill all the fields.','top-right','error',4000);
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (key, value) {
                if($.isPlainObject(value)) {
                    $.each(value, function (key, value) {                       
                        //console.log(key+ " " +value);	
					  var key = key.replace('.','_');
					  $('.'+key+'_error').show().append(value);
                    });
                }
            }); 
          }
		}

    });
});	



// Validation File Upload 
function validate(file) {
    var ext = file.split(".");
    ext = ext[ext.length-1].toLowerCase();      
    var arrayExtensions = ["jpg" , "jpeg", "png", "gif"];

    if (arrayExtensions.lastIndexOf(ext) == -1) {
		msg="The upload profile file must be a file of type: jpeg, jpg, png, gif, jpg";
		$('.upload_profile_file_error').html(msg);
		$('.upload-image').attr('disabled','disabled');
       // alert("Wrong extension type.");
        $("#upload_profile_file").val("");
    }else{
		$('.error').html('');
		$('.upload-image').removeAttr('disabled');
	}
}

 var resize = $('#upload-demo').croppie({
    enableExif: true,
    enableOrientation: true,    
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 100,
        height: 100,
        type: 'circle' //square
    },
    boundary: {
        width: 200,
        height: 200
    }
}); 
$('#upload_profile_file').on('change', function () { 
  var reader = new FileReader();
    reader.onload = function (e) {
      resize.croppie('bind',{
        url: e.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
});
$('.upload-image').on('click', function (ev) {
  resize.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function (img) {
    html = '<img src="' + img + '" />';
    $("#preview-crop-image").html(html);
    //$("#upload-success").html("Images cropped and uploaded successfully.");
    //$("#upload-success").show();
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/upload_profile_photo',
        data: {_token:csrf_token,upload_profile_file:img},
        success: function(data) {
			$('.error').html('');
			$('.loader_photo').css('display','none');
			 if(data.success){
				 $('.profile_photo').removeClass('thumb');
				var Image_box = "<div id='profile-upload'><img class='profile_photo_change' src='"+data.image_url+"'> <i class='fa fa-camera'></i></div>";
				$('.show_image').html(Image_box); //set images in circle left sidebar 
				
				var header_profile_img = '<img src="'+data.image_url+'" alt="image">';
				$('.nav-profile-img').html(header_profile_img); //replace header images
				$('.upload_photo_modal').modal('hide');  //hide modal  
				$('.upload_photo_modal').trigger('click');
				notification('Success','Profile Photo Uploaded Successfully','top-right','success',2000);
			}else{
				notification('Error','Somthing went wrong.','top-right','error',2000);
			}	 
        },
		error :function( data ) {
         if( data.status === 422 ) {
			$('.loader_photo').css('display','none');
			$('.error').html('');
			//notification('Error','Please fill all the fields.','top-right','error',4000);
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (key, value) {
                if($.isPlainObject(value)) {
                    $.each(value, function (key, value) {                       
                        //console.log(key+ " " +value);	
					  var key = key.replace('.','_');
					 $('.'+key+'_error').show().append(value);
                    });
                }
            }); 
          }
		}

    });
	
    
  });
});

/*==============================================
	 UPLOAD BANNER PHOTO  
============================================*/
  $(document).on('click','#upload_banner', function(e) {
  
    var file_data = $("#upload_banner_file").prop("files")[0]; 
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var form_data = new FormData(); 
    form_data.append("upload_banner_file", file_data);
    form_data.append("_token", csrf_token);
	

	$('.loader_banner').css('display','inline-block');
    $.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/upload_banner_photo',
        data: form_data,
		contentType: false,
		cache: false,
		processData: false,
        success: function(data) {
			$('.error').html('');
			$('.loader_banner').css('display','none');
			 if(data.success){
				$("#upload_banner_form")[0].reset();
				var Image_url= 'url('+data.image_url+')';
				$('.banner_photo').css('background-image',Image_url);
				$('.upload_banner_modal').modal('hide');
				$('.upload_banner_modal').trigger('click');
				notification('Success','Profile Photo Uploaded Successfully','top-right','success',2000);
			}else{
				notification('Error','Somthing went wrong.','top-right','error',2000);
			}	 
        },
		error :function( data ) {
         if( data.status === 422 ) {
			$('.loader_banner').css('display','none');
			$('.error').html('');
			//notification('Error','Please fill all the fields.','top-right','error',4000);
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (key, value) {
                if($.isPlainObject(value)) {
                    $.each(value, function (key, value) {                       
                        //console.log(key+ " " +value);	
					  var key = key.replace('.','_');
					 $('.'+key+'_error').show().append(value);
                    });
                }
            }); 
          }
		}

    });
});	

function social_login_popup(url) {
	newwindow=window.open(url,'name','height=500,width=600');
	if (window.focus) {newwindow.focus()}
	return false;
}

