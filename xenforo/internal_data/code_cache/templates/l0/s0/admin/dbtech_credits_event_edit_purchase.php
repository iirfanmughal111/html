<?php
// FROM HASH: 9f47fa0dbf8b27e5da2a562f8e8686aa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextAreaRow(array(
		'name' => 'settings[purchase_description]',
		'value' => $__vars['event']['settings']['purchase_description'],
		'autosize' => 'true',
	), array(
		'label' => 'Description',
		'hint' => 'You may use BB code',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'settings[purchase_cost]',
		'value' => $__vars['event']['settings']['purchase_cost'],
		'step' => 'any',
	), array(
		'label' => 'Cost',
		'explain' => 'Enter the cost (in real-world money) of the credits you are selling here.',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'settings[purchase_amount]',
		'value' => $__vars['event']['settings']['purchase_amount'],
		'step' => 'any',
	), array(
		'label' => 'Amount',
		'explain' => 'Enter the amount of credits you are selling here.',
	)) . '

';
	$__vars['paymentProfiles'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('XF:Payment', )), 'getPaymentProfileTitlePairs', array());
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['paymentProfiles']);
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'settings[payment_profile_ids][]',
		'multiple' => 'true',
		'size' => '8',
		'value' => ($__vars['event']['settings']['payment_profile_ids'] ? $__vars['event']['settings']['payment_profile_ids'] : array()),
	), $__compilerTemp1, array(
		'label' => 'Payment profile',
	)) . '
';
	return $__finalCompiled;
}
);