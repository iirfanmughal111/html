<?php
// FROM HASH: 559e2e6b0de0026e0adb78519cb6c9c7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xfmg_editor_dialog_gallery', 'album_list', array(
		'page' => $__vars['page'],
		'albums' => $__vars['albums'],
		'listClass' => 'js-yourAlbumsList',
		'link' => 'media/albums/dialog/yours',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);