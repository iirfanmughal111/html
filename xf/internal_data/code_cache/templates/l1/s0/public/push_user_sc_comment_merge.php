<?php
// FROM HASH: bec7108ee124e6f2514604aa8789c753
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your comments in the item ' . ($__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true) . $__templater->escape($__vars['extra']['title'])) . ' were merged together.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('base_url', array($__vars['extra']['itemLink'], 'canonical', ), true) . '</push:url>';
	return $__finalCompiled;
}
);