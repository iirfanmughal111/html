<?php
// FROM HASH: ba8b60b609404d9d3343a81e6881762b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your download ' . $__templater->escape($__vars['extra']['update']) . ' for ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], ), true) . $__templater->escape($__vars['extra']['title'])) . ' was deleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);