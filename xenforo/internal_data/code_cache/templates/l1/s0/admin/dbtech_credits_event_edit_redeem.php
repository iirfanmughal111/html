<?php
// FROM HASH: 8edfc35793c434729dbd4c806f2f38f6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formNumberBoxRow(array(
		'name' => 'settings[redeem_amount]',
		'value' => $__vars['event']['settings']['redeem_amount'],
		'step' => 'any',
	), array(
		'label' => 'Redemption reward',
		'explain' => 'The amount of currency that is awarded (or taken away) when this redemption code is used.',
	)) . '

' . $__templater->formDateInputRow(array(
		'name' => 'settings[redeem_startdate]',
		'value' => ($__vars['event']['settings']['redeem_startdate'] ? $__templater->func('date', array($__vars['event']['settings']['redeem_startdate'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
	), array(
		'label' => 'Start date',
		'explain' => 'Earliest time that this redemption code will be valid.',
	)) . '

' . $__templater->formDateInputRow(array(
		'name' => 'settings[redeem_enddate]',
		'value' => ($__vars['event']['settings']['redeem_enddate'] ? $__templater->func('date', array($__vars['event']['settings']['redeem_enddate'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
	), array(
		'label' => 'End date',
		'explain' => 'The date on which the redemption code will no longer be valid.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'settings[redeem_code]',
		'value' => $__vars['event']['settings']['redeem_code'],
	), array(
		'label' => 'Redemption code',
		'explain' => 'This code must be entered through the currency popup in order to be redeemed.<br />
The code is case sensitive and should be unique!',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'settings[redeem_maxtimes]',
		'value' => $__vars['event']['settings']['redeem_maxtimes'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Maximum redemptions per user',
		'explain' => 'The total number of times this can be redeemed per user.<br />
0 = unlimited',
	)) . '

' . $__templater->formNumberBoxRow(array(
		'name' => 'settings[redeem_maxusers]',
		'value' => $__vars['event']['settings']['redeem_maxusers'],
		'min' => '0',
		'step' => '1',
	), array(
		'label' => 'Maximum unique redemptions',
		'explain' => 'The total number of different users that can redeem this code.<br />
0 = unlimited',
	)) . '
';
	return $__finalCompiled;
}
);