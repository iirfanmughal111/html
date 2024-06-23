<?php
// FROM HASH: 75c0d0f06277a063c3c289126e395020
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Shipping zones');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add shipping zone', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/shipping-zones/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['shippingZones'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__templater->isTraversable($__vars['shippingZones'])) {
			foreach ($__vars['shippingZones'] AS $__vars['shippingZone']) {
				$__compilerTemp1 .= '
						' . $__templater->dataRow(array(
					'label' => $__templater->escape($__vars['shippingZone']['title']),
					'href' => $__templater->func('link', array('dbtech-ecommerce/shipping-zones/edit', $__vars['shippingZone'], ), false),
					'delete' => $__templater->func('link', array('dbtech-ecommerce/shipping-zones/delete', $__vars['shippingZone'], ), false),
				), array(array(
					'class' => 'dataList-cell--min dataList-cell--hint',
					'_type' => 'cell',
					'html' => $__templater->escape($__vars['shippingZone']['display_order']),
				),
				array(
					'name' => 'active[' . $__vars['shippingZone']['shipping_zone_id'] . ']',
					'selected' => $__vars['shippingZone']['active'],
					'class' => 'dataList-cell--separated',
					'submit' => 'true',
					'tooltip' => 'Enable / disable \'' . $__vars['shippingZone']['title'] . '\'',
					'_type' => 'toggle',
					'html' => '',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-outer">
			' . $__templater->callMacro('filter_macros', 'quick_filter', array(
			'key' => 'shipping-zones',
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
					<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['totalShippingzones'], ), true) . '</span>
				</div>
			</div>
		</div>
	', array(
			'action' => $__templater->func('link', array('dbtech-ecommerce/shipping-zones/toggle', ), false),
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