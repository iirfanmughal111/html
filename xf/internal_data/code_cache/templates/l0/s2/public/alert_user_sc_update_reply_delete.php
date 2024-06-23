<?php
// FROM HASH: 2b37c365d349704265596a355b6de5f4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your reply to the update \'' . $__templater->escape($__vars['extra']['update_title']) . '\' on item \'' . ($__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true) . $__templater->escape($__vars['extra']['item_title'])) . '\' was deleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);