<?php
// FROM HASH: 2be33780a79371da77dbfa21c0ebc8c1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('

	' . $__templater->callMacro('public:currency_macros', 'currency_list', array(
		'name' => $__vars['inputName'],
		'value' => ($__vars['option']['option_value'] ?: 'USD'),
	), $__vars) . '
', array(
		'name' => $__vars['inputName'],
		'value' => $__vars['option']['option_value'],
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);