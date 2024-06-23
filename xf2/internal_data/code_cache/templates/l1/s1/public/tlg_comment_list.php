<?php
// FROM HASH: 7116fbf0cdc9b785798bedf8d50fccdd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['comments'])) {
		foreach ($__vars['comments'] AS $__vars['commentItem']) {
			$__finalCompiled .= '
    ' . $__templater->callMacro('tlg_comment_macros', 'comment', array(
				'comment' => $__vars['commentItem'],
				'content' => $__vars['commentItem']['Content'],
			), $__vars) . '
';
		}
	}
	return $__finalCompiled;
}
);