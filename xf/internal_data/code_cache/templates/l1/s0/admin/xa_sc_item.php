<?php
// FROM HASH: 0fee6a4f767b4237928e6216eeced7c9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Showcase');
	$__finalCompiled .= '

' . $__templater->callMacro('section_nav_macros', 'section_nav', array(
		'section' => 'xa_showcase',
	), $__vars);
	return $__finalCompiled;
}
);