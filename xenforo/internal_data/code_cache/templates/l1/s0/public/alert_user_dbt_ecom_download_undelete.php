<?php
// FROM HASH: 740bf690aaea0fa787a2ac89bd64b196
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your download ' . $__templater->escape($__vars['extra']['update']) . ' for ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['extra']['prefix_id'], ), true) . $__templater->escape($__vars['extra']['title'])) . ' was undeleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);