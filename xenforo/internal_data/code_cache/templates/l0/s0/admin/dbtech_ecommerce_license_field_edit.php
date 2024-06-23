<?php
// FROM HASH: e092fdeec57e6caacbb658a877e337e5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['extraOptions'] = $__templater->preEscaped('
		' . $__templater->callMacro('base_custom_field_macros', 'common_options', array(
		'field' => $__vars['field'],
		'supportsUserEditable' => true,
		'supportsEditableOnce' => true,
		'supportsModeratorEditable' => true,
	), $__vars) . '
	');
	$__finalCompiled .= $__templater->includeTemplate('base_custom_field_edit', $__compilerTemp1);
	return $__finalCompiled;
}
);