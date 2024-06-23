<?php
// FROM HASH: 50fd01e56ccaad5a8bf23d9ebcffe213
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Coupon log entry');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('users/edit', $__vars['entry']['User'], ), true) . '">' . $__templater->escape($__vars['entry']['User']['username']) . '</a>
			', array(
		'label' => 'User',
	)) . '
			';
	if ($__vars['entry']['Ip']) {
		$__finalCompiled .= '
				' . $__templater->formRow('
					<a href="' . $__templater->func('link_type', array('public', 'misc/ip-info', null, array('ip' => $__templater->filter($__vars['entry']['Ip']['ip'], array(array('ip', array()),), false), ), ), true) . '" target="_blank" class="u-ltr">' . $__templater->filter($__vars['entry']['Ip']['ip'], array(array('ip', array()),), true) . '</a>
				', array(
			'label' => 'IP address',
		)) . '
			';
	}
	$__finalCompiled .= '
			' . $__templater->formRow('
				' . $__templater->func('date_dynamic', array($__vars['entry']['log_date'], array(
		'data-full-date' => 'true',
	))) . '
			', array(
		'label' => 'Date',
	)) . '

			<hr class="formRowSep" />

			';
	$__compilerTemp1 = '';
	if ($__vars['entry']['Coupon']) {
		$__compilerTemp1 .= '
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/coupons/edit', $__vars['entry']['Coupon'], ), true) . '">' . $__templater->escape($__vars['entry']['Coupon']['title']) . '</a>
					';
	} else {
		$__compilerTemp1 .= '
					' . 'Unknown coupon' . '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp1 . '
			', array(
		'label' => 'Coupon',
	)) . '
			' . $__templater->formRow('
				' . $__templater->filter($__vars['entry']['coupon_discounts'], array(array('currency', array($__vars['entry']['currency'], )),), true) . '
			', array(
		'label' => 'Discount',
	)) . '

			';
	$__compilerTemp2 = '';
	if ($__vars['entry']['Product']) {
		$__compilerTemp2 .= '
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/edit', $__vars['entry']['Product'], ), true) . '">' . $__templater->escape($__vars['entry']['Product']['title']) . '</a>
				';
	} else {
		$__compilerTemp2 .= '
					' . 'Unknown product' . '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp2 . '
			', array(
		'label' => 'Product',
	)) . '

			';
	$__compilerTemp3 = '';
	if ($__vars['entry']['Order']) {
		$__compilerTemp3 .= '
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/orders/', $__vars['entry']['Order'], ), true) . '" data-xf-click="overlay">' . 'Order #' . $__templater->escape($__vars['entry']['order_id']) . '' . '</a>
					<div class="u-muted">' . ($__vars['entry']['order_item_id'] ? 'Order item #' . $__templater->escape($__vars['entry']['order_item_id']) . '' : 'Unknown order item') . '</div>
				';
	} else {
		$__compilerTemp3 .= '
					' . 'Unknown order' . '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp3 . '
			', array(
		'label' => 'Order',
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);