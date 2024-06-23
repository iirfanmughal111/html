<?php
// FROM HASH: 1160a48e9d2a1792b8677c277b4fd11f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['amount'] < 0) {
		$__finalCompiled .= '
	' . 'You donated ' . (($__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], ))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . ' to ' . ($__templater->escape($__vars['user']['username']) ?: 'N/A') . '.' . '
';
	} else {
		$__finalCompiled .= '
	' . '' . ($__templater->escape($__vars['user']['username']) ?: 'N/A') . ' donated ' . (($__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], ))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . ' to you.' . '
';
	}
	$__finalCompiled .= '
';
	if ($__vars['content']['message']) {
		$__finalCompiled .= 'Message' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['content']['message']);
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('link', array('canonical:dbtech-credits/currency', $__vars['content']['Currency'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);