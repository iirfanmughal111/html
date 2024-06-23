<?php
// FROM HASH: 403c70f5dc175ecdca063b1d654329bb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'The license ' . ((((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['title'])) . ' [') . $__templater->escape($__vars['extra']['license_key'])) . ']</a>') . ' was reassigned to you.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);