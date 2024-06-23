<?php
// FROM HASH: 58f3fd79d4a4da74a2a24e629d20f085
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['coupon'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add coupon');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit coupon' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['coupon']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['coupon'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/coupons/delete', $__vars['coupon'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__vars['productsByCategory'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductsByCategory', array());
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['coupon'], 'isInsert', array())) {
		$__compilerTemp1 .= '
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
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->formRow('

					<div class="inputGroup">
						' . $__templater->formDateInput(array(
			'name' => 'expiry_date',
			'value' => ($__vars['coupon']['expiry_date'] ? $__templater->func('date', array($__vars['coupon']['expiry_date'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
		)) . '
						<span class="inputGroup-splitter"></span>
						' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'expiry_time',
			'value' => ($__vars['coupon']['expiry_date'] ? $__templater->func('date', array($__vars['coupon']['expiry_date'], 'H:i', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'H:i', ), false)),
		)) . '
					</div>
				', array(
			'rowtype' => 'input',
			'label' => 'Valid to',
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['coupon']['product_discounts'])) {
		foreach ($__vars['coupon']['product_discounts'] AS $__vars['counter'] => $__vars['discountInfo']) {
			$__compilerTemp2 .= '
						<li class="inputPair">
							<div class="inputGroup">
								' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
				'inputName' => 'product_discounts[' . $__vars['counter'] . '][product_id]',
				'productsByCategory' => $__vars['productsByCategory'],
				'productId' => $__vars['discountInfo']['product_id'],
				'row' => false,
				'class' => 'filterBlock-input',
				'includeBlank' => false,
				'includeNone' => true,
			), $__vars) . '

								<span class="inputGroup-splitter"></span>

								' . $__templater->formNumberBox(array(
				'name' => 'product_discounts[' . $__vars['counter'] . '][product_value]',
				'min' => '0',
				'value' => $__vars['discountInfo']['product_value'],
				'step' => 'any',
				'required' => false,
			)) . '
							</div>
						</li>
					';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => ($__templater->method($__vars['coupon'], 'exists', array()) ? $__vars['coupon']['MasterTitle']['phrase_text'] : ''),
	), array(
		'label' => 'Title',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'coupon_code',
		'value' => $__vars['coupon']['coupon_code'],
		'maxlength' => $__templater->func('max_length', array('DBTech\\eCommerce:Coupon', 'coupon_code', ), false),
	), array(
		'label' => 'Coupon code',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'coupon_type',
		'value' => $__vars['coupon']['coupon_type'],
	), array(array(
		'value' => 'percent',
		'label' => 'Percent',
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => 'coupon_percent',
		'value' => $__vars['coupon']['coupon_percent'],
		'min' => '0',
		'max' => '100',
		'step' => 'any',
	)) . '
							<span class="inputGroup-text">%</span>
						</div>
					'),
		'_type' => 'option',
	),
	array(
		'value' => 'value',
		'label' => 'Flat value',
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => 'coupon_value',
		'value' => $__vars['coupon']['coupon_value'],
		'min' => '0',
		'step' => 'any',
	)) . '
							<span class="inputGroup-text">' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '</span>
						</div>
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Coupon type',
		'explain' => 'Determines what sort of discount is applied to the customer\'s cart; a percent off their total, or a set discount.',
	)) . '

			' . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'start_date',
		'value' => ($__vars['coupon']['start_date'] ? $__templater->func('date', array($__vars['coupon']['start_date'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formTextBox(array(
		'type' => 'time',
		'name' => 'start_time',
		'value' => ($__vars['coupon']['start_date'] ? $__templater->func('date', array($__vars['coupon']['start_date'], 'H:i', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'H:i', ), false)),
	)) . '
				</div>
			', array(
		'label' => 'Valid from',
		'rowtype' => 'input',
	)) . '

			' . $__compilerTemp1 . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'remaining_uses',
		'value' => $__vars['coupon']['remaining_uses'],
		'min' => '-1',
		'step' => '1',
	), array(
		'label' => 'Remaining uses',
		'explain' => 'This setting determines how many uses this coupon has remaining before it is no longer available for use.<br />
-1 = Unlimited uses',
	)) . '

			' . $__templater->formRow('

				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'minimum_products',
		'value' => $__vars['coupon']['minimum_products'],
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'maximum_products',
		'value' => $__vars['coupon']['maximum_products'],
		'min' => '0',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Number of products in cart between',
		'explain' => 'Use 0 to specify no maximum.',
	)) . '

			' . $__templater->formRow('

				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'minimum_cart_value',
		'value' => $__vars['coupon']['minimum_cart_value'],
		'min' => '0',
		'step' => 'any',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'maximum_cart_value',
		'value' => $__vars['coupon']['maximum_cart_value'],
		'min' => '0',
		'step' => 'any',
	)) . '
					<span class="inputGroup-text">' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '</span>
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Cart value between',
		'explain' => 'Use 0 to specify no maximum.',
	)) . '

			' . $__templater->formRow('

				<ul class="listPlain inputPair-container">
					' . $__compilerTemp2 . '
					<li class="inputPair" data-xf-init="field-adder" data-increment-format="product_discounts[{counter}]">
						<div class="inputGroup">
							' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
		'inputName' => 'product_discounts[' . $__vars['nextCounter'] . '][product_id]',
		'productsByCategory' => $__vars['productsByCategory'],
		'productId' => '',
		'row' => false,
		'class' => 'filterBlock-input',
		'includeBlank' => false,
		'includeNone' => true,
	), $__vars) . '

							<span class="inputGroup-splitter"></span>

							' . $__templater->formNumberBox(array(
		'name' => 'product_discounts[' . $__vars['nextCounter'] . '][product_value]',
		'min' => '0',
		'step' => 'any',
		'required' => false,
	)) . '
						</div>
					</li>
				</ul>
			', array(
		'rowtype' => 'input',
		'label' => 'Applicable products',
		'explain' => 'Leaving the value field blank will use the "Coupon type" value chosen above.<br />
Set product to "None" to remove a row upon saving.<br />
If no products are chosen, this coupon applies to <b>all</b> products.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/coupons/save', $__vars['coupon'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);