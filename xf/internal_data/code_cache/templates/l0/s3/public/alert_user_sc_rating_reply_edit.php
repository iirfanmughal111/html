<?php
// FROM HASH: 83813b8277d2c0a9598a68e84a3af859
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your reply to a review on the item ' . (((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['title'])) . '</a>') . ' was edited.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);