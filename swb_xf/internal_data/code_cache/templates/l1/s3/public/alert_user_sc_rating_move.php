<?php
// FROM HASH: e6ceabb54d25a3b769113c2860cbcad4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your review on the item ' . $__templater->escape($__vars['extra']['sourceTitle']) . ' was moved to ' . ((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['targetLink'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['title'])) . '</a>') . '.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);