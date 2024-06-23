<?php
// FROM HASH: 306c3b07dbd611381e67d35956659e17
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your media ' . (((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['title'])) . '</a>') . ' was edited.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);