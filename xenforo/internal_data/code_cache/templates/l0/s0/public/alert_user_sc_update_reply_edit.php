<?php
// FROM HASH: b18224dd103cee4c45d8f6e7910ffdac
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your reply to the update ' . (((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['update_title'])) . '</a>') . ' on the item ' . (((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '">') . $__templater->escape($__vars['extra']['item_title'])) . '</a>') . ' was edited.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);