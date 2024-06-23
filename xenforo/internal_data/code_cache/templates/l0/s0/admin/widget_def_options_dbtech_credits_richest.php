<?php
// FROM HASH: d5f3cbc237baa26528a07b7bd76c6837
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Users limit',
		'explain' => 'Controls the number of users that can be shown in this widget.',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[showAmounts]',
		'value' => '1',
		'selected' => $__vars['options']['showAmounts'],
		'hint' => 'If disabled, the amount of credits each user has will not be displayed.',
		'label' => 'Show amounts',
		'_type' => 'option',
	)), array(
	)) . '

';
	$__compilerTemp1 = array(array(
		'value' => '',
		'label' => 'All currencies',
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['currencies']);
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[currencyIds][]',
		'value' => ($__vars['options']['currencyIds'] ?: ''),
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp1, array(
		'label' => 'Currency limit',
		'explain' => 'Only the currencies selected here will be included.',
	));
	return $__finalCompiled;
}
);