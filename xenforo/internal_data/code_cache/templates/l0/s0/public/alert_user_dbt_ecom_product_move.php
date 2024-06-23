<?php
// FROM HASH: c41143c064f07129fa3e6ee82bd40253
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], ), true) . $__templater->escape($__vars['extra']['title'])) . ' was moved to a different category.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);