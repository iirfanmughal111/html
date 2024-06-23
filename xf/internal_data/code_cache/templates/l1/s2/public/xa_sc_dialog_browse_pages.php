<?php
// FROM HASH: 082bbccfb20010cb87a00ef2b7acb98f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_editor_dialog_showcase', 'page_list', array(
		'page' => $__vars['page'],
		'itemPages' => $__vars['itemPages'],
		'listClass' => 'js-browsePagesList',
		'link' => 'showcase/dialog/browse-pages',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);