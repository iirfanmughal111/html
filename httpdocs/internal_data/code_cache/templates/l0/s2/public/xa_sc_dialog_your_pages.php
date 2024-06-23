<?php
// FROM HASH: 0fe2a0ea03924a5f2096cc9d98bdd296
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_editor_dialog_showcase', 'page_list', array(
		'page' => $__vars['page'],
		'itemPages' => $__vars['itemPages'],
		'listClass' => 'js-yourPagesList',
		'link' => 'showcase/dialog/your-pages',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);