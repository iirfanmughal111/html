<?php
// FROM HASH: 2db47ef374f406c3bf7fa0ebaed4a5f4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_editor_dialog_showcase', 'item_list', array(
		'page' => $__vars['page'],
		'items' => $__vars['items'],
		'listClass' => 'js-yourItemsList',
		'link' => 'showcase/dialog/yours',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);