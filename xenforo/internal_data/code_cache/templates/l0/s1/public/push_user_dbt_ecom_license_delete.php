<?php
// FROM HASH: 9725a852187651fd697f40a6475e5133
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your license ' . (((($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], 'plain', ), true) . $__templater->escape($__vars['extra']['title'])) . ' [') . $__templater->escape($__vars['extra']['license_key'])) . ']') . ' was deleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);