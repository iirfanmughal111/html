<?php
// FROM HASH: 2ea801b6dc6f908767ff83d65cfb826b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xfmg_editor_dialog_gallery', 'album_list', array(
		'page' => $__vars['page'],
		'albums' => $__vars['albums'],
		'listClass' => 'js-browseAlbumsList',
		'link' => 'media/albums/dialog/browse',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);