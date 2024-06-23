<?php
// FROM HASH: 33463a3b1ad15f10790a19794537aa37
return array(
'macros' => array('order' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'order' => '!',
		'context' => 'public',
		'linkPrefix' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if ($__vars['context'] == 'admin') {
		$__compilerTemp1 .= '
						<li>
							';
		if ($__vars['order']['user_id']) {
			$__compilerTemp1 .= '
								' . ($__vars['order']['User'] ? $__templater->escape($__vars['order']['User']['username']) : 'Unknown user') . '
							';
		} else {
			$__compilerTemp1 .= '
								' . 'Guest' . '
							';
		}
		$__compilerTemp1 .= '
						</li>
					';
	}
	$__compilerTemp2 = '';
	if ($__vars['context'] == 'public') {
		$__compilerTemp2 .= '
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/account/order/edit', $__vars['order'], ), true) . '" class="menu-linkRow">' . 'Edit address' . '</a>
						';
		if ($__templater->method($__vars['order'], 'canDownloadInvoice', array())) {
			$__compilerTemp2 .= '
							<a href="' . $__templater->func('link', array('dbtech-ecommerce/account/order/invoice', $__vars['order'], ), true) . '" class="menu-linkRow">' . 'Download invoice' . '</a>
						';
		}
		$__compilerTemp2 .= '
						';
		if ($__templater->method($__vars['order'], 'canPurchase', array())) {
			$__compilerTemp2 .= '
							<a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/retry', $__vars['order'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Retry payment' . '</a>
							<a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/cancel', $__vars['order'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Cancel order' . '</a>
						';
		}
		$__compilerTemp2 .= '
					';
	} else {
		$__compilerTemp2 .= '
						<a href="' . $__templater->func('link_type', array($__vars['context'], 'dbtech-ecommerce/logs/orders', $__vars['order'], ), true) . '" class="menu-linkRow">' . 'View' . '</a>
						';
		if ($__templater->method($__vars['order'], 'isCompleted', array())) {
			$__compilerTemp2 .= '
							<a href="' . $__templater->func('link_type', array($__vars['context'], 'dbtech-ecommerce/orders/cancel', $__vars['order'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Cancel order' . '</a>
							';
			if (($__vars['order']['order_state'] != 'shipped') AND $__templater->method($__vars['order'], 'hasPhysicalProduct', array())) {
				$__compilerTemp2 .= '
								<a href="' . $__templater->func('link_type', array($__vars['context'], 'dbtech-ecommerce/orders/ship', $__vars['order'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Mark as shipped' . '</a>
							';
			}
			$__compilerTemp2 .= '
						';
		} else {
			$__compilerTemp2 .= '
							<a href="' . $__templater->func('link_type', array($__vars['context'], 'dbtech-ecommerce/orders/complete', $__vars['order'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . (($__vars['order']['order_state'] == 'reversed') ? 'Restore order' : 'Complete order') . '</a>
							';
			if (($__vars['order']['order_state'] == 'pending') OR ($__vars['order']['order_state'] == 'awaiting_payment')) {
				$__compilerTemp2 .= '
								<a href="' . $__templater->func('link_type', array($__vars['context'], 'dbtech-ecommerce/orders/delete', $__vars['order'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Delete order' . '</a>
							';
			}
			$__compilerTemp2 .= '
						';
		}
		$__compilerTemp2 .= '
						';
		if (($__vars['order']['order_state'] == 'pending') AND (!$__vars['order']['coupon_id'])) {
			$__compilerTemp2 .= '
							<a href="' . $__templater->func('link_type', array($__vars['context'], 'dbtech-ecommerce/orders/apply-coupon', $__vars['order'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Apply coupon' . '</a>
						';
		}
		$__compilerTemp2 .= '
						';
		if ($__templater->method($__vars['order'], 'isCompleted', array())) {
			$__compilerTemp2 .= '
							<a href="' . $__templater->func('link_type', array($__vars['context'], 'dbtech-ecommerce/orders/invoice', $__vars['order'], ), true) . '" class="menu-linkRow">' . 'Download invoice' . '</a>
							';
			if ($__templater->method($__vars['order'], 'hasPhysicalProduct', array())) {
				$__compilerTemp2 .= '
								<a href="' . $__templater->func('link_type', array($__vars['context'], 'dbtech-ecommerce/orders/shipping-label', $__vars['order'], ), true) . '" class="menu-linkRow">' . 'Download shipping label' . '</a>
							';
			}
			$__compilerTemp2 .= '
						';
		}
		$__compilerTemp2 .= '
					';
	}
	$__finalCompiled .= $__templater->dataRow(array(
	), array(array(
		'href' => $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'], $__vars['order'], ), false),
		'overlay' => 'true',
		'label' => '
				' . 'Order #' . $__templater->escape($__vars['order']['order_id']) . ' (' . $__templater->filter($__vars['order']['order_total'], array(array('currency', array($__vars['order']['currency'], )),), true) . ')' . '
			',
		'hint' => '
				' . $__templater->escape($__templater->method($__vars['order'], 'getOrderStateText', array())) . '
			',
		'explain' => '
				<ul class="listInline listInline--bullet">
					<li>' . $__templater->func('date_dynamic', array($__vars['order']['order_date'], array(
		'data-full-date' => 'true',
	))) . '</li>
					' . $__compilerTemp1 . '
					<li>' . ($__vars['order']['ip_address'] ? $__templater->filter($__vars['order']['ip_address'], array(array('ip', array()),), true) : 'Unknown IP address') . '</li>
				</ul>
			',
		'_type' => 'main',
		'html' => '',
	),
	array(
		'class' => 'dataList-cell--action',
		'label' => 'Manage' . $__vars['xf']['language']['ellipsis'],
		'_type' => 'popup',
		'html' => '

			<div class="menu" data-menu="menu" aria-hidden="true">
				<div class="menu-content">
					<h3 class="menu-header">' . 'Manage' . $__vars['xf']['language']['ellipsis'] . '</h3>
					' . $__compilerTemp2 . '
				</div>
			</div>
		',
	))) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);