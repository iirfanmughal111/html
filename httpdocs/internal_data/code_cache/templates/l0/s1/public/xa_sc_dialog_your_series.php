<?php
// FROM HASH: 36d834d94aee08c188f95256ca56dcb4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_editor_dialog_showcase', 'series_list', array(
		'page' => $__vars['page'],
		'series' => $__vars['series'],
		'listClass' => 'js-yourSeriesList',
		'link' => 'showcase/series/dialog/yours',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);