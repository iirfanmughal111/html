<?php
// FROM HASH: d8b9c87c35889a210add318191167731
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<!--suppress Annotator -->
';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '
';
	if ($__vars['license']) {
		$__finalCompiled .= '
	';
		$__templater->breadcrumb($__templater->preEscaped('Licenses owned by ' . $__templater->escape($__vars['license']['User']['username']) . ''), $__templater->func('link', array('dbtech-ecommerce/licenses', $__vars['license']['User'], ), false), array(
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeCss('dbtech_ecommerce.less');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Purchase add-ons: ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title'])) . '');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped('Purchase add-ons: ' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title'])) . '');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['product']['Children'])) {
		foreach ($__vars['product']['Children'] AS $__vars['childProduct']) {
			if ($__templater->method($__vars['childProduct'], 'canPurchase', array())) {
				$__compilerTemp2 = '';
				if (!$__vars['purchasedAddOns'][$__vars['childProduct']['product_id']]) {
					$__compilerTemp2 .= '
							';
					if ($__vars['childProduct']['Costs']) {
						$__compilerTemp2 .= '
								';
						$__compilerTemp3 = array();
						if ($__templater->isTraversable($__vars['childProduct']['Costs'])) {
							foreach ($__vars['childProduct']['Costs'] AS $__vars['cost']) {
								$__compilerTemp4 = '';
								if ($__templater->method($__vars['cost'], 'isLifetime', array())) {
									$__compilerTemp4 .= '
													' . 'Your license will receive updates forever.' . '
													';
								} else {
									$__compilerTemp4 .= '
													' . 'Your license will receive updates until <b>' . (('<b>' . $__templater->func('date_time', array($__templater->method($__vars['cost'], 'getNewExpiryDate', array($__vars['license'], )), ), true)) . '</b>') . '</b>' . '
												';
								}
								$__compilerTemp3[] = array(
									'value' => $__vars['cost']['product_cost_id'],
									'label' => $__templater->escape($__vars['cost']['length']),
									'hint' => $__templater->filter($__vars['cost']['price'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true),
									'selected' => $__vars['cost']['product_cost_id'] == $__templater->arrayKey($__templater->method($__vars['childProduct']['Costs'], 'first', array()), 'product_cost_id'),
									'data-xf-init' => 'disabler',
									'data-container' => '.js-licenseDescription' . $__vars['cost']['product_cost_id'],
									'data-hide' => 'true',
									'_dependent' => array('
												<div class="js-licenseDescription' . $__templater->escape($__vars['cost']['product_cost_id']) . '" ' . (($__vars['cost']['product_cost_id'] != $__templater->arrayKey($__templater->method($__vars['childProduct']['Costs'], 'first', array()), 'product_cost_id')) ? 'style="display: none;"' : '') . '>
												' . $__compilerTemp4 . '
												</div>
											'),
									'_type' => 'option',
								);
							}
						}
						$__compilerTemp2 .= $__templater->formRadio(array(
							'name' => 'addon_pricing_tier[' . $__vars['childProduct']['product_id'] . ']',
						), $__compilerTemp3) . '
							';
					} else {
						$__compilerTemp2 .= '
								<div class="formRow-hint">' . $__templater->filter($__vars['product']['starting_price'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</div>
							';
					}
					$__compilerTemp2 .= '
						';
				} else {
					$__compilerTemp2 .= '
							<div class="formRow-hint">' . 'Already owned' . '</div>
						';
				}
				$__compilerTemp1[] = array(
					'label' => ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['childProduct'], ), true) . $__templater->escape($__vars['childProduct']['title'])),
					'selected' => $__vars['purchasedAddOns'][$__vars['childProduct']['product_id']],
					'disabled' => $__vars['purchasedAddOns'][$__vars['childProduct']['product_id']],
					'_dependent' => array('
						' . $__compilerTemp2 . '
					'),
					'_type' => 'option',
				);
			}
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">

		<h3 class="block-formSectionHeader">' . 'Add-on products' . '</h3>
		' . $__templater->formCheckBoxRow(array(
	), $__compilerTemp1, array(
		'label' => 'Optional extras',
		'explain' => 'The update duration may not be relevant to add-on products; the duration is usually tied to the parent product.',
	)) . '

		' . $__templater->formSubmitRow(array(
		'submit' => 'Add to cart',
		'sticky' => 'true',
		'icon' => 'purchase',
	), array(
	)) . '

	</div>

	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/purchase/add-ons', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);