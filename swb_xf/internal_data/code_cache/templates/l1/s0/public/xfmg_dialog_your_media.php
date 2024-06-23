<?php
// FROM HASH: b7dd20f88a5ea5321832eb323b49d744
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xfmg_editor_dialog_gallery', 'media_list', array(
		'page' => $__vars['page'],
		'mediaItems' => $__vars['mediaItems'],
		'listClass' => 'js-yourMediaList',
		'link' => 'media/dialog/yours',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);