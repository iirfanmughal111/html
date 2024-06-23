<?php
// FROM HASH: dc7b0ec8523d91a7b5b123efbb21da66
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your item ' . ((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['title'])) . '</a>') . ' was reassigned to ' . $__templater->escape($__vars['extra']['to']) . '.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);