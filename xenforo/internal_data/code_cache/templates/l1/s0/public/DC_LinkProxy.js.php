<?php
// FROM HASH: a43a97e7aeb5e29fa12bc2254f2689a7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->inlineJs('
	$(document).ready(function() {
		var n = $(\'#DC_LinkProxy_AutoRedirection__timer\').html() - 1;
		setInterval(function() {
			if (n >= -1) { 
				$(\'#DC_LinkProxy_AutoRedirection__timer\').html(n--); 
			}
			if ( n == -1 ) {
				$(\'#continue_btn\').removeClass(\'d-none\');
			//	window.location.replace("' . $__vars['url'] . '");
	
			}
		}, 1000);
	
	
	});
');
	return $__finalCompiled;
}
);