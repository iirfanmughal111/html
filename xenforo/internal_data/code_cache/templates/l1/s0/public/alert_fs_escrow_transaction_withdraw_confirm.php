<?php
// FROM HASH: 16e45513f9fc8454545b57dfbd043045
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['extra']['status'] == 1) {
		$__finalCompiled .= '
' . '' . $__templater->escape($__vars['extra']['username']) . ' your withdraw amount  of $' . $__templater->escape($__vars['extra']['amount']) . '  is confirmed.' . '
	';
	} else if ($__vars['extra']['status'] == 0) {
		$__finalCompiled .= '
	' . '' . $__templater->escape($__vars['extra']['username']) . ' your withdraw amount  of $' . $__templater->escape($__vars['extra']['amount']) . ' is failed.' . '
';
	}
	return $__finalCompiled;
}
);