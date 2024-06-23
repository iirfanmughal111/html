<?php
// FROM HASH: e60459496c602b3646a156811422a65f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xfmg_media_view_macros', 'media_film_strip', array(
		'mediaItem' => $__vars['mediaItem'],
		'filmStripParams' => $__vars['filmStripParams'],
	), $__vars);
	return $__finalCompiled;
}
);