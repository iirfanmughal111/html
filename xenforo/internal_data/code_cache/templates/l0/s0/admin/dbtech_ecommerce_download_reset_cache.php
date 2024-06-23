<?php
// FROM HASH: 68a4250695b25d37d381269bc0550350
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reset cache' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['download']['title']));
	$__finalCompiled .= '

' . $__templater->callMacro('public:dbtech_ecommerce_download_edit_macros', 'reset_cache', array(
		'context' => 'admin',
		'linkPrefix' => 'dbtech-ecommerce/downloads',
		'download' => $__vars['download'],
	), $__vars);
	return $__finalCompiled;
}
);