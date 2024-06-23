<?php
// FROM HASH: 14007a53e188efaf9e158cd4a8261864
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('prefix_macros', 'select', array(
		'name' => 'na',
		'prefixes' => $__vars['prefixes'],
		'type' => 'sc_item',
	), $__vars);
	return $__finalCompiled;
}
);