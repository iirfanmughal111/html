<?php
// FROM HASH: 85cb03f8f73f96224a2fe23579db2df4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_update_macros', 'reply', array(
		'update' => $__vars['itemUpdate'],
		'reply' => $__vars['reply'],
	), $__vars);
	return $__finalCompiled;
}
);