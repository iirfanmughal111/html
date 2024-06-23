<?php
// FROM HASH: 9c32e299491f8531d8b12931e2c620dc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your address ' . $__templater->escape($__vars['extra']['title']) . ' was edited.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('base_url', array($__vars['extra']['link'], 'canonical', ), true) . '</push:url>';
	return $__finalCompiled;
}
);