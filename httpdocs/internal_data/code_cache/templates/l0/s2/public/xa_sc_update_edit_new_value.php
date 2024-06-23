<?php
// FROM HASH: 902d96114c25c0596676d5a5b97b284a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_update_macros', 'update', array(
		'update' => $__vars['update'],
		'item' => $__vars['item'],
	), $__vars);
	return $__finalCompiled;
}
);