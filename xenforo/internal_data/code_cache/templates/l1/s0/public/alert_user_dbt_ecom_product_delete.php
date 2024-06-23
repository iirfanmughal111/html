<?php
// FROM HASH: e82104d3173884a81ed5d9a4c7540d2a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your product ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], ), true) . $__templater->escape($__vars['extra']['title'])) . ' was deleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);