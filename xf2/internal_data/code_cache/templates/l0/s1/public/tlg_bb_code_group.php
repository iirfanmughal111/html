<?php
// FROM HASH: 4be5bc2962b27294c13b0f5a7708b4fe
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="groupBbCode--wrapper">
    ';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '
    ' . $__templater->callMacro('tlg_group_list_macros', 'group', array(
		'group' => $__vars['group'],
		'showMembers' => true,
	), $__vars) . '
</div>';
	return $__finalCompiled;
}
);