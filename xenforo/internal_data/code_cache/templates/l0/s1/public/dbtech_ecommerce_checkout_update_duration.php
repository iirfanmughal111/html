<?php
// FROM HASH: 37dc5a88b6c21b6c614343797de0a605
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Choose update duration');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['product']['Costs'])) {
		foreach ($__vars['product']['Costs'] AS $__vars['cost']) {
			$__compilerTemp2 = '';
			if ($__templater->method($__vars['cost'], 'isLifetime', array())) {
				$__compilerTemp2 .= '
									' . 'Your license will receive updates forever.' . '
								';
			} else {
				$__compilerTemp2 .= '
									' . 'Your license will receive updates until <b>' . (('<b>' . $__templater->func('date_time', array($__templater->method($__vars['cost'], 'getNewExpiryDate', array($__vars['license'], )), ), true)) . '</b>') . '</b>' . '
								';
			}
			$__compilerTemp1[] = array(
				'value' => $__vars['cost']['product_cost_id'],
				'label' => $__templater->escape($__vars['cost']['length']),
				'hint' => $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array($__vars['license'], true, true, ))),
				'selected' => $__vars['cost']['product_cost_id'] == $__templater->arrayKey($__templater->method($__vars['product']['Costs'], 'first', array()), 'product_cost_id'),
				'data-xf-init' => 'disabler',
				'data-container' => '.js-licenseDescription' . $__vars['cost']['product_cost_id'],
				'data-hide' => 'true',
				'_dependent' => array('
							<div class="js-licenseDescription' . $__templater->escape($__vars['cost']['product_cost_id']) . '" ' . (($__vars['cost']['product_cost_id'] != $__templater->arrayKey($__templater->method($__vars['product']['Costs'], 'first', array()), 'product_cost_id')) ? 'style="display: none;"' : '') . '>
								' . $__compilerTemp2 . '
							</div>
						'),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">

		<hr class="block-separator" />

		<div class="block-body">
			' . $__templater->formRadioRow(array(
		'name' => 'pricing_tier',
	), $__compilerTemp1, array(
		'label' => 'Receive updates for' . $__vars['xf']['language']['ellipsis'],
		'rowtype' => 'noColon',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>

', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/checkout/update-duration', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);