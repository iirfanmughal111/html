<?php
// FROM HASH: 6e49d019e674ce5b2d6c99fa66d0f6b3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->inlineJs('
	$(document).on(\'click\', \'.thpostcomments_commentLink\', function(event) {
		event.preventDefault();
		$(this).closest(\'.message\').toggleClass(\'thpostcomments_message--expanded\');
	});
');
	return $__finalCompiled;
}
);