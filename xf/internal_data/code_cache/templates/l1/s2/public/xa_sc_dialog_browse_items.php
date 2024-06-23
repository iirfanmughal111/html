<?php
// FROM HASH: d8e04d985fcd6af9dc6a49a40c95832e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_editor_dialog_showcase', 'item_list', array(
		'page' => $__vars['page'],
		'items' => $__vars['items'],
		'listClass' => 'js-browseItemsList',
		'link' => 'showcase/dialog/browse',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);