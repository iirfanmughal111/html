<?php
// FROM HASH: 7509b4574ff5c3027f7a7e29379841f5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Extend licenses');
	$__finalCompiled .= '

';
	$__vars['productsByCategory'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductsByCategory', array());
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('

				<ul class="inputList">
					<li data-xf-init="field-adder">
						' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
		'inputName' => 'product_ids[]',
		'productsByCategory' => $__vars['productsByCategory'],
		'productId' => '',
		'row' => false,
		'class' => 'filterBlock-input',
		'includeBlank' => false,
		'includeNone' => true,
		'licensesOnly' => true,
	), $__vars) . '
					</li>
				</ul>
			', array(
		'rowtype' => 'input',
		'label' => 'Applicable products',
		'explain' => 'Set product to "None" to skip a row upon saving.<br />
If no products are chosen, the extension applies to <b>all</b> products.',
	)) . '

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
		'label' => 'Extend licenses for' . $__vars['xf']['language']['ellipsis'],
		'rowclass' => 'formRow--noColon',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'refresh_expired',
		'value' => '1',
		'label' => 'Refresh expired licenses',
		'hint' => 'This will reset the expiry date of expired licenses to the current time, then apply the above extension.<br />
Leave unticked and the extension will always be applied to the license\'s current expiry date, regardless of what it is.',
		'_type' => 'option',
	)), array(
	)) . '

		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'submit',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/licenses/extend', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);