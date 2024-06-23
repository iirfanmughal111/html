<?php
// FROM HASH: c1482c70f6bb458b2039274014524e00
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['source_user_id'] == $__vars['content']['user_id']) {
		$__finalCompiled .= '
	';
		if ($__vars['content']['amount'] < 0) {
			$__finalCompiled .= '
		' . 'You removed ' . (((((('<a href="' . $__templater->func('link', array('dbtech-credits/currency', $__vars['content']['Currency'], ), true)) . '" class="fauxBlockLink-blockLink" data-xf-click="overlay">') . $__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], )))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . '</a>') . ' from your account.' . '
	';
		} else {
			$__finalCompiled .= '
		' . 'You added ' . (((((('<a href="' . $__templater->func('link', array('dbtech-credits/currency', $__vars['content']['Currency'], ), true)) . '" class="fauxBlockLink-blockLink" data-xf-click="overlay">') . $__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], )))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . '</a>') . ' to your account.' . '
	';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '	
	';
		if ($__vars['content']['amount'] < 0) {
			$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['content']['SourceUser'], false, array('defaultname' => 'N/A', ), ), true) . ' removed ' . (((((('<a href="' . $__templater->func('link', array('dbtech-credits/currency', $__vars['content']['Currency'], ), true)) . '" class="fauxBlockLink-blockLink" data-xf-click="overlay">') . $__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], )))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . '</a>') . ' from your account.' . '
	';
		} else {
			$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['content']['SourceUser'], false, array('defaultname' => 'N/A', ), ), true) . ' added ' . (((((('<a href="' . $__templater->func('link', array('dbtech-credits/currency', $__vars['content']['Currency'], ), true)) . '" class="fauxBlockLink-blockLink" data-xf-click="overlay">') . $__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], )))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . '</a>') . ' to your account.' . '
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
	return $__finalCompiled;
}
);