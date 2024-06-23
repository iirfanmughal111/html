<?php
// FROM HASH: 426a707e5baf89860d041e24e21f36f7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->func('snippet', array($__vars['content']['Description']['message'], $__templater->func('max_length', array($__vars['bookmark'], 'message', ), false), array('stripQuote' => true, ), ), true);
	return $__finalCompiled;
}
);