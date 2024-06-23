<?php
// FROM HASH: eb1aeeb049564b5fb3c240776afe2ed6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('post_macros', 'post', array(
		'post' => $__vars['post'],
		'thread' => $__vars['thread'],
	), $__vars) . '
' . $__templater->callMacro('post_macros', 'post', array(
		'post' => $__vars['newPost'],
		'thread' => $__vars['thread'],
	), $__vars);
	return $__finalCompiled;
}
);