// Testing
var UNIX_timestamp = 1669981500;
function dateConverter(UNIX_timestamp){
	var wStart_time = new Date(UNIX_timestamp * 1000).toLocaleString('en-GB', {
		hour12: false,
		timeZone:'Europe/London',
		timeStyle:'short',
	  });
	  var humanDate = new Date(UNIX_timestamp * 1000);
	  
	var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	var year = humanDate.getFullYear();
	var month = months[humanDate.getMonth()];
	var date = humanDate.getDate();
	var hour = humanDate.getHours();
	var min = humanDate.getMinutes();
	var sec = humanDate.getSeconds();
	// var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
	var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;

	return time;
  }
//   alert(dateConverter(UNIX_timestamp));

// TestingEnd


/*Add More field in webinar form
var max_fields      = 10; //maximum input boxes allowed*/
var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
var add_field_key = $(".add_field_key"); //Add button ID

var notification_wrapper   		= $(".notification_fields_wrap"); //Fields wrapper
var add_field_notification = $(".add_field_notification"); //Add button ID

/*var x = 1; //initlal text box count*/
$(add_field_key).click(function(e){ //on add input button click
	e.preventDefault();

	/*if(x < max_fields){ //max input box allowed
		x++; //text box increment*/
		$(wrapper).append('<div class="row new_keynote"><div class="col-md-8 col-xs-8 field mb-4"><input type="text" name="webinarkey[]" class="key_input form-control"></div><div class="col-md-4 col-xs-4 field mb-4"><a href="#" class="remove_field">Remove</a></div></div>'); //add input box
	/*}*/
});

$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
	e.preventDefault(); $(this).parents('div.new_keynote').remove(); /*x--;*/
});

// Notifications Managment


$(add_field_notification).click(function(e){ //on add input button click
	e.preventDefault();


		$(notification_wrapper).append( '<div class="row new_notification"><div class="col-md-8 col-xs-8 field mb-4"><div class="row my-3"><div class="col"><label for="notificationdate">Notification Date</label><input type="date" class="form-control notification-date" name="notificationdate[]"></div><div class="col"><label for="notificationtime">Notification Time</label><input type="time" class="form-control notification-time" name="notificationtime[]"></div></div></div><div class="col-md-4 col-xs-4 field mb-4"><a href="#" class="remove_notification mt-5">Remove</a></div></div>'); 
});

$(notification_wrapper).on("click",".remove_notification", function(e){ //user click on remove text
	e.preventDefault(); $(this).parents('div.new_notification').remove(); /*x--;*/
});



// DateValidation

const today = new Date();




// 👇️ 1/27/2022, 13:18:22


// $('#start-date').on('change', function(e) {
// 	var start_date = this.value;
// 		$('.notification-date').attr('max',start_date);
// 		$('#end-date').attr('min',start_date);

// });

$('#start-time, #start-date').on('change', function(e) {
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	var today_date = yyyy + '-' + mm + '-' + dd;
	var time_now = today.toLocaleString('en-GB', {
		hour12: false,
		timeZone:'Europe/London',
		timeStyle:'short',
	  });

	// var time_now = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	

	var start_time =$('#start-time').val();
	var start_date =$('#start-date').val();
	$('.notification-date').attr('max',start_date);
	$('.notification-date').attr('min',today_date);

	
	$('#end-date').attr('min',start_date);


	if (start_date==today_date ){
	$('#end-time').attr('min',start_time);
	$('#start-time').attr('min',time_now);
	$('.notification-time').attr('min',time_now);
	$('.notification-time').attr('max',start_time);

	// alert(start_time + time_now);


	}
	else{
		// $('.notification-time').attr('max',start_time);
		$('#end-time').removeAttr('min');
		$('#start-time').removeAttr('min');
		$('.notification-time').removeAttr('min');
		$('.notification-time').removeAttr('max');

		// $('.notification-time').attr('max',start_time);



	}
	$('#end-time').val(start_time);

});	

// ForUpdatingWEbinarLimitedValidations
$('#update-start-time, #update-start-date').on('change', function(e) {
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	var today_date = yyyy + '-' + mm + '-' + dd;
	var time_now = today.toLocaleString('en-GB', {
		hour12: false,
		timeZone:'Europe/London',
		timeStyle:'short',
	  });

	// var time_now = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	

	var update_start_time =$('#update-start-time').val();
	var update_start_date =$('#update-start-date').val();
	$('.notification-date').attr('max',update_start_date);
	// $('.notification-date').attr('min',today_date);

	
	$('#update-end-date').attr('min',update_start_date);


	if (update_start_date==today_date){
	$('#update-end-time').attr('min',update_start_time);
	// $('#start-time').attr('min',time_now);
	// $('.notification-time').attr('min',time_now);
	// $('.notification-time').attr('max',update_start_time);



	// alert(update_start_time + time_now);


	}
	else{
		// $('.notification-time').attr('max',update_start_time);
		// $('#update-end-time').removeAttr('min');
		$('#update-start-time').removeAttr('min');
		$('.notification-time').removeAttr('min');
		$('.notification-time').removeAttr('max');

		// $('.notification-time').attr('max',update_start_time);



	}
	$('#update-end-time').val(start_time);

});	



$('#notification-btn').on('click', function(e) {
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	var today_date = yyyy + '-' + mm + '-' + dd;

	// var time_now = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	var time_now = today.toLocaleString('en-GB', {
		hour12: false,
		timeZone:'Europe/London',
		timeStyle:'short',
	  });

		var start_date = $('#start-date').val();
		var start_time = $('#start-time').val();

		$('.notification-date').attr('max',start_date);
		$('.notification-date').attr('min',today_date);
			if (start_date==today_date){
			$('.notification-time').attr('min',time_now);
			$('.notification-time').attr('max',start_time);

			

			}
			else{
				// $('#end-time').removeAttr('min');
				// $('#start-time').removeAttr('min');
				$('.notification-time').removeAttr('min');
				$('.notification-time').removeAttr('max');


			}


});


// UserSideJs
var button = document.getElementById("nextscrollbtn");
button.onclick = function() {
    var container = document.getElementById('logos-scroll');
    sideScroll(container, 'right', 25, 100, 10);
};

var back = document.getElementById('prescrollbtn');
back.onclick = function() {
    var container = document.getElementById('logos-scroll');
    sideScroll(container, 'left', 25, 100, 10);
};

function sideScroll(element, direction, speed, distance, step) {
    scrollAmount = 0;
    var slideTimer = setInterval(function() {
        if (direction == 'left') {
            element.scrollLeft -= step;
        } else {
            element.scrollLeft += step;
        }
        scrollAmount += step;
        if (scrollAmount >= distance) {
            window.clearInterval(slideTimer);
        }
    }, speed);
}
base_url = window.location.origin;

// UsersFunctions
// function getUsers(webinar_id){
// var output = "";
// // var webinar_id =webinar_id;
// 	// var guide_id = $(this).data('id');
// 	var csrf_token = $('meta[name="csrf-token"]').attr('content');
c


// }


base_url = window.location.origin;


// delete Webinar
$(document).on('click', '.delete_webinar' , function() {

	var webinar_id = $(this).data('id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	// setHeader('Access-Control-Allow-Origin', 'http://127.0.0.1:8000');



	 $.ajax({
        type: "Post",
		dataType: 'json',
        url: base_url+'admin/webinar/delete_webinar/'+webinar_id,
        data: {_token:csrf_token,'webinar_id':webinar_id},
		contentType: "application/json",
        success: function(data) {	
			console.log('success');
			if(data.success){


				notification('Success','Webinar deleted Successfully','top-right','success',2000);
				if(typeof (data.view) != 'undefined' && data.view != null && data.view != ''){
					$('.webinar_full').html(data.view);

				}else{
					$('.webinar_row_'+webinar_id).hide();
	console.log('hide');

				}
			}else if(data.message){
	console.log('error');

				notification('Error',data.message,'top-right','error',4000);
			}else{
	console.log('error2');

				notification('Error','Something went wrong.','top-right','error',3000);
			}	
        },
        error: function(data) {
	console.log('error function');
	var val = data.responseText;
        console.log("error "+val);
			
		}

		
    });
});

$(document).on('click','.switch_status', function(e) {
	
	if($(this).is(":checked")){
		var webinar_status = 1;
	}
	else if($(this).is(":not(:checked)")){
		var webinar_status = 0;
	}
	var webinar_id = $(this).attr('data-webinar_id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	var mydata = {'status':webinar_status,'webinar_id':webinar_id,'_token':csrf_token};
	console.log(webinar_id);
	$.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'admin/webinar/enable-disable',
        data: mydata,
        success: function(data) {
             // IF TRUE THEN SHOW SUCCESS MESSAGE  
			 if(data.success){
				notification('Success','Webinar has been enabled.','top-right','success',4000);
				
			}else{
             notification('Error','Webinar has been disabled.','top-right','error',4000);
			}	
			
        },
		error :function( data ) {
			console.log('Error'+data);
		}
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