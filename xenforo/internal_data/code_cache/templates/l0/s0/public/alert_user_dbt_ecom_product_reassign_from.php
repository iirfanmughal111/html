<?php
// FROM HASH: 3d503f63121746911ed907c305f52796
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your product ' . ((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['title'])) . '</a>') . ' was reassigned to ' . $__templater->escape($__vars['extra']['to']) . '.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);