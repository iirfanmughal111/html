<?php
// FROM HASH: 41005f975cc1d5f96ac752d1996ef918
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['extra']['status'] == 1) {
		$__finalCompiled .= '
' . '' . $__templater->escape($__vars['extra']['username']) . ' your deposit amount  of $' . $__templater->escape($__vars['extra']['amount']) . '  is confirmed.' . '
	';
	} else if ($__vars['extra']['status'] == 0) {
		$__finalCompiled .= '
	' . '' . $__templater->escape($__vars['extra']['username']) . ' your deposit amount  of $' . $__templater->escape($__vars['extra']['amount']) . ' is failed.' . '
';
	}
	return $__finalCompiled;
}
);