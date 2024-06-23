<?php
// FROM HASH: d4c282176e9afaf6840a00cdbd489140
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['amount'] < 0) {
		$__finalCompiled .= '
	' . 'You donated ' . (((((('<a href="' . $__templater->func('link', array('dbtech-credits/currency', $__vars['content']['Currency'], ), true)) . '" class="fauxBlockLink-blockLink" data-xf-click="overlay">') . $__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], )))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . '</a>') . ' to ' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => 'N/A', ), ), true) . '.' . '
';
	} else {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => 'N/A', ), ), true) . ' donated ' . (((((('<a href="' . $__templater->func('link', array('dbtech-credits/currency', $__vars['content']['Currency'], ), true)) . '" class="fauxBlockLink-blockLink" data-xf-click="overlay">') . $__templater->escape($__templater->method($__vars['content']['Currency'], 'getFormattedValue', array($__vars['amount'], )))) . ' ') . $__templater->escape($__vars['content']['Currency']['title'])) . '</a>') . ' to you.' . '
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