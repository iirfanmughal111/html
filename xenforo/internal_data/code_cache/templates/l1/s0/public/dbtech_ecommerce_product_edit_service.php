<?php
// FROM HASH: cb5ac7e3bfbea1249b27c97d2abb9d19
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
				' . 'Product variation' . '
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
			' . $__templater->formTextBoxRow(array(
		'name' => 'cost_title[' . $__vars['cost']['product_cost_id'] . ']',
		'value' => $__vars['cost']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['cost'], 'title', ), false),
		'required' => 'true',
	), array(
		'label' => 'Title',
	)) . '

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
					' . 'Product variation' . '
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
				' . $__templater->formTextBoxRow(array(
		'name' => 'cost_title[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']',
		'value' => $__templater->func('mustache', array('title', ), false),
		'maxlength' => '100',
		'required' => 'true',
	), array(
		'label' => 'Title',
	)) . '

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

<div data-xf-init="dbtech-ecommerce-product-pricing-manager" data-manage-url="' . $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/cost-row', $__vars['product'], ), true) . '" data-product-type="dbtech_ecommerce_service">
	' . $__templater->callMacro(null, 'cost_row_list', array(
		'costs' => $__vars['product']['Costs'],
	), $__vars) . '

	<h3 class="block-formSectionHeader">
		<span class="block-formSectionHeader-aligner">
			' . 'Product variations' . '
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
				' . 'Add variation' . '
			', array(
		'type' => 'button',
		'class' => 'button--link js-addProductPricing',
		'icon' => 'add',
	), '', array(
	)) . '
		', array(
		'rowtype' => 'input',
	)) . '
	</div>
</div>

' . '

' . '

';
	return $__finalCompiled;
}
);