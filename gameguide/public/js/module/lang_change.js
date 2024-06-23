
$('#language').on('change', function(e) {
	//document.getElementById("language-form").submit();
	//form.submit();
	// var csrf_token = $('meta[name="csrf-token"]').attr('content');	
	//  $.ajax({
    //     type: "Post",
	// 	dataType: 'json',
    //     url: window.location.origin+'/lang-switch',
    //     data: {_token:csrf_token,language:$('#email').val()},
    //     success: function(data) {
	// 		if (data){
	// 			location.reload();
	// 		}
	// 		console.log('Language switching problem!!!');
	// 		//console.log(data);

	
    //     },
	// 	error: function(xhr, status, error) {
	// 		console.log('Language switching problem!!!');
			

    //     }
       
    // });



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