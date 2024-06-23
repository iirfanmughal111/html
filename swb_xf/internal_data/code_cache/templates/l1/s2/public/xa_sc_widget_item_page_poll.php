<?php
// FROM HASH: 3ba01ee9822222008b7319ca6ca79333
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('poll_macros', 'poll_block', array(
		'poll' => $__vars['poll'],
		'simpleDisplay' => true,
		'forceTitle' => $__vars['title'],
	), $__vars);
	return $__finalCompiled;
}
);