<?php
// FROM HASH: 317cbdf0b9c23c6492d00b6b96c2febf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['option'], 'canEdit', array())) {
		$__compilerTemp1 .= '
		' . $__templater->callMacro('option_macros', 'option_edit_link', array(
			'group' => $__vars['group'],
			'option' => $__vars['option'],
		), $__vars) . '
	';
	}
	$__vars['editHtml'] = $__templater->preEscaped($__templater->func('trim', array('
	' . $__compilerTemp1 . '
'), false));
	$__finalCompiled .= '

' . $__templater->formRadioRow(array(
		'name' => $__vars['inputName'] . '[enabled]',
	), array(array(
		'label' => 'Disabled',
		'value' => '0',
		'selected' => $__vars['option']['option_value']['enabled'] == '0',
		'_type' => 'option',
	),
	array(
		'label' => 'Default',
		'value' => '1',
		'selected' => $__vars['option']['option_value']['enabled'] == '1',
		'hint' => 'This setting uses the Navigation Manager\'s position.',
		'_type' => 'option',
	),
	array(
		'label' => 'Right',
		'value' => '2',
		'selected' => $__vars['option']['option_value']['enabled'] == '2',
		'hint' => 'This will enable a navbar tab on the right hand side (where the "Alert" and "Inbox" tabs are).',
		'_dependent' => array($__templater->formRadio(array(
		'name' => $__vars['inputName'] . '[right_position]',
		'value' => ($__vars['option']['option_value']['right_position'] ? $__vars['option']['option_value']['right_position'] : 'end'),
	), array(array(
		'label' => 'Before "Account" Tab',
		'value' => 'start',
		'_type' => 'option',
	),
	array(
		'label' => 'Before "Alerts" Tab',
		'value' => 'middle',
		'_type' => 'option',
	),
	array(
		'label' => 'After "Alerts" Tab',
		'value' => 'end',
		'_type' => 'option',
	))), $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[right_text]',
		'value' => '1',
		'selected' => $__vars['option']['option_value']['right_text'],
		'label' => '
				' . 'Enable navbar text' . '
			',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	),
	array(
		'label' => 'Integrated with DragonByte Shop',
		'value' => '3',
		'selected' => $__vars['option']['option_value']['enabled'] == '3',
		'hint' => 'If you have DragonByte Shop installed and the navbar tab enabled, you can integrate Credits\' links with it.',
		'_type' => 'option',
	)), array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['editHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);