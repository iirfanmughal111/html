<?php
// FROM HASH: b51c1df4aece2f210a149a6da9682751
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_review_macros', 'reply', array(
		'review' => $__vars['itemRating'],
		'reply' => $__vars['reply'],
	), $__vars);
	return $__finalCompiled;
}
);