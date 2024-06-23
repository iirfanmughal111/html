<?php
// FROM HASH: f2fb4aeb6e90146ef537e110fc08c272
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	';
	if ($__vars['comment']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
		' . 'New comment on watched media item' . '
	';
	} else {
		$__finalCompiled .= '
		' . 'New comment on watched album' . '
	';
	}
	$__finalCompiled .= '
</mail:subject>

';
	if ($__vars['comment']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
	' . '<p>' . $__templater->func('username_link_email', array($__vars['comment']['User'], $__vars['comment']['username'], ), true) . ' commented on a media item you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '
';
	} else {
		$__finalCompiled .= '
	' . '<p>' . $__templater->func('username_link_email', array($__vars['comment']['User'], $__vars['comment']['username'], ), true) . ' commented on an album you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '
';
	}
	$__finalCompiled .= '

<h2><a href="' . $__templater->func('link', array('canonical:media/comments', $__vars['comment'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['comment']['message'], 'xfmg_comment', $__vars['comment'], ), true) . '</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['comment']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
	' . $__templater->callMacro('xfmg_media_macros', 'go_media_bar', array(
			'mediaItem' => $__vars['content'],
			'watchType' => 'media',
		), $__vars) . '
	' . $__templater->callMacro('xfmg_media_macros', 'watched_media_footer', array(
			'mediaItem' => $__vars['content'],
		), $__vars) . '
';
	} else if ($__vars['comment']['content_type'] == 'xfmg_album') {
		$__finalCompiled .= '
	' . $__templater->callMacro('xfmg_album_macros', 'go_album_bar', array(
			'album' => $__vars['content'],
		), $__vars) . '
	' . $__templater->callMacro('xfmg_album_macros', 'watched_album_footer', array(
			'album' => $__vars['content'],
		), $__vars) . '
';
	}
	return $__finalCompiled;
}
);