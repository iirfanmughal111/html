<?php
// FROM HASH: f5663ff08ee1503498ff245d9bdec8f5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['timeToNextBump'] = $__templater->method($__vars['thread'], 'getTimeToNextBump', array());
	$__finalCompiled .= '
';
	if ($__vars['timeToNextBump']) {
		$__finalCompiled .= '
	' . $__templater->func('date_dynamic', array(($__vars['xf']['time'] + $__vars['timeToNextBump']), array(
		))) . '
';
	} else {
		$__finalCompiled .= '
	' . 'Bump Thread' . '
';
	}
	return $__finalCompiled;
}
);