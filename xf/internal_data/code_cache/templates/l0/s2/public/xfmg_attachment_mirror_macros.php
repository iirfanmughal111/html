<?php
// FROM HASH: 1b2b53008557685a6d6ec1d9d62205ef
return array(
'macros' => array('lightbox_caption' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'attachment' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['attachment']['XfmgMirrorMedia'] AND $__templater->method($__vars['attachment']['XfmgMirrorMedia'], 'canView', array())) {
		$__compilerTemp1 .= '
	&nbsp;&middot; <a href="' . $__templater->func('link', array('media', $__vars['attachment']['XfmgMirrorMedia'], ), true) . '">' . $__templater->fontAwesome('fa-camera', array(
		)) . ' ' . 'View media item' . '</a>
';
	}
	$__finalCompiled .= $__templater->func('trim', array('
' . $__compilerTemp1 . '
'), false);
	return $__finalCompiled;
}
),
'lightbox_sidebar_href' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'attachment' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__vars['attachment']['XfmgMirrorMedia'] AND $__templater->method($__vars['attachment']['XfmgMirrorMedia'], 'canView', array())) {
		$__compilerTemp1 .= '
	' . $__templater->func('link', array('media', $__vars['attachment']['XfmgMirrorMedia'], array('lightbox' => 1, ), ), true) . '
';
	}
	$__finalCompiled .= $__templater->func('trim', array('
' . $__compilerTemp1 . '
'), false);
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);