<?php
// FROM HASH: 51fdec2cb4b5bad350e292958824c6b9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['source_user_id'] == $__vars['content']['user_id']) {
		$__finalCompiled .= '
	';
		if ($__vars['content']['amount'] < 0) {
			$__finalCompiled .= '
		' . 'You removed ' . (($__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], ))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . ' from your account.' . '
	';
		} else {
			$__finalCompiled .= '
		' . 'You added ' . (($__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], ))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . ' to your account.' . '
	';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '	
	';
		if ($__vars['content']['amount'] < 0) {
			$__finalCompiled .= '
		' . '' . ($__templater->escape($__vars['content']['SourceUser']['username']) ?: 'N/A') . ' removed ' . (($__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], ))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . ' from your account.' . '
	';
		} else {
			$__finalCompiled .= '
		' . '' . ($__templater->escape($__vars['content']['SourceUser']['username']) ?: 'N/A') . ' added ' . (($__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], ))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . ' to your account.' . '
	';
		}
		$__finalCompiled .= '
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