<?php
// FROM HASH: 5bab32f9f330bdf94d38c0d213a831e2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'New update posted on watched item' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['update']['User'], $__vars['update']['username'], ), true) . ' posted a new update on an item you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:showcase/update', $__vars['update'], ), true) . '">' . $__templater->escape($__vars['update']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['update']['message'], 'sc_update', $__vars['update'], ), true) . '</div>
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