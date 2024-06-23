<?php
// FROM HASH: 630ebf71b683946a2dc63f72c3a239b1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('multi_quote_macros', 'block', array(
		'quotes' => $__vars['quotes'],
		'messages' => $__vars['comments'],
		'containerRelation' => 'Content',
		'dateKey' => 'comment_date',
		'bbCodeContext' => 'sc_comment',
	), $__vars);
	return $__finalCompiled;
}
);