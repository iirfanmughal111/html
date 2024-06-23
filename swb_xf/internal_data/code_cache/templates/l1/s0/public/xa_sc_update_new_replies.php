<?php
// FROM HASH: bf2a557f2b7d8c0e341c14d589fe8ec3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->isTraversable($__vars['itemUpdateReplies'])) {
		foreach ($__vars['itemUpdateReplies'] AS $__vars['itemUpdateReply']) {
			$__finalCompiled .= '
	' . $__templater->callMacro('xa_sc_update_macros', 'reply', array(
				'update' => $__vars['itemUpdate'],
				'reply' => $__vars['itemUpdateReply'],
			), $__vars) . '
';
		}
	}
	return $__finalCompiled;
}
);