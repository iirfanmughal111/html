<?php
// FROM HASH: 177835243266b452e332f65460b74b97
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('xa_sc_comment_macros', 'comment', array(
		'comment' => $__vars['comment'],
		'content' => $__vars['content'],
		'linkPrefix' => 'showcase/item-comments',
	), $__vars);
	return $__finalCompiled;
}
);