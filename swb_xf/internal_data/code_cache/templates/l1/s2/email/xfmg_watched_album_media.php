<?php
// FROM HASH: 99ca137574e79a3c193a396d5eeae3e0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . '' . $__templater->escape($__vars['mediaItem']['title']) . ' - New media item in watched album' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['mediaItem']['User'], $__vars['mediaItem']['username'], ), true) . ' added a media item to an album you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage'] AND $__vars['mediaItem']['description']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->filter($__templater->func('structured_text', array($__vars['mediaItem']['description'], ), false), array(array('raw', array()),), true) . '</div>
';
	}
	$__finalCompiled .= '


' . $__templater->callMacro('xfmg_album_macros', 'go_album_bar', array(
		'album' => $__vars['album'],
	), $__vars) . '
' . $__templater->callMacro('xfmg_album_macros', 'watched_album_footer', array(
		'album' => $__vars['album'],
	), $__vars) . '
';
	return $__finalCompiled;
}
);