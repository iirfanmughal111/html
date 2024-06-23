<?php
// FROM HASH: 3522ee6b3bb8ba5e5ac7a830859d50ca
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'A page ' . (((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['title'])) . '</a>') . ' on the item ' . ((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['itemTitle'])) . '</a>') . ' was reassigned to you.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);