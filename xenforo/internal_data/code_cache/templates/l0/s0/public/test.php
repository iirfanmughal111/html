<?php
// FROM HASH: edfb83b8ff3cb7a1aac7fd6f4dbee6d0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'test

';
	$__templater->inlineJs('

	 var timeOutID;
	 timeOutID = setInterval(checkVideoEncoding, 10000);
    function checkVideoEncoding() {

			jQuery.ajax({
			type: "Get",
			url: "' . $__templater->func('link', array('upgrade/save', ), false) . '",
			dataType:"json",
			success: function(content){
				if (content.data.status){
					refreshPage()
				}
			console.log(content.data);
			},
			error: function(xhr){

			console.log(xhr.status + " " + xhr.statusText);
			}
		});
    }
	  function refreshPage() {
        location.reload();
    }
	
');
	$__finalCompiled .= '
' . $__templater->func('csrf_input');
	return $__finalCompiled;
}
);