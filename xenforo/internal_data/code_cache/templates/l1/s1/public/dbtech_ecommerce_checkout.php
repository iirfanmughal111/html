<?php
// FROM HASH: 24925335976621d95fa966d77afa256a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('dbtech_ecommerce.less');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'xf/payment.js',
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Checkout');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			';
	if ($__vars['order']['Address'] AND ($__vars['order']['Address']['address_state'] == 'moderated')) {
		$__compilerTemp1 .= '
				<dd class="blockStatus-message blockStatus-message--moderated">
					' . 'Your VAT ID is awaiting approval before the tax status can be applied.' . '
				</dd>
			';
	}
	$__compilerTemp1 .= '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	<dl class="blockStatus blockStatus--standalone">
		<dt>' . 'Status' . '</dt>
		' . $__compilerTemp1 . '
	</dl>
';
	}
	$__finalCompiled .= '

';
	$__vars['showUpdate'] = false;
	$__finalCompiled .= '
';
	if ($__templater->isTraversable($__vars['order']['Items'])) {
		foreach ($__vars['order']['Items'] AS $__vars['item']) {
			if ($__templater->method($__vars['item']['Product'], 'hasQuantityFunctionality', array())) {
				$__finalCompiled .= '
	';
				$__vars['showUpdate'] = true;
				$__finalCompiled .= '
';
			}
		}
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['order']['Items'])) {
		foreach ($__vars['order']['Items'] AS $__vars['item']) {
			$__compilerTemp2 .= '
					';
			$__compilerTemp3 = '';
			if ($__templater->method($__vars['item']['Product'], 'hasLicenseFunctionality', array()) AND $__vars['item']['license_id']) {
				$__compilerTemp3 .= '
									- <a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['item']['License'], ), true) . '" class="u-concealed" target="_blank">' . 'Renewal' . '</a>
								';
			}
			$__compilerTemp4 = '';
			if ($__templater->method($__vars['item']['Product'], 'hasLicenseFunctionality', array())) {
				$__compilerTemp4 .= '
									';
				if ($__templater->method($__vars['item']['Cost'], 'isLifetime', array())) {
					$__compilerTemp4 .= '
										' . 'Will never expire.' . '
									';
				} else {
					$__compilerTemp4 .= '
										' . 'Receives updates until ' . $__templater->func('date_time', array($__templater->method($__vars['item']['Cost'], 'getNewExpiryDate', array($__vars['item']['License'], )), ), true) . '' . '
									';
				}
				$__compilerTemp4 .= '
									<div><a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/update-duration', $__vars['item'], ), true) . '" data-xf-click="overlay" class="u-concealed">' . 'Change update duration' . '</a></div>
								';
			} else if ($__templater->method($__vars['item']['Product'], 'hasShippingFunctionality', array())) {
				$__compilerTemp4 .= '
									';
				if (!$__templater->test($__vars['item']['ShippingMethod'], 'empty', array()) AND $__templater->method($__vars['item']['ShippingMethod'], 'isApplicable', array($__vars['order']['ShippingAddress'], ))) {
					$__compilerTemp4 .= '
										<div>' . 'Shipping method: ' . $__templater->escape($__vars['item']['ShippingMethod']['title']) . '' . '</div>
										<div><a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/shipping-method', $__vars['item'], ), true) . '" data-xf-click="overlay" class="u-concealed">' . 'Change shipping method' . '</a></div>
									';
				} else {
					$__compilerTemp4 .= '
										<a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/shipping-method', $__vars['item'], ), true) . '" data-xf-click="overlay" class="u-concealed">' . 'Choose shipping method' . '</a>
									';
				}
				$__compilerTemp4 .= '
								';
			}
			$__compilerTemp5 = array(array(
				'name' => 'order_item_ids[]',
				'value' => $__vars['item']['order_item_id'],
				'_type' => 'toggle',
				'html' => '',
			)
,array(
				'class' => 'dataList-cell--flexHeight',
				'label' => '<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['item']['Product'], ), true) . '">' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['item']['Product'], ), true) . ' ' . $__templater->escape($__vars['item']['full_title']) . '</a>',
				'hint' => '
								' . $__templater->escape($__templater->method($__vars['item']['Product'], 'getProductTypePhrase', array())) . '
								' . $__compilerTemp3 . '

							',
				'explain' => '
								' . $__compilerTemp4 . '
							',
				'_type' => 'main',
				'html' => '',
			));
			if ($__templater->method($__vars['item']['Product'], 'hasQuantityFunctionality', array())) {
				$__compilerTemp5[] = array(
					'class' => 'dataList-cell--min',
					'_type' => 'cell',
					'html' => '
								' . $__templater->formNumberBox(array(
					'name' => 'quantity[' . $__vars['item']['order_item_id'] . ']',
					'value' => $__vars['item']['quantity'],
					'min' => '0',
					'max' => $__vars['item']['Cost']['stock'],
					'step' => '1',
				)) . '
							',
				);
			} else {
				$__compilerTemp5[] = array(
					'_type' => 'cell',
					'html' => '&nbsp;',
				);
			}
			$__compilerTemp5[] = array(
				'class' => 'dataList-cell--separated dataList-cell--alt dataList-cell--min',
				'style' => 'text-align: start;',
				'_type' => 'cell',
				'html' => '
							' . $__templater->filter($__vars['item']['base_price'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '
						',
			);
			$__compilerTemp2 .= $__templater->dataRow(array(
				'rowclass' => 'prefixGroup' . $__vars['prefixGroupId'] . ' js-checkAllCartItems',
			), $__compilerTemp5) . '
				';
		}
	}
	$__compilerTemp6 = '';
	if ($__vars['showUpdate']) {
		$__compilerTemp6 .= '
					' . $__templater->button('Update', array(
			'type' => 'submit',
			'name' => 'update_quantity',
			'value' => '1',
		), '', array(
		)) . '
				';
	}
	$__compilerTemp7 = '';
	$__compilerTemp8 = '';
	$__compilerTemp8 .= '
					';
	if ($__vars['xf']['options']['dbtechEcommerceCoupons']['enabled']) {
		$__compilerTemp8 .= '
						' . $__templater->formTextBoxRow(array(
			'name' => 'coupon_code',
			'value' => ($__vars['order']['Coupon'] ? $__vars['order']['Coupon']['coupon_code'] : ''),
		), array(
			'label' => 'Coupon code',
			'explain' => 'If you have a coupon code, you can apply it here. It will automatically discount the relevant items.',
		)) . '
					';
	}
	$__compilerTemp8 .= '

					';
	if ($__vars['xf']['visitor']['dbtech_ecommerce_store_credit'] > 0) {
		$__compilerTemp8 .= '
						' . $__templater->formNumberBoxRow(array(
			'name' => 'store_credit',
			'value' => $__vars['order']['store_credit_amount'],
			'min' => '0',
			'step' => '1',
			'readonly' => (!$__vars['xf']['visitor']['dbtech_ecommerce_store_credit']),
		), array(
			'label' => 'Store credit',
			'explain' => 'You have ' . $__templater->filter($__vars['xf']['visitor']['dbtech_ecommerce_store_credit'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . ' worth of store credit.',
		)) . '
					';
	}
	$__compilerTemp8 .= '

					';
	if ($__templater->method($__vars['order'], 'hasVatInfo', array())) {
		$__compilerTemp8 .= '
						';
		if ($__vars['order']['Address']['sales_tax_id']) {
			$__compilerTemp8 .= '
							' . $__templater->formRow('
								' . $__templater->escape($__vars['order']['Address']['sales_tax_id']) . '
							', array(
				'label' => 'VAT registration number',
			)) . '
						';
		} else {
			$__compilerTemp8 .= '
							';
			$__vars['country'] = $__templater->method($__vars['xf']['app']['em'], 'find', array('DBTech\\eCommerce:Country', $__vars['xf']['options']['dbtechEcommerceAddressCountry'], ));
			$__compilerTemp8 .= '

							' . $__templater->formTextBoxRow(array(
				'name' => 'sales_tax_id',
				'maxlength' => $__templater->func('max_length', array('DBTech\\eCommerce:Address', 'sales_tax_id', ), false),
			), array(
				'label' => 'VAT registration number',
				'explain' => 'If you provide a VAT ID and your business is registered outside of ' . $__templater->escape($__vars['country']['name']) . ', we don\'t need to charge VAT on your order.<br />
Please enter your VAT ID without spaces.',
			)) . '
						';
		}
		$__compilerTemp8 .= '
					';
	}
	$__compilerTemp8 .= '
				';
	if (strlen(trim($__compilerTemp8)) > 0) {
		$__compilerTemp7 .= '
			<hr class="block-separator" />

			<h3 class="block-header">' . 'Purchase options' . '</h3>
			<div class="block-body">
				' . $__compilerTemp8 . '
			</div>
		';
	}
	$__compilerTemp9 = '';
	if ($__templater->isTraversable($__vars['order']['Items'])) {
		foreach ($__vars['order']['Items'] AS $__vars['item']) {
			$__compilerTemp9 .= '
			';
			$__compilerTemp10 = '';
			$__compilerTemp10 .= '
						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
				'type' => 'dbtechEcommerceOrders',
				'set' => $__vars['item']['product_fields'],
				'onlyInclude' => $__vars['item']['Product']['field_cache'],
				'namePrefix' => 'product_fields[' . $__vars['item']['order_item_id'] . ']',
				'valueClass' => 'formRow',
			), $__vars) . '
					';
			if (strlen(trim($__compilerTemp10)) > 0) {
				$__compilerTemp9 .= '
				<hr class="block-separator" />

				<h3 class="block-header">' . $__templater->escape($__vars['item']['full_title']) . '</h3>
				<div class="block-body">
					' . $__compilerTemp10 . '
				</div>
			';
			}
			$__compilerTemp9 .= '
		';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">

		<h2 class="block-header">' . $__templater->formCheckBox(array(
		'standalone' => 'true',
	), array(array(
		'check-all' => '.dataList .js-checkAllCartItems >',
		'_type' => 'option',
	))) . 'Cart' . '</h2>
		<div class="block-body">
			' . $__templater->dataList('
				<tbody class="dataList-rowGroup">
				' . $__compilerTemp2 . '
				</tbody>
			', array(
	)) . '
		</div>
		<div class="block-footer block-footer--split">
			<span class="block-footer-counter">
				' . $__templater->button('Delete selected', array(
		'type' => 'submit',
		'name' => 'delete',
		'value' => '1',
	), '', array(
	)) . '
				' . $__compilerTemp6 . '
			</span>
			<span class="block-footer-controls">
				<b>' . 'Sub-total' . $__vars['xf']['language']['label_separator'] . '</b> ' . $__templater->filter($__vars['order']['sub_total'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '
			</span>
		</div>

		' . $__compilerTemp7 . '

		' . $__compilerTemp9 . '

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>

', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/checkout/update', $__vars['order'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-force-flash-message' => 'true',
	)) . '

';
	if (($__vars['order']['order_total'] > 0) AND ($__templater->func('count', array($__vars['xf']['options']['dbtechEcommercePaymentProfileIds'], ), false) == 0)) {
		$__finalCompiled .= '
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				<p class="block-rowMessage block-rowMessage--error block-rowMessage--iconic">
					<strong>' . 'Error' . $__vars['xf']['language']['label_separator'] . '</strong>
					' . 'The system has not been configured correctly: no valid payment profile selected. Please <a href="' . $__templater->func('link', array('canonical:misc/contact', ), true) . '">contact us</a> and notify the administration of this problem.' . '
				</p>
			', array(
		)) . '
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	';
		$__compilerTemp11 = '';
		if ($__vars['xf']['options']['dbtechEcommerceTermsPageId']) {
			$__compilerTemp11 .= '
				<h3 class="block-minorHeader">
					' . $__templater->escape($__vars['xf']['visitor']['dbtech_ecommerce_terms']['title']) . '
					<span class="block-desc">
					<b>' . 'Last updated' . $__vars['xf']['language']['label_separator'] . '</b> ' . $__templater->func('date_time', array($__vars['xf']['visitor']['dbtech_ecommerce_terms']['modified_date'], ), true) . '
				</span>
				</h3>
				<div class="block-body">
					<div class="block-row block-row--dbtechEcommerceTerms">
						' . $__templater->includeTemplate($__templater->method($__vars['xf']['visitor']['dbtech_ecommerce_terms'], 'getTemplateName', array()), $__vars) . '
					</div>

					';
			$__compilerTemp12 = array(array(
				'name' => 'confirm',
				'class' => 'js-consentBoxes',
				'label' => 'I have read and agree to the Terms of Service',
				'_type' => 'option',
			));
			if ($__templater->method($__vars['order'], 'hasDigitalDownload', array()) AND $__vars['xf']['options']['dbtechEcommerceSeparateRefundPolicy']) {
				$__compilerTemp12[] = array(
					'name' => 'confirm_digital_refund',
					'class' => 'js-consentBoxes',
					'label' => ' I understand that after downloading this product I cannot cancel/refund.',
					'_type' => 'option',
				);
			}
			$__compilerTemp11 .= $__templater->formCheckBoxRow(array(
				'data-xf-init' => 'dbtech-ecommerce-multi-disabler',
				'data-input-controls' => '.js-consentBoxes',
				'data-container' => '.js-submitDisable',
			), $__compilerTemp12, array(
				'rowtype' => 'fullWidth noLabel',
			)) . '
				</div>
			';
		} else {
			$__compilerTemp11 .= '
				<h3 class="block-formSectionHeader">
					' . 'Terms and rules' . '
				</h3>
				<div class="block-body">
					<div class="block-row block-row--dbtechEcommerceTerms">
						' . '<p>The providers ("we", "us", "our") of the service provided by this web site ("Service") are not responsible for any user-generated content and accounts. Content submitted express the views of their author only.</p>

<p>This Service is only available to users who are at least ' . ($__templater->escape($__vars['xf']['options']['registrationSetup']['minimumAge']) ?: 13) . ' years old. If you are younger than this, please do not register for this Service. If you register for this Service, you represent that you are this age or older.</p>

<p>All content you submit, upload, or otherwise make available to the Service ("Content") may be reviewed by staff members. All Content you submit or upload may be sent to third-party verification services (including, but not limited to, spam prevention services). Do not submit any Content that you consider to be private or confidential.</p>

<p>You agree to not use the Service to submit or link to any Content which is defamatory, abusive, hateful, threatening, spam or spam-like, likely to offend, contains adult or objectionable content, contains personal information of others, risks copyright infringement, encourages unlawful activity, or otherwise violates any laws. You are entirely responsible for the content of, and any harm resulting from, that Content or your conduct.</p>

<p>We may remove or modify any Content submitted at any time, with or without cause, with or without notice. Requests for Content to be removed or modified will be undertaken only at our discretion. We may terminate your access to all or any part of the Service at any time, with or without cause, with or without notice.</p>

<p>You are granting us with a non-exclusive, permanent, irrevocable, unlimited license to use, publish, or re-publish your Content in connection with the Service. You retain copyright over the Content.</p>

<p>These terms may be changed at any time without notice.</p>

<p>If you do not agree with these terms, please do not register or use the Service. Use of the Service constitutes acceptance of these terms. If you wish to close your account, please <a href="{contactUrl}">contact us</a>.</p>' . '
					</div>

					';
			$__compilerTemp13 = array(array(
				'name' => 'confirm',
				'class' => 'js-consentBoxes',
				'label' => 'I have read and agree to the Terms of Service',
				'_type' => 'option',
			));
			if ($__templater->method($__vars['order'], 'hasDigitalDownload', array()) AND $__vars['xf']['options']['dbtechEcommerceSeparateRefundPolicy']) {
				$__compilerTemp13[] = array(
					'name' => 'confirm_digital_refund',
					'class' => 'js-consentBoxes',
					'label' => ' I understand that after downloading this product I cannot cancel/refund.',
					'_type' => 'option',
				);
			}
			$__compilerTemp11 .= $__templater->formCheckBoxRow(array(
				'data-xf-init' => 'dbtech-ecommerce-multi-disabler',
				'data-input-controls' => '.js-consentBoxes',
				'data-container' => '.js-submitDisable',
			), $__compilerTemp13, array(
				'rowtype' => 'fullWidth noLabel',
			)) . '
				</div>
			';
		}
		$__finalCompiled .= $__templater->form('
		<div class="block-container">
			<h2 class="block-header">' . 'Checkout' . '</h2>
			' . $__compilerTemp11 . '

			' . $__templater->formSubmitRow(array(
			'submit' => (($__vars['order']['order_total'] > 0) ? 'Proceed to payment' : 'Get free'),
			'icon' => 'purchase',
		), array(
			'rowclass' => 'js-submitDisable',
		)) . '

			<div class="js-paymentProviderReply-dbtech_ecommerce_order' . $__templater->escape($__vars['order']['order_id']) . '"></div>
		</div>

	', array(
			'action' => $__templater->func('link', array('dbtech-ecommerce/checkout/complete', ), false),
			'class' => 'block',
			'ajax' => 'true',
			'data-xf-init' => 'payment-provider-container',
		)) . '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp14 = '';
	if ($__vars['order']['order_total'] > 0) {
		$__compilerTemp14 .= '
					<dl class="pairs pairs--justified"><dt><b>' . 'Sub-total' . '</b></dt> <dd>' . $__templater->filter($__vars['order']['sub_total'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</dd></dl>
				';
	}
	$__compilerTemp15 = '';
	if ($__vars['xf']['options']['dbtechEcommerceSales']['enabled'] AND (($__vars['order']['sale_discounts'] > 0) OR $__vars['xf']['options']['dbtechEcommerceSales']['alwaysShow'])) {
		$__compilerTemp15 .= '
					<dl class="pairs pairs--justified"><dt>' . 'Sale discounts' . '</dt> <dd>-' . $__templater->filter($__vars['order']['sale_discounts'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</dd></dl>
				';
	}
	$__compilerTemp16 = '';
	if ($__vars['xf']['options']['dbtechEcommerceCoupons']['enabled'] AND (($__vars['order']['coupon_discounts'] > 0) OR $__vars['xf']['options']['dbtechEcommerceCoupons']['alwaysShow'])) {
		$__compilerTemp16 .= '
					<dl class="pairs pairs--justified"><dt>' . 'Coupon discounts' . '</dt> <dd>-' . $__templater->filter($__vars['order']['coupon_discounts'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</dd></dl>
				';
	}
	$__compilerTemp17 = '';
	if ($__vars['order']['automatic_discounts'] > 0) {
		$__compilerTemp17 .= '
					<dl class="pairs pairs--justified"><dt>' . 'Automatic discounts' . '</dt> <dd>-' . $__templater->filter($__vars['order']['automatic_discounts'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</dd></dl>
				';
	}
	$__compilerTemp18 = '';
	if ($__vars['order']['store_credit_amount'] > 0) {
		$__compilerTemp18 .= '
					<dl class="pairs pairs--justified"><dt>' . 'Store credit used' . '</dt> <dd>-' . $__templater->filter($__vars['order']['store_credit_amount'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</dd></dl>
				';
	}
	$__compilerTemp19 = '';
	if ($__templater->method($__vars['order'], 'hasPhysicalProduct', array())) {
		$__compilerTemp19 .= '
					<dl class="pairs pairs--justified"><dt>' . 'Shipping cost' . '</dt> <dd>+' . $__templater->filter($__vars['order']['shipping_cost'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</dd></dl>
				';
	}
	$__compilerTemp20 = '';
	if ($__vars['xf']['options']['dbtechEcommerceSalesTax']['enabled'] AND ($__vars['order']['sales_tax'] > 0)) {
		$__compilerTemp20 .= '
					<dl class="pairs pairs--justified"><dt>' . 'Sales tax' . '</dt> <dd>+' . $__templater->filter($__vars['order']['sales_tax'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</dd></dl>
				';
	}
	$__templater->modifySidebarHtml('pricingInfo', '
	<div class="block">
		<div class="block-container">
			<h3 class="block-minorHeader">' . 'Order totals' . '</h3>

			<div class="block-body block-row block-row--minor">
				' . $__compilerTemp14 . '

				' . $__compilerTemp15 . '

				' . $__compilerTemp16 . '

				' . $__compilerTemp17 . '

				' . $__compilerTemp18 . '

				' . $__compilerTemp19 . '

				' . $__compilerTemp20 . '

				<dl class="pairs pairs--justified"><dt><b>' . 'Order total' . '</b></dt> <dd>' . $__templater->filter($__vars['order']['order_total'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '</dd></dl>
			</div>
		</div>
	</div>
', 'replace');
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['order'], 'isAddressRequired', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp21 = '';
		if ($__vars['order']['Address']['business_co']) {
			$__compilerTemp21 .= '
						<li>' . $__templater->escape($__vars['order']['Address']['business_co']) . '</li>
					';
		}
		$__compilerTemp22 = '';
		if ($__vars['order']['Address']['address1']) {
			$__compilerTemp22 .= '
						<li>' . $__templater->escape($__vars['order']['Address']['address1']) . '</li>
					';
		}
		$__compilerTemp23 = '';
		if ($__vars['order']['Address']['address2']) {
			$__compilerTemp23 .= '
						<li>' . $__templater->escape($__vars['order']['Address']['address2']) . '</li>
					';
		}
		$__compilerTemp24 = '';
		if ($__vars['order']['Address']['address3']) {
			$__compilerTemp24 .= '
						<li>' . $__templater->escape($__vars['order']['Address']['address3']) . '</li>
					';
		}
		$__compilerTemp25 = '';
		if ($__vars['order']['Address']['address4']) {
			$__compilerTemp25 .= '
						<li>' . $__templater->escape($__vars['order']['Address']['address4']) . '</li>
					';
		}
		$__templater->modifySidebarHtml('billingAddress', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader">' . 'Billing address' . '</h3>

				<ul class="block-body block-row block-row--minor">
					<li>' . $__templater->escape($__vars['order']['Address']['business_title']) . '</li>

					' . $__compilerTemp21 . '

					' . $__compilerTemp22 . '

					' . $__compilerTemp23 . '

					' . $__compilerTemp24 . '

					' . $__compilerTemp25 . '

					<li>' . $__templater->escape($__vars['order']['Address']['Country']['native_name']) . '</li>

					<li><a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/address', ), true) . '">' . 'Edit' . '</a></li>
				</ul>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '

	';
		if ($__templater->method($__vars['order'], 'hasPhysicalProduct', array())) {
			$__finalCompiled .= '
		';
			$__vars['address'] = ($__vars['order']['shipping_address_id'] ? $__vars['order']['ShippingAddress'] : $__vars['order']['Address']);
			$__compilerTemp26 = '';
			if ($__vars['address']['business_co']) {
				$__compilerTemp26 .= '
							<li>' . $__templater->escape($__vars['address']['business_co']) . '</li>
						';
			}
			$__compilerTemp27 = '';
			if ($__vars['address']['address1']) {
				$__compilerTemp27 .= '
							<li>' . $__templater->escape($__vars['address']['address1']) . '</li>
						';
			}
			$__compilerTemp28 = '';
			if ($__vars['address']['address2']) {
				$__compilerTemp28 .= '
							<li>' . $__templater->escape($__vars['address']['address2']) . '</li>
						';
			}
			$__compilerTemp29 = '';
			if ($__vars['address']['address3']) {
				$__compilerTemp29 .= '
							<li>' . $__templater->escape($__vars['address']['address3']) . '</li>
						';
			}
			$__compilerTemp30 = '';
			if ($__vars['address']['address4']) {
				$__compilerTemp30 .= '
							<li>' . $__templater->escape($__vars['address']['address4']) . '</li>
						';
			}
			$__templater->modifySidebarHtml('shippingAddress', '
			' . '' . '

			<div class="block">
				<div class="block-container">
					<h3 class="block-minorHeader">' . 'Shipping address' . '</h3>

					<ul class="block-body block-row block-row--minor">
						<li>' . $__templater->escape($__vars['address']['business_title']) . '</li>

						' . $__compilerTemp26 . '

						' . $__compilerTemp27 . '

						' . $__compilerTemp28 . '

						' . $__compilerTemp29 . '

						' . $__compilerTemp30 . '

						<li>' . $__templater->escape($__vars['address']['Country']['native_name']) . '</li>

						<li><a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout/address', ), true) . '">' . 'Edit' . '</a></li>
					</ul>
				</div>
			</div>
		', 'replace');
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);