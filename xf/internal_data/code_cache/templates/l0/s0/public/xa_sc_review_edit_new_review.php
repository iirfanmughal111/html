<?php
// FROM HASH: 0ff294ce658442c216901c6411a4506d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_review_macros', 'review', array(
		'review' => $__vars['review'],
		'item' => $__vars['item'],
	), $__vars);
	return $__finalCompiled;
}
);