<?php
// FROM HASH: 39e6963cd6f157e76e9aab5dd16436f0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['inputName'] . '[enabled]',
		'label' => 'Enable sales',
		'value' => '1',
		'selected' => $__vars['option']['option_value']['enabled'],
		'data-hide' => 'true',
		'_dependent' => array($__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[alwaysShow]',
		'selected' => $__vars['option']['option_value']['alwaysShow'],
		'label' => 'Always show sale discounts in sub-total',
		'hint' => 'Whether to always display "Sale discounts" in the sub-total, even if no sale discounts are applied.',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	)), array(
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);