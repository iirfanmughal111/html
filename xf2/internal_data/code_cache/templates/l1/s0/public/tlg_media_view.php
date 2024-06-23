<?php
// FROM HASH: 6cba0d93a6e9d412eb4eb9e768d31557
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('media');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__compilerTemp2 = $__vars;
	$__compilerTemp2['tlgNoBreadcrumbs'] = $__templater->preEscaped('1');
	$__finalCompiled .= $__templater->includeTemplate('xfmg_media_view', $__compilerTemp2);
	return $__finalCompiled;
}
);