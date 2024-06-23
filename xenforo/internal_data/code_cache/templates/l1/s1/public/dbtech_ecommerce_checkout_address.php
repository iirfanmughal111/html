<?php
// FROM HASH: 1c2123d1359efff0837392f59be2813f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Checkout'), $__templater->func('link', array('dbtech-ecommerce/checkout', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Your address');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['addresses'], 'empty', array())) {
		$__compilerTemp1 .= '
			<h2 class="block-header">' . 'Choose address' . '</h2>
			<div class="block-body">
				';
		$__compilerTemp2 = $__templater->mergeChoiceOptions(array(), $__vars['addresses']);
		$__compilerTemp2[] = array(
			'value' => '0',
			'label' => $__templater->filter('New address', array(array('parens', array()),), true),
			'_type' => 'option',
		);
		$__compilerTemp1 .= $__templater->formSelectRow(array(
			'name' => 'address_id',
			'value' => $__vars['order']['address_id'],
			'data-xf-init' => 'desc-loader',
			'data-desc-url' => $__templater->func('link', array('dbtech-ecommerce/account/get-address-description', ), false),
		), $__compilerTemp2, array(
			'label' => 'Billing address',
			'explain' => 'This address will be used for your invoice, which will be automatically sent to you.',
			'html' => '
						<div class="js-descTarget formRow-explain">
							' . $__templater->filter($__vars['order']['Address']['description'], array(array('raw', array()),), true) . '
						</div>
					',
		)) . '

				';
		if ($__templater->method($__vars['order'], 'hasPhysicalProduct', array())) {
			$__compilerTemp1 .= '
					';
			$__compilerTemp3 = array(array(
				'value' => '0',
				'label' => 'Same as billing address',
				'_type' => 'option',
			));
			$__compilerTemp3 = $__templater->mergeChoiceOptions($__compilerTemp3, $__vars['addresses']);
			$__compilerTemp1 .= $__templater->formSelectRow(array(
				'name' => 'shipping_address_id',
				'value' => $__vars['order']['shipping_address_id'],
				'data-xf-init' => 'desc-loader',
				'data-desc-url' => $__templater->func('link', array('dbtech-ecommerce/account/get-address-description', ), false),
			), $__compilerTemp3, array(
				'label' => 'Shipping address',
				'html' => '
							<div class="js-descTarget formRow-explain">
								' . $__templater->filter($__vars['order']['ShippingAddress']['description'], array(array('raw', array()),), true) . '
							</div>
						',
			)) . '
				';
		}
		$__compilerTemp1 .= '
			</div>
			' . $__templater->formSubmitRow(array(
			'icon' => 'save',
			'submit' => 'Continue',
		), array(
			'rowtype' => 'simple',
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		' . $__compilerTemp1 . '
		<h2 class="block-header">' . 'Add address' . '</h2>

		' . $__templater->callMacro(null, 'dbtech_ecommerce_address_edit_macros::form_contents', array(
		'required' => false,
	), $__vars) . '

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/checkout/address', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);