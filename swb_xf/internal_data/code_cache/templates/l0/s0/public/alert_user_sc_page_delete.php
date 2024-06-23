<?php
// FROM HASH: d9d49e058eeb3a4b84879a99708488f3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your page \'' . $__templater->escape($__vars['extra']['title']) . '\' on \'' . ((((('<a href="' . $__templater->func('base_url', array($__vars['extra']['itemLink'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['extra']['prefix_id'], ), true)) . $__templater->escape($__vars['extra']['itemTitle'])) . '</a>') . '\' was deleted.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);