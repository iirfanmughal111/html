<?php
// FROM HASH: 8b9455096be4e5ca2df7f94e77eeef34
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<!--suppress Annotator -->
';
	$__templater->breadcrumbs($__templater->method($__vars['product']['Category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['product']['title'])), $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->includeCss('dbtech_ecommerce.less');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Purchase product: ' . $__templater->escape($__vars['product']['full_title']) . '');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array()) AND !$__templater->test($__vars['license'], 'empty', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formRow('
					' . $__templater->escape($__vars['license']['title']) . '
				', array(
			'label' => 'License',
			'explain' => $__templater->escape($__vars['license']['license_key']),
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if (!$__templater->test($__vars['product']['Costs'], 'empty', array())) {
		$__compilerTemp2 .= '
				';
		if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
			$__compilerTemp2 .= '
					';
			$__compilerTemp3 = array();
			if ($__templater->isTraversable($__vars['product']['Costs'])) {
				foreach ($__vars['product']['Costs'] AS $__vars['cost']) {
					$__compilerTemp4 = '';
					if (!$__templater->test($__vars['cost']['description'], 'empty', array())) {
						$__compilerTemp4 .= '
											<i class="far fa-info-circle u-featuredText" title="' . $__templater->filter($__vars['cost']['description'], array(array('for_attr', array()),), true) . '" data-xf-init="tooltip"></i>
										';
					}
					$__compilerTemp5 = '';
					if ($__vars['cost']['highlighted']) {
						$__compilerTemp5 .= '
										<span class="label label--green">
											' . 'Most value!' . '
										</span>
									';
					}
					$__compilerTemp6 = '';
					if ($__templater->method($__vars['cost'], 'isLifetime', array())) {
						$__compilerTemp6 .= '
											' . 'Your license will receive updates forever.' . '
										';
					} else {
						$__compilerTemp6 .= '
											' . 'Your license will receive updates until <b>' . (('<b>' . $__templater->func('date_time', array($__templater->method($__vars['cost'], 'getNewExpiryDate', array($__vars['license'], )), ), true)) . '</b>') . '</b>' . '
										';
					}
					$__compilerTemp3[] = array(
						'value' => $__vars['cost']['product_cost_id'],
						'selected' => $__vars['cost']['product_cost_id'] == $__vars['selectedCost']['product_cost_id'],
						'data-xf-init' => 'disabler',
						'data-container' => '.js-licenseDescription' . $__vars['cost']['product_cost_id'],
						'data-hide' => 'true',
						'label' => '
									<span class="' . ($__vars['cost']['highlighted'] ? 'textHighlight' : '') . '">
										' . $__templater->escape($__vars['cost']['length']) . '

										' . $__compilerTemp4 . '
									</span>
									' . $__compilerTemp5 . '
								',
						'hint' => '
									<span class="' . ($__vars['cost']['highlighted'] ? 'textHighlight' : '') . '">
										' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array($__vars['license'], true, true, ))) . '
									</span>
								',
						'_dependent' => array('
									<div class="js-licenseDescription' . $__templater->escape($__vars['cost']['product_cost_id']) . '" ' . (($__vars['cost']['product_cost_id'] != $__vars['selectedCost']['product_cost_id']) ? 'style="display: none;"' : '') . '>
										' . $__compilerTemp6 . '
									</div>
								'),
						'_type' => 'option',
					);
				}
			}
			$__compilerTemp2 .= $__templater->formRadioRow(array(
				'name' => 'pricing_tier',
			), $__compilerTemp3, array(
				'label' => 'Receive updates for' . $__vars['xf']['language']['ellipsis'],
				'rowtype' => 'noColon',
			)) . '
				';
		} else {
			$__compilerTemp2 .= '
					';
			$__compilerTemp7 = array();
			if ($__templater->isTraversable($__vars['product']['Costs'])) {
				foreach ($__vars['product']['Costs'] AS $__vars['cost']) {
					$__compilerTemp8 = '';
					if (!$__templater->test($__vars['cost']['description'], 'empty', array())) {
						$__compilerTemp8 .= '
											<i class="far fa-info-circle u-featuredText" title="' . $__templater->filter($__vars['cost']['description'], array(array('for_attr', array()),), true) . '" data-xf-init="tooltip"></i>
										';
					}
					$__compilerTemp9 = '';
					if ($__vars['cost']['highlighted']) {
						$__compilerTemp9 .= '
										<span class="label label--green">
											' . 'Most value!' . '
										</span>
									';
					}
					$__compilerTemp10 = '';
					if ($__templater->method($__vars['product'], 'hasStockFunctionality', array())) {
						$__compilerTemp10 .= '
											<br />
											' . ($__vars['cost']['stock'] ? 'Available stock: ' . $__templater->filter($__vars['cost']['stock'], array(array('number', array()),), true) . '' : 'Out of stock!') . '
										';
					}
					$__compilerTemp11 = '';
					if ($__templater->method($__vars['product'], 'hasQuantityFunctionality', array())) {
						$__compilerTemp11 .= '
										<div class="inputGroup">
											<span class="inputGroup-text">' . 'Quantity' . $__vars['xf']['language']['label_separator'] . '</span>
											' . $__templater->formNumberBox(array(
							'name' => 'quantity',
							'value' => '1',
							'min' => '0',
							'max' => $__vars['cost']['stock'],
							'step' => '1',
						)) . '
										</div>
									';
					}
					$__compilerTemp7[] = array(
						'value' => $__vars['cost']['product_cost_id'],
						'selected' => $__vars['cost']['product_cost_id'] == $__vars['selectedCost']['product_cost_id'],
						'disabled' => (($__templater->method($__vars['product'], 'hasStockFunctionality', array()) AND ($__vars['cost']['stock'] <= 0)) ? true : false),
						'data-hide' => 'true',
						'label' => '
									<span class="' . ($__vars['cost']['highlighted'] ? 'textHighlight' : '') . '">
										' . $__templater->escape($__vars['cost']['title']) . '

										' . $__compilerTemp8 . '
									</span>
									' . $__compilerTemp9 . '
								',
						'hint' => '
									<span class="' . ($__vars['cost']['highlighted'] ? 'textHighlight' : '') . '">
										' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '
										' . $__compilerTemp10 . '
									</span>
								',
						'_dependent' => array('
									' . $__compilerTemp11 . '
								'),
						'_type' => 'option',
					);
				}
			}
			$__compilerTemp2 .= $__templater->formRadioRow(array(
				'name' => 'pricing_tier',
			), $__compilerTemp7, array(
				'label' => 'Available variations',
			)) . '

					';
			if ($__templater->method($__vars['product'], 'hasShippingFunctionality', array())) {
				$__compilerTemp2 .= '
						' . $__templater->formRow('
							' . $__templater->escape($__templater->method($__vars['product'], 'getShippingZoneList', array())) . '
						', array(
					'label' => 'Ships to',
				)) . '
					';
			}
			$__compilerTemp2 .= '
				';
		}
		$__compilerTemp2 .= '
			';
	}
	$__compilerTemp12 = '';
	if (!$__templater->test($__vars['product']['Children'], 'empty', array())) {
		$__compilerTemp12 .= '
			<h3 class="block-formSectionHeader">' . 'Add-on products' . '</h3>
			';
		$__compilerTemp13 = array();
		if ($__templater->isTraversable($__vars['product']['Children'])) {
			foreach ($__vars['product']['Children'] AS $__vars['childProduct']) {
				if ($__templater->method($__vars['childProduct'], 'canPurchase', array())) {
					$__compilerTemp14 = '';
					if (!$__vars['purchasedAddOns'][$__vars['childProduct']['product_id']]) {
						$__compilerTemp14 .= '
								';
						if ($__vars['childProduct']['Costs']) {
							$__compilerTemp14 .= '
									';
							$__compilerTemp15 = array();
							if ($__templater->isTraversable($__vars['childProduct']['Costs'])) {
								foreach ($__vars['childProduct']['Costs'] AS $__vars['cost']) {
									$__compilerTemp16 = '';
									if ($__templater->method($__vars['cost'], 'isLifetime', array())) {
										$__compilerTemp16 .= '
														' . 'Your license will receive updates forever.' . '
														';
									} else {
										$__compilerTemp16 .= '
														' . 'Your license will receive updates until <b>' . (('<b>' . $__templater->func('date_time', array($__templater->method($__vars['cost'], 'getNewExpiryDate', array($__vars['license'], )), ), true)) . '</b>') . '</b>' . '
													';
									}
									$__compilerTemp15[] = array(
										'value' => $__vars['cost']['product_cost_id'],
										'label' => $__templater->escape($__vars['cost']['length']),
										'hint' => $__templater->filter($__vars['cost']['price'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true),
										'selected' => $__vars['cost']['product_cost_id'] == $__templater->arrayKey($__templater->method($__vars['childProduct']['Costs'], 'first', array()), 'product_cost_id'),
										'data-xf-init' => 'disabler',
										'data-container' => '.js-licenseDescription' . $__vars['cost']['product_cost_id'],
										'data-hide' => 'true',
										'_dependent' => array('
													<div class="js-licenseDescription' . $__templater->escape($__vars['cost']['product_cost_id']) . '" ' . (($__vars['cost']['product_cost_id'] != $__templater->arrayKey($__templater->method($__vars['childProduct']['Costs'], 'first', array()), 'product_cost_id')) ? 'style="display: none;"' : '') . '>
													' . $__compilerTemp16 . '
													</div>
												'),
										'_type' => 'option',
									);
								}
							}
							$__compilerTemp14 .= $__templater->formRadio(array(
								'name' => 'addon_pricing_tier[' . $__vars['childProduct']['product_id'] . ']',
							), $__compilerTemp15) . '
								';
						} else {
							$__compilerTemp14 .= '
									<div class="formRow-hint">' . $__templater->filter($__vars['product']['starting_price'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</div>
								';
						}
						$__compilerTemp14 .= '
							';
					} else {
						$__compilerTemp14 .= '
								<div class="formRow-hint">' . 'Already owned' . '</div>
							';
					}
					$__compilerTemp13[] = array(
						'label' => $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['childProduct'], ), true) . $__templater->escape($__vars['childProduct']['title']),
						'selected' => $__vars['purchasedAddOns'][$__vars['childProduct']['product_id']],
						'disabled' => $__vars['purchasedAddOns'][$__vars['childProduct']['product_id']],
						'_dependent' => array('
							' . $__compilerTemp14 . '
						'),
						'_type' => 'option',
					);
				}
			}
		}
		$__compilerTemp12 .= $__templater->formCheckBoxRow(array(
		), $__compilerTemp13, array(
			'label' => 'Optional extras',
			'explain' => 'The update duration may not be relevant to add-on products; the duration is usually tied to the parent product.',
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h3 class="block-formSectionHeader">' . 'Purchase options' . '</h3>
		<div class="block-body">
			' . $__compilerTemp1 . '
			' . $__compilerTemp2 . '
		</div>

		' . $__compilerTemp12 . '

		' . $__templater->formSubmitRow(array(
		'submit' => 'Add to cart',
		'sticky' => 'true',
		'icon' => 'purchase',
	), array(
	)) . '

	</div>

	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/purchase', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);