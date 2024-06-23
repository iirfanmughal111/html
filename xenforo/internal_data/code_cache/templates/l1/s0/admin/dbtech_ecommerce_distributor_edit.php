<?php
// FROM HASH: b7d95c162ee714bec9772c91c161a49a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['distributor'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add distributor');
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit distributor' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['distributor']['User']['username']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['distributor'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/distributors/delete', $__vars['distributor'], ), false),
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
	$__vars['i'] = 0;
	if ($__templater->isTraversable($__vars['distributor']['Products'])) {
		foreach ($__vars['distributor']['Products'] AS $__vars['map']) {
			$__vars['i']++;
			$__compilerTemp1 .= '
						<li class="inputPair">
							<div class="inputGroup">
								' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
				'inputName' => 'available_products[' . $__vars['i'] . '][product_id]',
				'productsByCategory' => $__vars['productsByCategory'],
				'productId' => $__vars['map']['product_id'],
				'row' => false,
				'class' => 'filterBlock-input',
				'includeBlank' => false,
				'includeNone' => true,
			), $__vars) . '

								<span class="inputGroup-splitter"></span>

								' . $__templater->formNumberBox(array(
				'name' => 'available_products[' . $__vars['i'] . '][available_licenses]',
				'min' => '-1',
				'value' => $__vars['map']['available_licenses'],
				'step' => '1',
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
		'name' => 'username',
		'ac' => 'single',
		'value' => $__vars['distributor']['User']['username'],
	), array(
		'label' => 'Username',
	)) . '

			' . $__templater->formRow('

				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'license_length_amount',
		'value' => ($__vars['distributor']['license_length_amount'] ?: 7),
		'min' => '1',
		'max' => '255',
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formSelect(array(
		'name' => 'license_length_unit',
		'value' => ($__vars['distributor']['license_length_unit'] ?: 'day'),
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
		'label' => 'Maximum license length',
	)) . '

			' . $__templater->formRow('

				<ul class="listPlain inputPair-container">
					' . $__compilerTemp1 . '
					<li class="inputPair" data-xf-init="field-adder" data-increment-format="available_products[{counter}]">
						<div class="inputGroup">
							' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
		'inputName' => 'available_products[' . $__vars['nextCounter'] . '][product_id]',
		'productsByCategory' => $__vars['productsByCategory'],
		'productId' => '',
		'row' => false,
		'class' => 'filterBlock-input',
		'includeBlank' => false,
		'includeNone' => true,
	), $__vars) . '

							<span class="inputGroup-splitter"></span>

							' . $__templater->formNumberBox(array(
		'name' => 'available_products[' . $__vars['nextCounter'] . '][available_licenses]',
		'min' => '-1',
		'step' => '1',
		'data-default-value' => '-1',
		'required' => false,
	)) . '
						</div>
					</li>
				</ul>
			', array(
		'rowtype' => 'input',
		'label' => 'Applicable products',
		'explain' => 'Choose the product and the number of licenses they should be able to generate.<br />
Set product to "None" to remove a row upon saving.<br />
Set the value field to <code>-1</code> to allow unlimited licenses to be generated for this product.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/distributors/save', $__vars['distributor'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);