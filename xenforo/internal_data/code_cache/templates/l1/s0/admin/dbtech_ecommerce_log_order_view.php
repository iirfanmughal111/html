<?php
// FROM HASH: a01afe5e3682de9b6451649deb53d618
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Order #' . $__templater->escape($__vars['order']['order_id']) . '');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			';
	$__compilerTemp1 = '';
	if ((!$__vars['order']['user_id']) AND $__vars['order']['Address']) {
		$__compilerTemp1 .= '
					<div class="u-muted">' . ($__templater->escape($__vars['order']['Address']['email']) ?: 'Unknown email') . '</div>
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__templater->func('username_link', array($__vars['order']['User'], false, array(
		'defaultname' => 'Unknown user',
	))) . '
				' . $__compilerTemp1 . '
			', array(
		'label' => 'User',
	)) . '

			' . $__templater->formRow('
				<a href="' . $__templater->func('link_type', array('public', 'misc/ip-info', null, array('ip' => $__templater->filter($__vars['order']['ip_address'], array(array('ip', array()),), false), ), ), true) . '" target="_blank" class="u-ltr">' . $__templater->filter($__vars['order']['ip_address'], array(array('ip', array()),), true) . '</a>
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
			
			';
	$__compilerTemp2 = '';
	if ($__vars['order']['purchase_request_key']) {
		$__compilerTemp2 .= '
					<a href="' . $__templater->func('link', array('logs/payment-provider', null, array('purchase_request_key' => $__vars['order']['purchase_request_key'], ), ), true) . '">
						' . $__templater->escape($__vars['order']['purchase_request_key']) . '
					</a>
				';
	} else {
		$__compilerTemp2 .= '
					N/A
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp2 . '
			', array(
		'label' => 'Purchase request key',
	)) . '

			' . $__templater->formRow('
				' . $__templater->escape($__templater->method($__vars['order'], 'getOrderStateText', array())) . '
			', array(
		'label' => 'Order state',
	)) . '

			' . $__templater->formRow('
				' . $__templater->filter($__vars['order']['order_total'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
			', array(
		'label' => 'Order total',
	)) . '

			';
	$__compilerTemp3 = '';
	if ($__vars['order']['Address']) {
		$__compilerTemp3 .= '
					<ul class="listPlain">
						<li>' . $__templater->escape($__vars['order']['Address']['business_title']) . '</li>

						';
		if ($__vars['order']['Address']['business_co']) {
			$__compilerTemp3 .= '
							<li>' . $__templater->escape($__vars['order']['Address']['business_co']) . '</li>
						';
		}
		$__compilerTemp3 .= '

						';
		if ($__vars['order']['Address']['address1']) {
			$__compilerTemp3 .= '
							<li>' . $__templater->escape($__vars['order']['Address']['address1']) . '</li>
						';
		}
		$__compilerTemp3 .= '

						';
		if ($__vars['order']['Address']['address2']) {
			$__compilerTemp3 .= '
							<li>' . $__templater->escape($__vars['order']['Address']['address2']) . '</li>
						';
		}
		$__compilerTemp3 .= '

						';
		if ($__vars['order']['Address']['address3']) {
			$__compilerTemp3 .= '
							<li>' . $__templater->escape($__vars['order']['Address']['address3']) . '</li>
						';
		}
		$__compilerTemp3 .= '

						';
		if ($__vars['order']['Address']['address4']) {
			$__compilerTemp3 .= '
							<li>' . $__templater->escape($__vars['order']['Address']['address4']) . '</li>
						';
		}
		$__compilerTemp3 .= '

						<li>' . $__templater->escape($__vars['order']['Address']['Country']['name']) . '</li>
					</ul>
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

			';
	if ($__templater->method($__vars['order'], 'hasPhysicalProduct', array())) {
		$__finalCompiled .= '
				';
		$__vars['address'] = ($__vars['order']['shipping_address_id'] ? $__vars['order']['ShippingAddress'] : $__vars['order']['Address']);
		$__finalCompiled .= '

				';
		$__compilerTemp4 = '';
		if ($__vars['address']) {
			$__compilerTemp4 .= '
						<ul class="listPlain">
							<li>' . $__templater->escape($__vars['address']['business_title']) . '</li>

							';
			if ($__vars['address']['business_co']) {
				$__compilerTemp4 .= '
								<li>' . $__templater->escape($__vars['address']['business_co']) . '</li>
							';
			}
			$__compilerTemp4 .= '

							';
			if ($__vars['address']['address1']) {
				$__compilerTemp4 .= '
								<li>' . $__templater->escape($__vars['address']['address1']) . '</li>
							';
			}
			$__compilerTemp4 .= '

							';
			if ($__vars['address']['address2']) {
				$__compilerTemp4 .= '
								<li>' . $__templater->escape($__vars['address']['address2']) . '</li>
							';
			}
			$__compilerTemp4 .= '

							';
			if ($__vars['address']['address3']) {
				$__compilerTemp4 .= '
								<li>' . $__templater->escape($__vars['address']['address3']) . '</li>
							';
			}
			$__compilerTemp4 .= '

							';
			if ($__vars['address']['address4']) {
				$__compilerTemp4 .= '
								<li>' . $__templater->escape($__vars['address']['address4']) . '</li>
							';
			}
			$__compilerTemp4 .= '

							<li>' . $__templater->escape($__vars['address']['Country']['name']) . '</li>
						</ul>
					';
		} else {
			$__compilerTemp4 .= '
						' . 'Unknown address' . '
					';
		}
		$__finalCompiled .= $__templater->formRow('
					' . $__compilerTemp4 . '
				', array(
			'label' => 'Shipping address',
		)) . '
			';
	}
	$__finalCompiled .= '
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
			$__compilerTemp5 = '';
			if ($__vars['item']['Product']) {
				$__compilerTemp5 .= '
						' . $__templater->escape($__vars['item']['quantity']) . 'x ' . $__templater->escape($__vars['item']['Product']['full_title']) . '
						<div class="u-muted">
							';
				if ($__templater->method($__vars['item']['Product'], 'hasLicenseFunctionality', array())) {
					$__compilerTemp5 .= '
								';
					if ($__vars['item']['Cost']) {
						$__compilerTemp5 .= '
									' . $__templater->escape($__vars['item']['Cost']['length']) . '
								';
					} else {
						$__compilerTemp5 .= '
									' . 'Unknown length' . '
								';
					}
					$__compilerTemp5 .= '
							';
				} else {
					$__compilerTemp5 .= '
								';
					if ($__vars['item']['Cost']) {
						$__compilerTemp5 .= '
									' . $__templater->escape($__vars['item']['Cost']['title']) . '
								';
					} else {
						$__compilerTemp5 .= '
									' . 'Unknown variation' . '
								';
					}
					$__compilerTemp5 .= '

								';
					if ($__templater->method($__vars['item']['Product'], 'hasShippingFunctionality', array())) {
						$__compilerTemp5 .= '
									';
						if (!$__templater->test($__vars['item']['ShippingMethod'], 'empty', array())) {
							$__compilerTemp5 .= '
										- ' . 'Shipping method: ' . $__templater->escape($__vars['item']['ShippingMethod']['title']) . '' . '
									';
						} else {
							$__compilerTemp5 .= '
										- ' . 'Unknown shipping method' . '
									';
						}
						$__compilerTemp5 .= '
								';
					}
					$__compilerTemp5 .= '
							';
				}
				$__compilerTemp5 .= '	
							- ' . $__templater->escape($__templater->method($__vars['item'], 'getItemTypePhrase', array())) . '
							- ' . $__templater->filter($__vars['item']['price'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
						</div>
					';
			} else {
				$__compilerTemp5 .= '
						' . 'Unknown product' . '
					';
			}
			$__finalCompiled .= $__templater->formRow('
					' . $__compilerTemp5 . '
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
		$__compilerTemp6 = '';
		if (!$__templater->test($__vars['order']['Address'], 'empty', array()) AND $__vars['order']['Address']['sales_tax_id']) {
			$__compilerTemp6 .= '
						<div class="u-muted">' . $__templater->escape($__vars['order']['Address']['sales_tax_id']) . '</div>
					';
		}
		$__finalCompiled .= $__templater->formRow('
					+' . $__templater->filter($__vars['order']['sales_tax'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
					' . $__compilerTemp6 . '
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
				' . $__templater->filter($__vars['order']['order_total'], array(array('currency', array($__vars['order']['currency'], )),), true) . '
			', array(
		'label' => 'Order total',
	)) . '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);