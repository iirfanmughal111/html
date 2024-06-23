<?php
// FROM HASH: d9f0eebbbc668b6639bce3b5edc48c7e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your media ' . $__templater->escape($__vars['extra']['title']) . ' was deleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('base_url', array($__vars['extra']['link'], 'canonical', ), true) . '</push:url>';
	return $__finalCompiled;
}
);