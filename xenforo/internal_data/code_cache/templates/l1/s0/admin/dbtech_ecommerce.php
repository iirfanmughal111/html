<?php
// FROM HASH: f921a83d54642511c1c7edbfd7662994
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('DragonByte eCommerce');
	$__finalCompiled .= '

' . $__templater->callMacro('section_nav_macros', 'section_nav', array(
		'section' => 'dbtechEcommerce',
	), $__vars);
	return $__finalCompiled;
}
);