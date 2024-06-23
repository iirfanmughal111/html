<?php
// FROM HASH: 7f44558e8c80f456c09598dc68ca585b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . '' . $__templater->escape($__vars['mediaItem']['title']) . ' - New media item in watched category' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['mediaItem']['User'], $__vars['mediaItem']['username'], ), true) . ' added a media item to a category you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage'] AND $__vars['mediaItem']['description']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->filter($__templater->func('structured_text', array($__vars['mediaItem']['description'], ), false), array(array('raw', array()),), true) . '</div>
';
	}
	$__finalCompiled .= '


' . $__templater->callMacro('xfmg_media_macros', 'go_media_bar', array(
		'mediaItem' => $__vars['mediaItem'],
		'watchType' => 'category',
	), $__vars) . '

' . $__templater->callMacro('xfmg_media_macros', 'watched_category_footer', array(
		'category' => $__vars['category'],
	), $__vars) . '
';
	return $__finalCompiled;
}
);