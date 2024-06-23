<?php
// FROM HASH: 501d726a2e93ba6da91493f1c850b74e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'value' => '',
		'label' => '&nbsp;',
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['currencies']);
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[currency_id]',
		'value' => ($__vars['profile']['options']['currency_id'] ?: ''),
	), $__compilerTemp1, array(
		'label' => 'Currency',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[exchange_rate]',
		'value' => ($__vars['profile']['options']['exchange_rate'] ?: 1),
		'min' => '0',
		'step' => 'any',
	), array(
		'label' => 'Exchange rate',
		'explain' => 'The value of your DragonByte Credits currency vs. the real-world cost of whatever item you apply this payment profile to.<br />
For example, if this payment profile is added to a User Upgrade that costs <strong>$4.99</strong> and you set this number to <code>100</code>, the user will be charged <strong>499 points</strong>.',
	));
	return $__finalCompiled;
}
);