<?php
// FROM HASH: 59cdc2504803477859cc67421b951432
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xfmg_comment_macros', ($__vars['lightbox'] ? 'comment_lightbox' : 'comment'), array(
		'comment' => $__vars['comment'],
		'content' => $__vars['content'],
		'linkPrefix' => ((('media/' . $__vars['content']['content_type']) == 'xfmg_media') ? 'media' : ('album' . '-comments')),
	), $__vars) . '
';
	return $__finalCompiled;
}
);