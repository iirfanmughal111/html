<?php
// FROM HASH: 9e87b240142cbf224ed59f7ff1c5b7f4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Logs');
	$__finalCompiled .= '

' . $__templater->callMacro('section_nav_macros', 'section_nav', array(
		'section' => 'dbtechEcomLogs',
	), $__vars);
	return $__finalCompiled;
}
);