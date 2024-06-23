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

if($(".coache-rating").length > 0){
    $(".coache-rating").starRating({
        totalStars: 5,
        starSize: 20,
        readOnly: true
    });
}

var role_id = $('#user_role_id').val();

var resize = $('#upload-demo').croppie({
    enableExif: true,
    enableOrientation: true, 
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 280,
        height: 280,
        type: 'circle' //square
    },
    boundary: {
        width: 300,
        height: 300
    }   
});

var coache_resize = $('#upload-coache-demo').croppie({
    enableExif: true,
    enableOrientation: true, 
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 500,
        height: 420,
        type: 'square'
    },
    boundary: {
        width: 510,
        height: 450
    }   
}); 

/*var oldProfileImage = $('#old_profile_image').val();
//Means old image exist
if(oldProfileImage != ''){
      resize.croppie('bind',{
        url: oldProfileImage
      }).then(function(){
        //console.log('jQuery bind complete');
      });

    if($('#upload-coache-demo').length > 0){
        coache_resize.croppie('bind',{
            url: oldProfileImage
            }).then(function(){
                //console.log('jQuery bind complete');
        });
    }
}*/


  
$('#upload_profile_file').on('change', function () { 
  var reader = new FileReader();
    reader.onload = function (e) {
      resize.croppie('bind',{
        url: e.target.result
      }).then(function(){
        //console.log('jQuery bind complete');
      });

    if($('#upload-coache-demo').length > 0){
        coache_resize.croppie('bind',{
            url: e.target.result
            }).then(function(){
            //console.log('jQuery bind complete');
        });
    }

    }
    reader.readAsDataURL(this.files[0]);
});
$('.upload-image').on('click', function (ev) {
    var coache_img = '';
    if($('#upload-coache-demo').length > 0){
        coache_resize.croppie('result', {
            type: 'canvas',
            size: 'viewport',
            quality:1
        }).then(function (cimg) {
            coache_img = cimg;
        });
    }
  resize.croppie('result', {
    type: 'canvas',
    size: 'viewport',
    quality:1
  }).then(function (img) {
    $('.loader_image_profile').css('display','inline-block');
    $('.error').html('');

    var file_data = $("#upload_profile_file").prop("files")[0]; 
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var form_data = new FormData(); 
    form_data.append("upload_profile_file", file_data);
    form_data.append("upload_profile_crop_file", img);
    form_data.append("_token", csrf_token);

    if(coache_img != ''){
        form_data.append("upload_coache_crop_file", coache_img);
    }

	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/upload_profile_photo',
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
			$('.error').html('');
			$('.loader_image_profile').css('display','none');
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
			$('.loader_image_profile').css('display','none');
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

/*Banner Resize Upload*/
/*alert($('.profile-banner').outerWidth()+' ---- '+$('.profile-banner').outerHeight()+' -- '+Number($('.profile-banner').width()) + Number(100));*/
var bannerWidth = $('.profile-banner').outerWidth() - Number(30);
var bannerHeight = $('.profile-banner').outerHeight();
var bannerBoundaryWidth = Number(bannerWidth) + Number(30);
var bannerBoundaryHeight = Number(bannerHeight) + Number(30);
/*console.log(bannerWidth+'--'+bannerHeight+'--'+bannerBoundaryWidth+'--'+bannerBoundaryHeight);*/
var banner_resize = $('#upload-banner-demo').croppie({
        enableExif: true,
        enableOrientation: true, 
        viewport: { // Default { width: 100, height: 100, type: 'square' } 
            width: bannerWidth,
            height: bannerHeight,
            type: 'square'
        },
        boundary: {
            width: bannerBoundaryWidth,
            height: bannerBoundaryHeight
        }
    });

$('#upload_banner_file').on('change', function () { 
  var reader = new FileReader();
    reader.onload = function (e) {
      banner_resize.croppie('bind',{
        url: e.target.result
      }).then(function(){
        //console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
}); 

/*==============================================
     UPLOAD BANNER PHOTO  
============================================*/
  $(document).on('click','#upload_banner', function(e) {
    banner_resize.croppie('result', {
        type: 'canvas',
        size: 'viewport',
        quality:1
    }).then(function (img) {
    
    //$("#upload-success").html("Images cropped and uploaded successfully.");
    //$("#upload-success").show();
        var file_data = $("#upload_banner_file").prop("files")[0]; 
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var form_data = new FormData(); 
        form_data.append("upload_banner_file", file_data);
        form_data.append("upload_banner_crop_file", img);
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
                    $('.profile-banner').css('background-image',Image_url);
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
});

/****************** Edit name ********************************/
$(document).on('click','.edit-name',function(){
/*Display none*/
$('#edit_profile_name').show();
$('.modal-content').show();
});

$(document).on('click','.save_name_profile',function(e){
e.preventDefault();

$('.request_loader').css('display','inline-block');
$('.errors').html('');

var form = $('form#edit_profile_name');
// var form = $(this).parents('#edit_profile_name');

var first_name = form.find('#first_name').val();
var last_name = form.find('#last_name').val();
var email = form.find('#email').val();

if(first_name.trim() != '' && last_name.trim() != '' && email.trim() != ''){
$('.loader_profile').css('display','inline-block');
var ajax_url = form.attr('action');
var method = form.attr('method');
$.ajax({
type: method,
dataType: 'json',
url: ajax_url,
data: form.serialize(),
success: function(data) {
$('.errors').html('');
$('.request_loader').css('display','none');
if(data.success){
// $('.loader_profile').css('display','none');
notification('Success','Profile Updated Successfully','top-right','success',1500);

location.reload(); 
	
	
// $('.profile-info').html(data.data);
setTimeout(function(){
$('#edit_profile_name').hide();
$('.modal-content').hide();
$('.modal-backdrop').remove();
/*$('.add_rating_modal').find('.modal-header .close').trigger('click');*/
}, 500);
}
// notification('Error','Please fill all the fields.','top-right','error',4000);
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
}
});

/****************Edit Tag Line *******************/

$(document).on('click','.edit-tag-line',function(){
    /*Display none*/
    $('.section_tag_line').hide();
    $('#edit_profile_tag').show();
});


$(document).on('click','.save_tag_line',function(e){
    e.preventDefault();

    var form = $(this).parents('#edit_profile_tag');
    var tag_line = form.find('#tag_line').val();
    if(tag_line.trim() != ''){
        $('.loader_tagline').css('display','inline-block');
        var ajax_url = form.attr('action');
        var method = form.attr('method');
        $.ajax({
            type: method,
            url: ajax_url,
            data: form.serialize(),
            success: function(data) {
                 if(data.success){
                    $('.loader_tagline').css('display','none');
                    notification('Success','Profile Updated Successfully','top-right','success',2000);
                    
                   $('.tag_line_outer').html(data.data);

                    $('.section_tag_line').show();
                    $('#edit_profile_tag').hide();

                }
            },
            error :function( data ) {
                
                if( data.status === 422 ) {
                    $('.loader_tagline').css('display','none');
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
        notification('Error','Tag Line not be empty','top-right','error',2000);
    }
    
});

/*************** Detect when click on outside Tag Form   *************************/


$(document).mouseup(function(e) 
{
    var container = $(".tag_line_outer");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        if($("#edit_profile_tag").is(":visible")){
            $('.save_tag_line').trigger('click');
            event.preventDefault();
        }
    }

    var name_container = $("#edit_profile_name");

    // if the target of the click isn't the container nor a descendant of the container
    if (!name_container.is(e.target) && name_container.has(e.target).length === 0) 
    {
        if($("#edit_profile_name").is(":visible")){
            $('.save_name_profile').trigger('click');
            event.preventDefault();
        }
    }
});

/****************** Update Edit Socail Link  ****************************/

$(document).on('click','.submit-social-link', function(e) {
    e.preventDefault();
    $('.request_loader').css('display','inline-block');
    $('.errors').html('');
    var form = $('form#social-link-Form');
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
                    notification('Success','Successfully Edit links','top-right','success',2000);
                }
                
                setTimeout(function(){ 
                    $('#edit_social_links_modal').modal('hide'); 
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

/******************Update Description *****************************/

$(document).on('click','.submit-description', function(e) {
    e.preventDefault();
    $('.request_loader').css('display','inline-block');
    $('.errors').html('');
    var form = $('form#userDescriptionForm');
    var formData = form.serializeArray();

    

    var ajax_url = form.attr('action');
    var method = form.attr('method');
    if( $('.cke_editor_user_description').length > 0){
            for (instance in CKEDITOR.instances) {
                //console.log(instance)
                //console.log(CKEDITOR.instances[instance].getData());
                formData.push({ name: instance, value: CKEDITOR.instances[instance].getData() });
                //$('#' + instance).val(CKEDITOR.instances[instance].getData());
            }
    }
    $.ajax({
        type: method,
        dataType: 'json',
        url: ajax_url,
        data: formData,
        success: function(data) {
            //alert(data)
            $('.errors').html('');
            $('.request_loader').css('display','none');
            // If data inserted into DB
             if(data.success){
                if(typeof (data.message) != "undefined" && data.message != "null"){
                    notification('Success',data.message,'top-right','success',2000);
                }else{
                    notification('Success','Successfully Edit links','top-right','success',2000);
                }
                
                setTimeout(function(){ 
                    $('#edit_description_modal').modal('hide'); 
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
