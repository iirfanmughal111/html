<?php
// FROM HASH: 55588e417ab532e2446d0210ce92dee0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['extra']['welcome']) {
		$__finalCompiled .= '
	' . 'Welcome to ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . '!' . '
';
	}
	$__finalCompiled .= '
';
	if ($__vars['content']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
	' . 'Your reply to the media item ' . $__templater->escape($__vars['content']['Content']['title']) . ' was submitted.' . '
';
	} else {
		$__finalCompiled .= '
	' . 'Your reply to the album ' . $__templater->escape($__vars['content']['Content']['title']) . ' was submitted.' . '
';
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('link', array('canonical:media/comments', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);