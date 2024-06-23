<?php
// FROM HASH: fac973ea9556dbec0b6206526cf234a9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->func('snippet', array($__vars['content']['change_log'], $__templater->func('max_length', array($__vars['bookmark'], 'message', ), false), array('stripQuote' => true, ), ), true);
	return $__finalCompiled;
}
);