<?php
// FROM HASH: cf997e0fa1178b4277ce43071a5971cb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tlg_post_macros', 'post', array(
		'post' => $__vars['post'],
	), $__vars);
	return $__finalCompiled;
}
);