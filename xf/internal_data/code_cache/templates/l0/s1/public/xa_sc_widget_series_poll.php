<?php
// FROM HASH: f5c675d068efe9713ae99c859f95ca92
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