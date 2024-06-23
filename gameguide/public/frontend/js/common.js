/* =======Notification Message =========
* position - top-right,top-left,bottom-left,bottom-right
* theme - success,info,warning,error,none
* showDuration - 4000 
==================================*/
function notification(title,message,positionClass,theme,showDuration){
	window.createNotification({
			closeOnClick: true,
			displayCloseButton: true,
			positionClass: 'nfc-'+positionClass,
			showDuration: showDuration,
			theme: theme
		})({
			title: title,
			message: message
		});
}

//Close Forgot modal and clear form.
$('.clear').click(function(){
	$('#forget_modal').modal('hide');
	$('#email').val('');
	$('.email_error ').html('');
})

// Shorten the text.
function shortemTxt(cls,showChars,moreText,lessText){
$("."+cls).shorten({
	"showChars" : showChars,
	"moreText"	: moreText,
	"lessText"	: lessText,
});
}


//Common water mark images 
var watermark_image = base_url+'/frontend/images/favicon.ico';




//USER SEARCH 

 $('#trigger').click(function() {
    $('#frmSearch').toggleClass('search-bar-expanded');
    $('#search-box').focus();
	
  });
 
/*  $('#search-box').blur(function() {
    $('#frmSearch').removeClass('search-bar-expanded');
  }); 
   */
$("#search-box").keyup(function(){
	
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
	    loader_img = base_url+'/frontend/images/LoaderIcon.gif';
		$.ajax({
		type: "POST",
		url: base_url+"/search-user",
		data:{username:$(this).val(),'_token':csrf_token},
		beforeSend: function(){
			$("#search-box").css("background","#FFF url("+loader_img+") no-repeat 98%");
		},
		success: function(data){
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#search-box").css("background","#FFF");
		}
		});
	});
	
//OPEN USER PROFLE 
function userProfile(url){
	window.location.href=url
}	