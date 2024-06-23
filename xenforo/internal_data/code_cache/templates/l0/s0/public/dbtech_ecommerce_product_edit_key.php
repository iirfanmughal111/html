<?php
// FROM HASH: 2460ac6eae25da7514900d0d7c535cb8
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

			' . $__templater->formHiddenVal('length_type[' . $__vars['cost']['product_cost_id'] . ']', 'permanent', array(
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

				' . $__templater->formHiddenVal('length_type[' . $__templater->func('mustache', array('product_cost_id', ), false) . ']', 'permanent', array(
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
	<span class="block-formSectionHeader-aligner">' . 'Options for serial key products' . '</span>
</h3>
<div class="block-body">
	' . $__templater->formTextBoxRow(array(
		'name' => 'license_prefix',
		'value' => $__vars['product']['license_prefix'],
	), array(
		'label' => 'License prefix',
		'explain' => 'String to uniquely identify this product and will prefix the customer\'s unique licence number.',
	)) . '

	' . $__templater->formRow('
		<h4 class="block-textHeader">
			' . 'Serial key formula' . '
		</h4>

		' . $__templater->formTextBox(array(
		'name' => 'product_type_data[serial_key_formula]',
		'value' => $__vars['product']['product_type_data']['serial_key_formula'],
		'placeholder' => 'Serial key formula',
	)) . '

		<div class="formRow-explain">' . 'The following replacement variables are available:
<ul>
<li><code>{d}</code> - a number from 0-9</li>
<li><code>{w}</code> - a lowercase letter from a-z</li>
<li><code>{W}</code> - an uppercase letter from A-Z</li>
</ul>
<strong>Example:</strong> <code>{d}{W}{d}{W}{d}-{d}{W}{d}{W}{d}-{d}{d}{d}{W}{W}-{W}{W}{W}{d}{d}-{W}{d}{d}{d}{W}</code>' . '</div>
	', array(
		'rowtype' => 'input',
		'label' => 'Serial key type',
	)) . '

	' . $__templater->formRow('
		<div class="blocks-textJoiner"><span></span><em>' . 'or' . '</em><span></span></div>
	', array(
	)) . '

	' . $__templater->formRow('
		<h4 class="block-textHeader">
			' . 'Serial key list' . '
		</h4>

		' . $__templater->formTextArea(array(
		'name' => 'product_type_data[serial_key_list]',
		'rows' => '4',
		'autosize' => 'true',
		'value' => $__vars['product']['product_type_data']['serial_key_list'],
	)) . '

		<div class="formRow-explain">' . 'This is a pre-defined list of serial keys. One key per line. The list will be automatically updated as keys are sold. Keys from orders that have been refunded are <strong>NOT</strong> automatically re-added.<br />
<br />
Please note that it is your responsibility to ensure this list has enough keys to meet demand. If there is only one key remaining, and two people place orders within a short period of time, it is possible payment is taken from both customers but the slower order will not have a valid serial key to assign to the customer.' . '</div>
	', array(
		'rowtype' => 'input',
	)) . '
</div>

<div data-xf-init="dbtech-ecommerce-product-pricing-manager" data-manage-url="' . $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/cost-row', $__vars['product'], ), true) . '" data-product-type="dbtech_ecommerce_key">
	' . $__templater->callMacro(null, 'cost_row_list', array(
		'costs' => $__vars['product']['Costs'],
	), $__vars) . '

	<h3 class="block-formSectionHeader">
		<span class="block-formSectionHeader-aligner">
			' . 'Product variations' . '
		</span>
	</h3>
	<div class="block-body">
		' . $__templater->formRow('

			' . $__templater->button('
				' . 'Add variation' . '
			', array(
		'type' => 'button',
		'class' => 'button--link js-addProductPricing',
		'icon' => 'add',
	), '', array(
	)) . '

			<div class="formRow-explain">' . 'This product type only needs one variation for pricing purposes. You can add more if you want, but it\'s strongly recommended to only add one variation.' . '</div>
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