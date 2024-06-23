<?php
// FROM HASH: 4de2960147ddc3212d51c605827b5b67
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('prefix_macros', 'select', array(
		'name' => 'na',
		'prefixes' => $__vars['prefixes'],
		'type' => 'resource',
	), $__vars) . '
';
	return $__finalCompiled;
}
);