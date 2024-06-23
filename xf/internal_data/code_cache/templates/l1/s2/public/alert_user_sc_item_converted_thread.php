<?php
// FROM HASH: 0784066a7a2b1dac7dcf38bfc2d37f37
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your thread was converted to the item ' . ((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['title'])) . '</a>') . '.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);