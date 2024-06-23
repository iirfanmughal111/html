/**********Delete Access Codes ***********/

$(document).on('click', '.used_access_code_delete' , function() {
	
	var access_code_id = $(this).data('id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: base_url+'/access-codes/list/delete/'+access_code_id,
        data: {_token:csrf_token,access_code_id:access_code_id},
        success: function(data) {
			if(data.success){
				notification('Success','Used Access Code deleted Successfully','top-right','success',2000);
				$('used_table').html(data.view);
				window.location.reload(); 
			
			}else if(data.message){
				notification('Error',data.message,'top-right','error',4000);
			}else{	
				notification('Error','Something went wrong.','top-right','error',3000);
			}	
        },
    });
});
