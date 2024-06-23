<?php
// FROM HASH: ff88cd0ecd2b2ffaa56d609c24790d53
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRadioRow(array(
		'listclass' => 'listColumns',
		'name' => $__vars['inputName'] . '[type]',
		'value' => $__vars['option']['option_value']['type'],
	), array(array(
		'value' => 'dom',
		'label' => 'Day of the month' . $__vars['xf']['language']['label_separator'],
		'name' => $__vars['inputName'] . '[type]',
		'_dependent' => array($__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[day_type]',
		'value' => $__vars['option']['option_value']['day_type'],
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => '1',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => '2',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => '3',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => '4',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => '5',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => '6',
		'_type' => 'option',
	),
	array(
		'value' => '7',
		'label' => '7',
		'_type' => 'option',
	),
	array(
		'value' => '8',
		'label' => '8',
		'_type' => 'option',
	),
	array(
		'value' => '9',
		'label' => '9',
		'_type' => 'option',
	),
	array(
		'value' => '10',
		'label' => '10',
		'_type' => 'option',
	),
	array(
		'value' => '11',
		'label' => '11',
		'_type' => 'option',
	),
	array(
		'value' => '12',
		'label' => '12',
		'_type' => 'option',
	),
	array(
		'value' => '13',
		'label' => '13',
		'_type' => 'option',
	),
	array(
		'value' => '14',
		'label' => '14',
		'_type' => 'option',
	),
	array(
		'value' => '15',
		'label' => '15',
		'_type' => 'option',
	),
	array(
		'value' => '16',
		'label' => '16',
		'_type' => 'option',
	),
	array(
		'value' => '17',
		'label' => '17',
		'_type' => 'option',
	),
	array(
		'value' => '18',
		'label' => '18',
		'_type' => 'option',
	),
	array(
		'value' => '19',
		'label' => '19',
		'_type' => 'option',
	),
	array(
		'value' => '20',
		'label' => '20',
		'_type' => 'option',
	),
	array(
		'value' => '21',
		'label' => '21',
		'_type' => 'option',
	),
	array(
		'value' => '22',
		'label' => '22',
		'_type' => 'option',
	),
	array(
		'value' => '23',
		'label' => '23',
		'_type' => 'option',
	),
	array(
		'value' => '24',
		'label' => '24',
		'_type' => 'option',
	),
	array(
		'value' => '25',
		'label' => '25',
		'_type' => 'option',
	),
	array(
		'value' => '26',
		'label' => '26',
		'_type' => 'option',
	),
	array(
		'value' => '27',
		'label' => '27',
		'_type' => 'option',
	),
	array(
		'value' => '28',
		'label' => '28',
		'_type' => 'option',
	),
	array(
		'value' => '29',
		'label' => '29',
		'_type' => 'option',
	),
	array(
		'value' => '30',
		'label' => '30',
		'_type' => 'option',
	),
	array(
		'value' => '31',
		'label' => '31',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	),
	array(
		'value' => 'dow',
		'label' => 'Day of the week' . $__vars['xf']['language']['label_separator'],
		'name' => $__vars['inputName'] . '[type]',
		'_dependent' => array($__templater->formSelect(array(
		'value' => $__vars['option']['option_value']['day_type'],
		'name' => $__vars['inputName'] . '[day_type]',
		'multiple' => 'true',
		'size' => '8',
		'style' => 'width: 200px',
	), array(array(
		'value' => '-1',
		'label' => 'Any',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => 'Sunday',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Monday',
		'_type' => 'option',
	),
	array(
		'value' => '2',
		'label' => 'Tuesday',
		'_type' => 'option',
	),
	array(
		'value' => '3',
		'label' => 'Wednesday',
		'_type' => 'option',
	),
	array(
		'value' => '4',
		'label' => 'Thursday',
		'_type' => 'option',
	),
	array(
		'value' => '5',
		'label' => 'Friday',
		'_type' => 'option',
	),
	array(
		'value' => '6',
		'label' => 'Saturday',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	)), array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
		'rowclass' => $__vars['rowClass'],
	));
	return $__finalCompiled;
}
);