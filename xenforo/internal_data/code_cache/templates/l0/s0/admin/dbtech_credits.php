<?php
// FROM HASH: 6eb6526526e983c3e6bb0cf206ee9212
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('DragonByte Credits');
	$__finalCompiled .= '

' . $__templater->callMacro('section_nav_macros', 'section_nav', array(
		'section' => 'dbtechCredits',
	), $__vars);
	return $__finalCompiled;
}
);