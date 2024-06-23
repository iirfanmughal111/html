<?php
// FROM HASH: f08db57745a4496e25c2131a96b438d1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xfmg_editor_dialog_gallery', 'media_list', array(
		'page' => $__vars['page'],
		'mediaItems' => $__vars['mediaItems'],
		'listClass' => 'js-browseMediaList',
		'link' => 'media/dialog/browse',
		'hasMore' => $__vars['hasMore'],
	), $__vars);
	return $__finalCompiled;
}
);