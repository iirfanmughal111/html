<?php
// FROM HASH: d5c6abc1b28a4cbe8e5a7e01a368b421
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('dbtech_ecommerce.less');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Checkout'), $__templater->func('link', array('dbtech-ecommerce/checkout', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Choose shipping method');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['item']['Order']['ShippingAddress']['ApplicableShippingMethods'])) {
		foreach ($__vars['item']['Order']['ShippingAddress']['ApplicableShippingMethods'] AS $__vars['shippingCombination']) {
			if ($__templater->method($__vars['shippingCombination'], 'isApplicableToProduct', array($__vars['item']['Product'], ))) {
				$__compilerTemp1[] = array(
					'value' => $__vars['shippingCombination']['shipping_method_id'],
					'label' => $__templater->escape($__vars['shippingCombination']['ShippingMethod']['title']),
					'hint' => 'Estimated shipping cost: ' . $__templater->filter($__templater->method($__vars['shippingCombination']['ShippingMethod'], 'getEstimatedShippingCost', array($__vars['item'], )), array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '',
					'selected' => $__vars['shippingCombination']['shipping_method_id'] == $__vars['item']['shipping_method_id'],
					'_type' => 'option',
				);
			}
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">

		<hr class="block-separator" />

		<h3 class="block-header">' . 'Shipping options' . '</h3>
		<div class="block-body">
			' . $__templater->formRadioRow(array(
		'name' => 'shipping_method_id',
	), $__compilerTemp1, array(
		'label' => 'Available shipping methods',
		'explain' => 'The shipping cost may change based on how many other items you are purchasing and what shipping method you choose for each item.<br />
The costs displayed here are estimates. The final shipping cost will be displayed on the checkout page once you have chosen a shipping method for all your items.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>

', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/checkout/shipping-method', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);