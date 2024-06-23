<?php
// FROM HASH: 2ae9f7ee2cb2bcaa843a89f95b429262
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_editor_dialog_showcase', 'series_list', array(
		'page' => $__vars['page'],
		'series' => $__vars['series'],
		'listClass' => 'js-browseSeriesList',
		'link' => 'showcase/series/dialog/browse',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);