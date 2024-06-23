<?php
// FROM HASH: 9015959ed66b21cca3e520f4c91be1f9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add coupon to order');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'coupon_percent',
		'value' => '10',
		'min' => '1',
		'max' => '100',
		'step' => 'any',
	)) . '
					<span class="inputGroup-text">%</span>
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Percent',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('

				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'length_amount',
		'value' => '7',
		'min' => '1',
		'max' => '255',
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formSelect(array(
		'name' => 'length_unit',
		'value' => 'day',
		'class' => 'input--inline',
	), array(array(
		'value' => 'day',
		'label' => 'Days',
		'_type' => 'option',
	),
	array(
		'value' => 'month',
		'label' => 'Months',
		'_type' => 'option',
	),
	array(
		'value' => 'year',
		'label' => 'Years',
		'_type' => 'option',
	))) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Valid for',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/orders/apply-coupon', $__vars['order'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);