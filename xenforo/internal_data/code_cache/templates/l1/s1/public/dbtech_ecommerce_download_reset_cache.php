<?php
// FROM HASH: 2d8e4cd38d7d573797daca576d1f807a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reset cache' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['download']['title']));
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_download_edit_macros', 'reset_cache', array(
		'context' => 'public',
		'linkPrefix' => 'dbtech-ecommerce/release',
		'download' => $__vars['download'],
	), $__vars);
	return $__finalCompiled;
}
);