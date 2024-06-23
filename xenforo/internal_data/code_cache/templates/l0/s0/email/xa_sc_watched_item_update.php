<?php
// FROM HASH: b576d1b2ba9e93aad9700f668070edf8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . '' . ($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . $__templater->escape($__vars['item']['title'])) . ' - Watched item updated' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['item']['User'], $__vars['item']['username'], ), true) . ' updated an item you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . $__templater->escape($__vars['item']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['item']['message'], 'sc_item', $__vars['item'], ), true) . '</div>
	
	';
		if ($__vars['item']['message_s2'] != '') {
			$__finalCompiled .= '
		<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['item']['message_s2'], 'sc_item', $__vars['item'], ), true) . '</div>	
	';
		}
		$__finalCompiled .= '
	
	';
		if ($__vars['item']['message_s3'] != '') {
			$__finalCompiled .= '
		<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['item']['message_s3'], 'sc_item', $__vars['item'], ), true) . '</div>	
	';
		}
		$__finalCompiled .= '
	
	';
		if ($__vars['item']['message_s4'] != '') {
			$__finalCompiled .= '
		<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['item']['message_s4'], 'sc_item', $__vars['item'], ), true) . '</div>	
	';
		}
		$__finalCompiled .= '
	
	';
		if ($__vars['item']['message_s5'] != '') {
			$__finalCompiled .= '
		<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['item']['message_s5'], 'sc_item', $__vars['item'], ), true) . '</div>	
	';
		}
		$__finalCompiled .= '	
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('xa_sc_item_macros', 'go_item_bar', array(
		'item' => $__vars['item'],
		'watchType' => 'update',
	), $__vars) . '
' . $__templater->callMacro('xa_sc_item_macros', 'watched_item_footer', array(
		'item' => $__vars['item'],
	), $__vars);
	return $__finalCompiled;
}
);