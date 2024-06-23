<?php
// FROM HASH: dca4efe4567656b1e8d8ecb12e3c2c60
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['expandedLayout']) {
		$__finalCompiled .= '
    ' . $__templater->callMacro('tlg_comment_macros', 'comment_root', array(
			'comment' => $__vars['comment'],
			'content' => $__vars['content'],
		), $__vars) . '
';
	} else {
		$__finalCompiled .= '
    ' . $__templater->callMacro('tlg_comment_macros', 'comment', array(
			'comment' => $__vars['comment'],
			'content' => $__vars['content'],
		), $__vars) . '
';
	}
	return $__finalCompiled;
}
);