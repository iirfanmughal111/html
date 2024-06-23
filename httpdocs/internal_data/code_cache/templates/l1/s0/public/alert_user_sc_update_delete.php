<?php
// FROM HASH: 3034a8797ef81218ec78d9f28cd5846c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your update \'' . $__templater->escape($__vars['extra']['title']) . '\' on \'' . ($__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true) . $__templater->escape($__vars['extra']['itemTitle'])) . '\' was deleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);