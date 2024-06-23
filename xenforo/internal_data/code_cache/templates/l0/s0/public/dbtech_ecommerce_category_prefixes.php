<?php
// FROM HASH: e04d633237e312b9f1dd727e846c92be
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('prefix_macros', 'select', array(
		'name' => 'na',
		'prefixes' => $__vars['prefixes'],
		'type' => 'dbtechEcommerceProduct',
	), $__vars);
	return $__finalCompiled;
}
);