<?php
// FROM HASH: 375c9908edc9154ab0fd019d69bfe9fc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'New item on watched series' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['item']['User'], $__vars['item']['username'], ), true) . ' added an item to a series you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['article'], 'escaped', ), true) . $__templater->escape($__vars['item']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['item']['message'], 'sc_item', $__vars['item'], ), true) . '</div>
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('xa_sc_item_macros', 'go_item_bar', array(
		'item' => $__vars['item'],
		'watchType' => 'series',
	), $__vars) . '

' . $__templater->callMacro('xa_sc_item_macros', 'watched_series_footer', array(
		'series' => $__vars['series'],
	), $__vars);
	return $__finalCompiled;
}
);