<?php
// FROM HASH: 1d190cc9f5f139daefcf4dd620b785c7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . 'New review on watched item' . '
</mail:subject>

';
	if ($__vars['review']['is_anonymous']) {
		$__finalCompiled .= '
	' . '<p>' . 'Anonymous' . ' posted a review on an item you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '	
';
	} else {
		$__finalCompiled .= '
	' . '<p>' . $__templater->func('username_link_email', array($__vars['review']['User'], $__vars['review']['username'], ), true) . ' posted a review on an item you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '
';
	}
	$__finalCompiled .= '

<h2><a href="' . $__templater->func('link', array('canonical:showcase/review', $__vars['review'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['review']['message'], 'sc_rating', $__vars['review'], ), true) . '</div>
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