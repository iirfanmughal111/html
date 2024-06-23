<?php
// FROM HASH: 9f99fc715647f470ebd7364972a6d6bb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Order #' . $__templater->escape($__vars['order']['order_id']) . '');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			';
	$__compilerTemp1 = '';
	if ($__vars['order']['user_id']) {
		$__compilerTemp1 .= '
					' . ($__vars['order']['User'] ? $__templater->escape($__vars['order']['User']['username']) : 'Unknown user') . '
				';
	} else {
		$__compilerTemp1 .= '
					' . 'Guest' . '
					';
		if ($__vars['order']['Address']) {
			$__compilerTemp1 .= '
						<div class="u-muted">' . ($__templater->escape($__vars['order']['Address']['email']) ?: 'Unknown email') . '</div>
					';
		}
		$__compilerTemp1 .= '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp1 . '
			', array(
		'label' => 'User',
	)) . '

			';
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array())) {
		$__compilerTemp2 .= '
					<a href="' . $__templater->func('link_type', array('public', 'misc/ip-info', null, array('ip' => $__templater->filter($__vars['order']['ip_address'], array(array('ip', array()),), false), ), ), true) . '" target="_blank" class="u-ltr">' . $__templater->filter($__vars['order']['ip_address'], array(array('ip', array()),), true) . '</a>
				';
	} else {
		$__compilerTemp2 .= '
					' . $__templater->filter($__vars['order']['ip_address'], array(array('ip', array()),), true) . '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp2 . '
			', array(
		'label' => 'IP address',
	)) . '

			' . $__templater->formRow('
				' . $__templater->func('date_dynamic', array($__vars['order']['order_date'], array(
		'data-full-date' => 'true',
	))) . '
			', array(
		'label' => 'Date',
	)) . '

			' . $__templater->formRow('
				' . $__templater->escape($__templater->method($__vars['order'], 'getOrderStateText', array())) . '
			', array(
		'label' => 'Order state',
	)) . '

			' . $__templater->formRow('
				' . $__templater->filter($__vars['order']['cost_amount'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
			', array(
		'label' => 'Order total',
	)) . '

			';
	$__compilerTemp3 = '';
	if ($__vars['order']['Address']) {
		$__compilerTemp3 .= '
					' . $__templater->escape($__vars['order']['Address']['business_title']) . '<br />
					' . $__templater->escape($__vars['order']['Address']['business_co']) . '<br />
					' . $__templater->escape($__vars['order']['Address']['address1']) . '<br />
					' . $__templater->escape($__vars['order']['Address']['address2']) . '<br />
					' . $__templater->escape($__vars['order']['Address']['address3']) . '<br />
					' . $__templater->escape($__vars['order']['Address']['address4']) . '
				';
	} else {
		$__compilerTemp3 .= '
					' . 'Unknown address' . '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp3 . '
			', array(
		'label' => 'Address',
	)) . '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="block-formSectionHeader-aligner">' . 'Items' . '</span>
		</h3>
		<div class="block-body">
			';
	if ($__templater->isTraversable($__vars['order']['Items'])) {
		foreach ($__vars['order']['Items'] AS $__vars['item']) {
			$__finalCompiled .= '
				';
			$__compilerTemp4 = '';
			if ($__vars['item']['Product']) {
				$__compilerTemp4 .= '
						' . $__templater->escape($__vars['item']['quantity']) . 'x ' . $__templater->escape($__vars['item']['Product']['title']) . '
						<div class="u-muted">
							';
				if ($__templater->method($__vars['item']['Product'], 'hasLicenseFunctionality', array())) {
					$__compilerTemp4 .= '
								';
					if ($__vars['item']['Cost']) {
						$__compilerTemp4 .= '
									' . $__templater->escape($__vars['item']['Cost']['length']) . '
								';
					} else {
						$__compilerTemp4 .= '
									' . 'Unknown length' . '
								';
					}
					$__compilerTemp4 .= '
							';
				} else {
					$__compilerTemp4 .= '
								';
					if ($__vars['item']['Cost']) {
						$__compilerTemp4 .= '
									' . $__templater->escape($__vars['item']['Cost']['title']) . '
								';
					} else {
						$__compilerTemp4 .= '
									' . 'Unknown variation' . '
								';
					}
					$__compilerTemp4 .= '
							';
				}
				$__compilerTemp4 .= '
							- ' . $__templater->escape($__templater->method($__vars['item'], 'getItemTypePhrase', array())) . '
							- ' . $__templater->filter($__vars['item']['price'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
						</div>
					';
			} else {
				$__compilerTemp4 .= '
						' . 'Unknown product' . '
					';
			}
			$__finalCompiled .= $__templater->formRow('
					' . $__compilerTemp4 . '
				', array(
			)) . '
			';
		}
	}
	$__finalCompiled .= '
		</div>

		<h3 class="block-formSectionHeader">
			<span class="block-formSectionHeader-aligner">' . 'Payment info' . '</span>
		</h3>
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->filter($__vars['order']['sub_total'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
			', array(
		'label' => 'Sub-total',
	)) . '

			<hr class="formRowSep" />

			';
	if ($__vars['order']['coupon_id']) {
		$__finalCompiled .= '
				' . $__templater->formRow('
					-' . $__templater->filter($__vars['order']['coupon_discounts'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
					<div class="u-muted">' . ($__templater->escape($__vars['order']['Coupon']['title']) ?: 'Unknown coupon') . '</div>
				', array(
			'label' => 'Coupon',
		)) . '
			';
	}
	$__finalCompiled .= '

			' . $__templater->formRow('
				-' . $__templater->filter($__vars['order']['sale_discounts'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
			', array(
		'label' => 'Sale discounts',
	)) . '

			';
	if ($__templater->method($__vars['order'], 'hasPhysicalProduct', array())) {
		$__finalCompiled .= '
				' . $__templater->formRow('
					+' . $__templater->filter($__vars['order']['shipping_cost'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
				', array(
			'label' => 'Shipping cost',
		)) . '
			';
	}
	$__finalCompiled .= '

			';
	if ((!$__templater->test($__vars['order']['Address'], 'empty', array()) AND $__vars['order']['Address']['sales_tax_id']) OR $__vars['order']['sales_tax']) {
		$__finalCompiled .= '
				';
		$__compilerTemp5 = '';
		if (!$__templater->test($__vars['order']['Address'], 'empty', array()) AND $__vars['order']['Address']['sales_tax_id']) {
			$__compilerTemp5 .= '
						<div class="u-muted">' . $__templater->escape($__vars['order']['Address']['sales_tax_id']) . '</div>
					';
		}
		$__finalCompiled .= $__templater->formRow('
					+' . $__templater->filter($__vars['order']['sales_tax'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
					' . $__compilerTemp5 . '
				', array(
			'label' => 'Sales tax',
		)) . '
			';
	}
	$__finalCompiled .= '

			' . $__templater->formRow('
				-' . $__templater->filter($__vars['order']['automatic_discounts'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
			', array(
		'label' => 'Automatic discounts',
	)) . '

			' . $__templater->formRow('
				-' . $__templater->filter($__vars['order']['store_credit_amount'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
			', array(
		'label' => 'Store credit used',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				' . $__templater->filter($__vars['order']['cost_amount'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
			', array(
		'label' => 'Order total',
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);