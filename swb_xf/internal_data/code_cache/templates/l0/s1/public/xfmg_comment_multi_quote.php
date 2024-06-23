<?php
// FROM HASH: d79e92cfd0613dd4fbd54cf5f68adccc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('multi_quote_macros', 'block', array(
		'quotes' => $__vars['quotes'],
		'messages' => $__vars['comments'],
		'containerRelation' => 'Content',
		'dateKey' => 'comment_date',
		'bbCodeContext' => 'xfmg_comment',
	), $__vars) . '
';
	return $__finalCompiled;
}
);