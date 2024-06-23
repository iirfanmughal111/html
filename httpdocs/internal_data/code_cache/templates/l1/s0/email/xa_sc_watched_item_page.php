<?php
// FROM HASH: acc0729c91c64405cc08f8efb5e80f12
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'New page added to watched item' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['item']['User'], $__vars['item']['username'], ), true) . ' added a new page on an item you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], array('item_page' => $__vars['page']['page_id'], ), ), true) . '">' . $__templater->escape($__vars['item']['title']) . ' - ' . $__templater->escape($__vars['page']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['page']['message'], 'sc_page', $__vars['page'], ), true) . '</div>
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('xa_sc_item_macros', 'go_item_bar', array(
		'item' => $__vars['item'],
		'watchType' => 'item',
	), $__vars) . '
' . $__templater->callMacro('xa_sc_item_macros', 'watched_item_footer', array(
		'item' => $__vars['item'],
	), $__vars);
	return $__finalCompiled;
}
);