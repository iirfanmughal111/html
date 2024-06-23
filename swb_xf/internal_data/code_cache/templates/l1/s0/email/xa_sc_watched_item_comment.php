<?php
// FROM HASH: b27dcddbc67ed385b2330f3ea6c4cbb6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'New comment on watched item' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['comment']['User'], $__vars['comment']['username'], ), true) . ' commented on an item you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:showcase/comments', $__vars['comment'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['comment']['message'], 'sc_comment', $__vars['comment'], ), true) . '</div>
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('xa_sc_item_macros', 'go_item_bar', array(
		'item' => $__vars['content'],
		'watchType' => 'item',
	), $__vars) . '
' . $__templater->callMacro('xa_sc_item_macros', 'watched_item_footer', array(
		'item' => $__vars['content'],
	), $__vars);
	return $__finalCompiled;
}
);