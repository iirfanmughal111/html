<xf:js>
	$(document).ready(function() {
		var n = $('#DC_LinkProxy_AutoRedirection__timer').html() - 1;
		setInterval(function() {
			if (n >= -1) { 
				$('#DC_LinkProxy_AutoRedirection__timer').html(n--); 
			}
			if ( n == -1 ) {
				$('#continue_btn').removeClass('d-none');
			//	window.location.replace("{$url}");
	
			}
		}, 1000);
	
	
	});
</xf:js>