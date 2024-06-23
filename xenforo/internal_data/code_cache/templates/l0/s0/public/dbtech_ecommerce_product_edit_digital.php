<?php
// FROM HASH: 07a7fac3241e0b1804398651878bf61d
return array(
'macros' => array('cost_row' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'cost' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="js-productCost" data-cost-id="' . $__templater->escape($__vars['cost']['product_cost_id']) . '">

		<h3 class="block-formSectionHeader">
			<span class="block-formSectionHeader-aligner">
				' . 'Product pricing tier' . '
			</span>
			<span class="contentRow-extra u-jsOnly">
				' . $__templater->button('
					' . 'Delete' . '
				', array(
		'class' => 'button--small js-pricingAction',
		'data-action' => 'delete',
	), '', array(
	)) . '
			</span>
		</h3>
		<div class="block-body">
			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'cost_amount[' . $__vars['cost']['product_cost_id'] . ']',
		'value' => $__vars['cost']['cost_amount'],
		'min' => '0',
		'step' => 'any',
		'class' => 'input--numberNarrow',
	)) . '
					<span class="inputGroup-text">' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '</span>
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Cost',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'renewal_type[' . $__vars['cost']['product_cost_id'] . ']',
		'value' => $__vars['cost']['renewal_type'],
	), array(array(
		'value' => 'global',
		'label' => 'Global defaults',
		'hint' => 'This will use the "License renewal discount" or "Expired license renewal discount" options set in the XenForo options.',
		'_type' => 'option',
	),
	array(
		'value' => 'fixed',
		'label' => 'Fixed renewal cost',
		'data-hide' => 'true',
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => 'renewal_amount[' . $__vars['cost']['product_cost_id'] . ']',
		'value' => $__vars['cost']['renewal_amount'],
		'min' => '0',
		'step' => 'any',
		'class' => 'input--numberNarrow',
	)) . '
							<span class="inputGroup-text">' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '</span>
						</div>
					'),
		'_type' => 'option',
	),
	array(
		'value' => 'percentage',
		'label' => 'Percentage',
		'data-hide' => 'true',
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => 'renewal_amount[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'value' => $__templater->func('mustache', array('renewal_amount', ), false),
		'min' => '0',
		'step' => 'any',
		'class' => 'input--numberNarrow',
	)) . '
							<span class="inputGroup-text">%</span>
						</div>
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Renewal cost',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'length_type[' . $__vars['cost']['product_cost_id'] . ']',
	), array(array(
		'value' => 'permanent',
		'selected' => $__vars['cost']['length_unit'] == '',
		'label' => 'Permanent',
		'_type' => 'option',
	),
	array(
		'value' => 'timed',
		'selected' => $__vars['cost']['length_unit'] != '',
		'label' => 'For length' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => 'length_amount[' . $__vars['cost']['product_cost_id'] . ']',
		'value' => ($__vars['cost']['length_amount'] ?: 1),
		'min' => '1',
		'max' => '365',
		'class' => 'input--numberNarrow',
	)) . '
							<span class="inputGroup-splitter"></span>
							' . $__templater->formSelect(array(
		'name' => 'length_unit[' . $__vars['cost']['product_cost_id'] . ']',
		'value' => ((($__vars['cost']['length_unit'] == 'permanent') OR (!$__vars['cost']['length_amount'])) ? 'month' : $__vars['cost']['length_unit']),
		'class' => 'input--autoSize',
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

					'),
		'_type' => 'option',
	)), array(
		'label' => 'License duration',
		'explain' => 'The length determines how long the user will receive updates for this product if they choose to purchase the product at that pricing tier.',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'cost_description[' . $__vars['cost']['product_cost_id'] . ']',
		'value' => $__vars['cost']['description'],
		'maxlength' => $__templater->func('max_length', array($__vars['cost'], 'description', ), false),
	), array(
		'label' => 'Description',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'highlighted',
	), array(array(
		'value' => $__vars['cost']['product_cost_id'],
		'selected' => $__vars['cost']['highlighted'],
		'label' => 'Highlighted',
		'hint' => 'The highlighted option will be shown in emphasised font, and will display a "Most value!" label. It will also be the default selected option.',
		'_type' => 'option',
	)), array(
	)) . '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'cost_row_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'costs' => array(),
		'listClass' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('dbtech_ecommerce_product_pricing.less');
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'DBTech/eCommerce/product_pricing.js',
		'addon' => 'DBTech/eCommerce',
		'min' => '1',
	));
	$__finalCompiled .= '

	<div class="productCostList ' . $__templater->escape($__vars['listClass']) . ' js-productCosts">
		';
	if ($__templater->isTraversable($__vars['costs'])) {
		foreach ($__vars['costs'] AS $__vars['cost']) {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'cost_row', array(
				'cost' => $__vars['cost'],
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '
	</div>
	' . $__templater->callMacro(null, 'cost_row_template', array(), $__vars) . '
';
	return $__finalCompiled;
}
),
'cost_row_template' => array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<script type="text/template" class="js-pricingTemplate">
		<div class="js-productCost" data-cost-id="' . $__templater->func('mustache', array('product_cost_id', ), true) . '">
			<h3 class="block-formSectionHeader">
				<span class="block-formSectionHeader-aligner">
					' . 'Product pricing tier' . '
				</span>
				<span class="contentRow-extra u-jsOnly">
					' . $__templater->button('
						' . 'Delete' . '
					', array(
		'class' => 'button--small js-pricingAction',
		'data-action' => 'delete',
	), '', array(
	)) . '
				</span>
			</h3>
			<div class="block-body">
				' . $__templater->formRow('
					<div class="inputGroup">
						' . $__templater->formNumberBox(array(
		'name' => 'cost_amount[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'value' => $__templater->func('mustache', array('cost_amount', ), false),
		'min' => '0',
		'step' => 'any',
		'class' => 'input--numberNarrow',
	)) . '
						<span class="inputGroup-text">' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '</span>
					</div>
				', array(
		'rowtype' => 'input',
		'label' => 'Cost',
	)) . '

				' . $__templater->formRadioRow(array(
		'name' => 'renewal_type[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'value' => 'global',
	), array(array(
		'value' => 'global',
		'label' => 'Global defaults',
		'hint' => 'This will use the "License renewal discount" or "Expired license renewal discount" options set in the XenForo options.',
		'_type' => 'option',
	),
	array(
		'value' => 'fixed',
		'label' => 'Fixed renewal cost',
		'data-hide' => 'true',
		'_dependent' => array('
							<div class="inputGroup">
								' . $__templater->formNumberBox(array(
		'name' => 'renewal_amount[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'value' => $__templater->func('mustache', array('renewal_amount', ), false),
		'min' => '0',
		'step' => 'any',
		'class' => 'input--numberNarrow',
	)) . '
								<span class="inputGroup-text">' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '</span>
							</div>
						'),
		'_type' => 'option',
	),
	array(
		'value' => 'percentage',
		'label' => 'Percentage',
		'data-hide' => 'true',
		'_dependent' => array('
							<div class="inputGroup">
								' . $__templater->formNumberBox(array(
		'name' => 'renewal_amount[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'value' => $__templater->func('mustache', array('renewal_amount', ), false),
		'min' => '0',
		'step' => 'any',
		'class' => 'input--numberNarrow',
	)) . '
								<span class="inputGroup-text">%</span>
							</div>
						'),
		'_type' => 'option',
	)), array(
		'label' => 'Renewal cost',
	)) . '

				' . $__templater->formRadioRow(array(
		'name' => 'length_type[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
	), array(array(
		'value' => 'permanent',
		'selected' => true,
		'label' => 'Permanent',
		'_type' => 'option',
	),
	array(
		'value' => 'timed',
		'label' => 'For length' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array('
							<div class="inputGroup">
								' . $__templater->formNumberBox(array(
		'name' => 'length_amount[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'value' => '1',
		'min' => '1',
		'max' => '365',
		'class' => 'input--numberNarrow',
	)) . '
								<span class="inputGroup-splitter"></span>
								' . $__templater->formSelect(array(
		'name' => 'length_unit[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'value' => 'month',
		'class' => 'input--autoSize',
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

						'),
		'_type' => 'option',
	)), array(
		'label' => 'License duration',
		'explain' => 'The length determines how long the user will receive updates for this product if they choose to purchase the product at that pricing tier.',
	)) . '

				' . $__templater->formTextBoxRow(array(
		'name' => 'cost_description[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'maxlength' => '255',
	), array(
		'label' => 'Description',
	)) . '

				' . $__templater->formRadioRow(array(
		'name' => 'highlighted',
	), array(array(
		'value' => $__templater->func('mustache', array('product_cost_id', ), false),
		'label' => 'Highlighted',
		'hint' => 'The highlighted option will be shown in emphasised font, and will display a "Most value!" label. It will also be the default selected option.',
		'_type' => 'option',
	)), array(
	)) . '
			</div>
		</div>
	</script>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<h3 class="block-formSectionHeader">
	<span class="block-formSectionHeader-aligner">' . 'Options for digital products' . '</span>
</h3>
<div class="block-body">
	' . $__templater->formTextBoxRow(array(
		'name' => 'license_prefix',
		'value' => $__vars['product']['license_prefix'],
	), array(
		'label' => 'License prefix',
		'explain' => 'String to uniquely identify this product and will prefix the customer\'s unique licence number.',
	)) . '

	';
	if (!$__templater->method($__vars['product'], 'isAddOn', array())) {
		$__finalCompiled .= '
		';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['product']['product_versions'])) {
			foreach ($__vars['product']['product_versions'] AS $__vars['version'] => $__vars['text']) {
				$__compilerTemp1 .= '
					<div class="inputGroup">
						<span class="inputGroup-text dragHandle"
							  aria-label="' . $__templater->filter('Drag handle', array(array('for_attr', array()),), true) . '"></span>
						' . $__templater->formTextBox(array(
					'name' => 'product_version[]',
					'value' => $__vars['version'],
					'placeholder' => 'Value (A-Z, 0-9, and _ only)',
					'size' => '24',
					'maxlength' => '25',
					'dir' => 'ltr',
				)) . '
						<span class="inputGroup-splitter"></span>
						' . $__templater->formTextBox(array(
					'name' => 'product_version_text[]',
					'value' => $__vars['text'],
					'placeholder' => 'Text',
					'size' => '24',
				)) . '
					</div>
				';
			}
		}
		$__finalCompiled .= $__templater->formRow('

			<div class="inputGroup-container" data-xf-init="list-sorter" data-drag-handle=".dragHandle">
				' . $__compilerTemp1 . '
				<div class="inputGroup is-undraggable js-blockDragafter" data-xf-init="field-adder"
					 data-remove-class="is-undraggable js-blockDragafter">
					<span class="inputGroup-text dragHandle"
						  aria-label="' . $__templater->filter('Drag handle', array(array('for_attr', array()),), true) . '"></span>
					' . $__templater->formTextBox(array(
			'name' => 'product_version[]',
			'placeholder' => 'Value (A-Z, 0-9, and _ only)',
			'size' => '24',
			'maxlength' => '25',
			'data-i' => '0',
			'dir' => 'ltr',
		)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formTextBox(array(
			'name' => 'product_version_text[]',
			'placeholder' => 'Text',
			'size' => '24',
			'data-i' => '0',
		)) . '
				</div>
			</div>
		', array(
			'rowtype' => 'input',
			'label' => 'Downloadable versions',
			'explain' => 'The value represents the internal value for the version. The text field is shown when the selection is displayed. You should not change the value field if any users have downloaded this product; if you do, old log entries will display invalid data.',
		)) . '
	';
	}
	$__finalCompiled .= '

	' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'has_demo',
		'value' => '1',
		'selected' => $__vars['product']['has_demo'],
		'label' => '
			' . 'Has demo version' . '
		',
		'_type' => 'option',
	)), array(
		'explain' => 'Determines whether this product has a demo (Lite) version available.',
	)) . '
</div>

';
	$__vars['noHighlight'] = true;
	$__finalCompiled .= '
';
	if ($__templater->isTraversable($__vars['product']['Costs'])) {
		foreach ($__vars['product']['Costs'] AS $__vars['cost']) {
			$__finalCompiled .= '
	';
			if ($__vars['cost']['highlighted']) {
				$__finalCompiled .= '
		';
				$__vars['noHighlight'] = false;
				$__finalCompiled .= '
	';
			}
			$__finalCompiled .= '
';
		}
	}
	$__finalCompiled .= '

<div data-xf-init="dbtech-ecommerce-product-pricing-manager" data-manage-url="' . $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/cost-row', $__vars['product'], ), true) . '" data-product-type="dbtech_ecommerce_digital">
	' . $__templater->callMacro(null, 'cost_row_list', array(
		'costs' => $__vars['product']['Costs'],
	), $__vars) . '

	<h3 class="block-formSectionHeader">
		<span class="block-formSectionHeader-aligner">
			' . 'Product pricing tier options' . '
		</span>
	</h3>
	<div class="block-body">
		' . $__templater->formRadioRow(array(
		'name' => 'highlighted',
	), array(array(
		'value' => '0',
		'selected' => $__vars['noHighlight'],
		'label' => 'No highlighted option',
		'hint' => 'Select this option to remove the currently selected highlighted option without setting a new one.',
		'_type' => 'option',
	)), array(
	)) . '

		' . $__templater->formRow('

			' . $__templater->button('
				' . 'Add pricing tier' . '
			', array(
		'type' => 'button',
		'class' => 'button--link js-addProductPricing',
		'icon' => 'add',
	), '', array(
	)) . '
		', array(
		'rowtype' => 'input',
		'explain' => 'The length determines how long the user will receive updates for this product if they choose to purchase the product at that pricing tier.',
	)) . '
	</div>
</div>

' . '

' . '

';
	return $__finalCompiled;
}
);