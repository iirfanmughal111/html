<?php
// FROM HASH: 0550c133d68f57757d7b3f4382bd3b27
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Shipping methods');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add shipping method', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/shipping-methods/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['shippingMethods'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['shippingMethods'])) {
			foreach ($__vars['shippingMethods'] AS $__vars['shippingMethod']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
					'label' => $__templater->escape($__vars['shippingMethod']['title']),
					'href' => $__templater->func('link', array('dbtech-ecommerce/shipping-methods/edit', $__vars['shippingMethod'], ), false),
					'explain' => $__templater->escape($__vars['shippingMethod']['cost_formula']),
					'delete' => $__templater->func('link', array('dbtech-ecommerce/shipping-methods/delete', $__vars['shippingMethod'], ), false),
				), array(array(
					'class' => 'dataList-cell--min dataList-cell--hint',
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['shippingMethod']['display_order']),
				),
				array(
					'name' => 'active[' . $__vars['shippingMethod']['shipping_method_id'] . ']',
					'selected' => $__vars['shippingMethod']['active'],
					'class' => 'dataList-cell--separated',
					'submit' => 'true',
					'tooltip' => 'Enable / disable \'' . $__vars['shippingMethod']['title'] . '\'',
					'_type' => 'toggle',
					'html' => '',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'shipping-methods',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				' . $__templater->dataList('
					' . $__compilerTemp1 . '
				', array(
		)) . '
				<div class="block-footer">
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['totalShippingMethods'], ), true) . '</span>
				</div>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('dbtech-ecommerce/shipping-methods/toggle', ), false),
			'class' => 'block',
			'ajax' => 'true',
		)) . '
	';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	return $__finalCompiled;
}
);