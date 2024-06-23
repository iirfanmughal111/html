<?php
// FROM HASH: e35d236f6345fdbb33d4f630d315d5d6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xfmg_media_view.less');
	$__finalCompiled .= '
' . $__templater->callMacro('xfmg_media_view_macros', 'note_view', array(
		'mediaItem' => $__vars['mediaItem'],
		'note' => $__vars['note'],
	), $__vars);
	return $__finalCompiled;
}
);