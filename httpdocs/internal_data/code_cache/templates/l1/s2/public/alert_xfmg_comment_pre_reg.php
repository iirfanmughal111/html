<?php
// FROM HASH: ea52e8d18fd3002b09fe1633bb872ece
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
	' . 'Your reply to the media item ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . ' was submitted.' . '
';
	} else {
		$__finalCompiled .= '
	' . 'Your reply to the album ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . ' was submitted.' . '
';
	}
	return $__finalCompiled;
}
);