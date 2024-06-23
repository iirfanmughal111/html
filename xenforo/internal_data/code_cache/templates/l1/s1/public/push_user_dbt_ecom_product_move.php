<?php
// FROM HASH: 5e76e3edcabed2acae5f9bc283230c8a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], 'plain', ), true) . $__templater->escape($__vars['extra']['title'])) . ' was moved to a different category.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);