<?php
// FROM HASH: 0049671b23ac7eeb5540cadcbe139b63
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your item ' . $__templater->escape($__vars['extra']['title']) . ' was merged into the item ' . ((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['targetLink'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['targetTitle'])) . '</a>') . '.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);