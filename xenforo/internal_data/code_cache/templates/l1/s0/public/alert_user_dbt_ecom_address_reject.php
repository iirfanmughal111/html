<?php
// FROM HASH: eaf53e9a27e10087bd9578fa024125fb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your address ' . (((('<a href="' . $__templater->func('base_url', array($__vars['extra']['link'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['extra']['title'])) . '</a>') . ' was rejected.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);