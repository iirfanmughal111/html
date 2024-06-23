<?php
// FROM HASH: 68b20daf0096eb3e5101abc67d249d25
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Auction');
	$__finalCompiled .= '

' . $__templater->callMacro('section_nav_macros', 'section_nav', array(
		'section' => 'auction',
	), $__vars);
	return $__finalCompiled;
}
);