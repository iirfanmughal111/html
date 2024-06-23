<?php
// FROM HASH: 61866ae03fe78c88e8f08da0b01022ae
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['inputName'] . '[required]',
		'value' => '1',
		'selected' => $__vars['option']['option_value']['required'],
		'data-hide' => 'true',
		'label' => 'Require address during checkout',
		'hint' => 'If disabled, customers will not be asked for their address during checkout. Address is always required in the following circumstances: <br />
&middot; The order contains a physical product<br />
&middot; Sales tax is enabled, and the order total is greater than 0',
		'_dependent' => array($__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[onlyPaid]',
		'selected' => $__vars['option']['option_value']['onlyPaid'],
		'label' => 'Only for paid orders',
		'hint' => 'If disabled, orders consisting only of free products will not require an address.',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['inputName'] . '[validate]',
		'selected' => $__vars['option']['option_value']['validate'],
		'label' => 'Validate billing country before payment',
		'hint' => 'If this option is enabled, the user\'s current IP address will be compared to the country they chose in their billing address during checkout.<br />
The order will be halted if the countries do not match.',
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