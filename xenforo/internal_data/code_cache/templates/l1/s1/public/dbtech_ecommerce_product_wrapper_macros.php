<?php
// FROM HASH: 098af05409569922eafa2fe828a9c339
return array(
'macros' => array('header' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
		'titleHtml' => null,
		'showMeta' => true,
		'metaHtml' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	if ($__vars['titleHtml'] !== null) {
		$__compilerTemp1 .= '
							' . $__templater->filter($__vars['titleHtml'], array(array('raw', array()),), true) . '
						';
	} else {
		$__compilerTemp1 .= '
							' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title']) . '
						';
	}
	$__compilerTemp2 = '';
	if ($__vars['showMeta']) {
		$__compilerTemp2 .= '
					<div class="p-description">
						';
		if ($__vars['metaHtml'] !== null) {
			$__compilerTemp2 .= '
							' . $__templater->filter($__vars['metaHtml'], array(array('raw', array()),), true) . '
						';
		}
		$__compilerTemp2 .= '
					</div>
				';
	}
	$__templater->setPageParam('headerHtml', '
		<div class="contentRow contentRow--hideFigureNarrow">
			<div class="contentRow-main">
				<div class="p-title">
					<h1 class="p-title-value">
						' . $__compilerTemp1 . '
					</h1>
				</div>
				' . $__compilerTemp2 . '
			</div>
		</div>
	');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'description' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['product']['description'], 'empty', array()) OR $__vars['product']['icon_date']) {
		$__finalCompiled .= '
		<div class="block-row">
			<div class="block-row--productIcon">' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['product'], 'l', ), true) . '</div>
			' . $__templater->func('bb_code', array($__vars['product']['description'], 'dbtech_ecommerce_description', $__vars['product'], ), true) . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'tabs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
		'licence' => null,
		'pageSelected' => 'overview',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	if (!$__templater->test($__vars['product']['product_specification'], 'empty', array())) {
		$__compilerTemp1 .= '
						<a class="tabs-tab ' . (($__vars['pageSelected'] == 'specifications') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('dbtech-ecommerce/specifications', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), true) . '">' . ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array()) ? 'Feature list' : 'Product specifications') . '</a>
					';
	}
	$__compilerTemp1 .= '
					';
	if (!$__templater->test($__vars['product']['copyright_info'], 'empty', array())) {
		$__compilerTemp1 .= '
						<a class="tabs-tab ' . (($__vars['pageSelected'] == 'copyright') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('dbtech-ecommerce/copyright', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), true) . '">' . 'Copyright info' . '</a>
					';
	}
	$__compilerTemp1 .= '
					';
	$__compilerTemp2 = $__templater->method($__vars['product'], 'getExtraFieldTabs', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['fieldId'] => $__vars['fieldValue']) {
			$__compilerTemp1 .= '
						<a class="tabs-tab ' . (($__vars['pageSelected'] == ('field_' . $__vars['fieldId'])) ? 'is-active' : '') . '" href="' . $__templater->func('link', array('dbtech-ecommerce/field', $__vars['product'], array('field' => $__vars['fieldId'], ), ), true) . '">' . $__templater->escape($__vars['fieldValue']) . '</a>
					';
		}
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array()) AND $__vars['product']['release_count']) {
		$__compilerTemp1 .= '
						<a class="tabs-tab ' . (($__vars['pageSelected'] == 'releases') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('dbtech-ecommerce/releases', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), true) . '">' . 'Releases' . ' ' . $__templater->filter($__vars['product']['real_release_count'], array(array('parens', array()),), true) . '</a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array()) AND !$__templater->test($__vars['product']['UserLicenses'], 'empty', array())) {
		$__compilerTemp1 .= '
						<a class="tabs-tab ' . (($__vars['pageSelected'] == 'user_licenses') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('dbtech-ecommerce/product-licenses', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), true) . '">' . 'Licenses' . '</a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__vars['product']['real_review_count']) {
		$__compilerTemp1 .= '
						<a class="tabs-tab ' . (($__vars['pageSelected'] == 'reviews') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('dbtech-ecommerce/reviews', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), true) . '">' . 'Reviews' . ' ' . $__templater->filter($__vars['product']['real_review_count'], array(array('parens', array()),), true) . '</a>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__templater->method($__vars['product'], 'hasViewableDiscussion', array())) {
		$__compilerTemp1 .= '
						<a class="tabs-tab ' . (($__vars['pageSelected'] == 'discussion') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('threads', $__vars['product']['Discussion'], ), true) . '">' . 'Discussion' . '</a>
					';
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<h2 class="block-tabHeader tabs hScroller" data-xf-init="h-scroller">
			<span class="hScroller-scroll">
				<!--[DBTecheCommerce:tabs:start]-->
				<a class="tabs-tab ' . (($__vars['pageSelected'] == 'overview') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), true) . '">' . 'Overview' . '</a>

				' . $__compilerTemp1 . '
				<!--[DBTecheCommerce:tabs:end]-->
			</span>
		</h2>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'sidebar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
		'category' => '!',
		'showCheckout' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
							';
	if ($__templater->isTraversable($__vars['product']['requirements'])) {
		foreach ($__vars['product']['requirements'] AS $__vars['requirement']) {
			$__compilerTemp2 .= '
								<span class="label label--accent label--fullSize">' . $__templater->escape($__vars['requirement']) . '</span>
							';
		}
	}
	$__compilerTemp2 .= '
						';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
					<div class="block-body block-row block-row--minor">
						' . $__compilerTemp2 . '
					</div>
				';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array())) {
		$__compilerTemp3 .= '
						<dl class="pairs pairs--justified"><dt>' . 'Last update' . '</dt> <dd>' . $__templater->func('date_dynamic', array($__vars['product']['last_update'], array(
		))) . '</dd></dl>
						';
		if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('dbtechEcommerceAdmin', 'viewDownloadLog', ))) {
			$__compilerTemp3 .= '
							<dl class="pairs pairs--justified"><dt>' . 'Total downloads' . '</dt> <dd>' . $__templater->filter($__vars['product']['full_download_count'], array(array('number', array()),), true) . '</dd></dl>
						';
		}
		$__compilerTemp3 .= '
					';
	}
	$__compilerTemp4 = '';
	if ($__vars['xf']['options']['dbtechEcommerceEnableRate'] AND ($__templater->func('property', array('dbtechEcommerceProductRatingStyle', ), false) == 'stars')) {
		$__compilerTemp4 .= '
						<dl class="pairs pairs--justified"><dt>' . 'Customer rating' . '</dt> <dd>
							' . $__templater->callMacro('rating_macros', 'stars_text', array(
			'rating' => $__vars['product']['rating_avg'],
			'count' => $__vars['product']['rating_count'],
			'rowClass' => 'ratingStarsRow--textBlock',
			'starsClass' => 'ratingStars--smaller',
		), $__vars) . '
						</dd></dl>
					';
	}
	$__compilerTemp5 = '';
	if ($__vars['xf']['options']['dbtechEcommerceEnableRate'] AND ($__templater->func('property', array('dbtechEcommerceProductRatingStyle', ), false) == 'circle')) {
		$__compilerTemp5 .= '
						' . $__templater->callMacro('dbtech_ecommerce_rating_macros', 'stars_circle', array(
			'rating' => $__vars['product']['rating_avg'],
			'count' => $__vars['product']['rating_count'],
			'rowClass' => 'ratingStarsRow--textBlock',
			'starsClass' => 'ratingStars--smaller',
		), $__vars) . '
					';
	}
	$__compilerTemp6 = '';
	$__compilerTemp7 = '';
	$__compilerTemp7 .= '
								<!--[eCommerce:product_information_buttons_top]-->

								';
	if ($__templater->method($__vars['product'], 'hasViewableDiscussion', array())) {
		$__compilerTemp7 .= '
									' . $__templater->button('Join discussion', array(
			'href' => $__templater->func('link', array('threads', $__vars['product']['Discussion'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp7 .= '

								';
	if ($__templater->method($__vars['product'], 'hasViewableSupportForum', array())) {
		$__compilerTemp7 .= '
									' . $__templater->button('Get support', array(
			'href' => $__templater->func('link', array($__templater->method($__vars['product']['SupportForum']['Node'], 'getRoute', array()), $__vars['product']['SupportForum'], ), false),
			'class' => 'button--link button--fullWidth',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp7 .= '

								<!--[eCommerce:product_information_buttons_bottom]-->
							';
	if (strlen(trim($__compilerTemp7)) > 0) {
		$__compilerTemp6 .= '
						<div class="productInfo-buttons">
							' . $__compilerTemp7 . '
						</div>
					';
	}
	$__templater->modifySidebarHtml('productInfo', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader">' . 'Product Information' . '</h3>

				' . $__compilerTemp1 . '

				<div class="block-body block-row block-row--minor">
					<!--[eCommerce:product_information_above_info_fields]-->

					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceProducts',
		'group' => 'above_info',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['product']['product_fields'],
	), $__vars) . '

					<!--[eCommerce:product_information_below_info_fields]-->

					<dl class="pairs pairs--justified"><dt>' . 'Seller' . '</dt> <dd>' . $__templater->func('username_link', array($__vars['product']['User'], true, array(
	))) . '</dd></dl>
					<dl class="pairs pairs--justified"><dt>' . 'Release date' . '</dt> <dd>' . $__templater->func('date_dynamic', array($__vars['product']['creation_date'], array(
	))) . '</dd></dl>
					' . $__compilerTemp3 . '

					<!--[eCommerce:product_information_below_pairs]-->

					' . $__compilerTemp4 . '

					<!--[eCommerce:product_information_above_info_fields2]-->

					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceProducts',
		'group' => 'below_info',
		'onlyInclude' => $__vars['category']['field_cache'],
		'set' => $__vars['product']['product_fields'],
	), $__vars) . '

					<!--[eCommerce:product_information_below_info_fields2]-->

					' . $__compilerTemp5 . '

					' . $__compilerTemp6 . '

					<!--[eCommerce:product_information_bottom]-->
				</div>
			</div>
		</div>
	', 'replace');
	$__finalCompiled .= '

	';
	$__compilerTemp8 = '';
	$__compilerTemp9 = '';
	$__compilerTemp9 .= '
						';
	if ((!$__vars['license']) OR $__templater->method($__vars['product'], 'canPurchase', array($__vars['license'], ))) {
		$__compilerTemp9 .= '
							<div class="block-body block-row block-row--minor block-row--pricingInfo block-row--pricingInfo' . $__templater->filter($__vars['product']['product_type'], array(array('to_upper', array('ucwords', )),), true) . '">
								';
		if (!$__templater->test($__vars['product']['Costs'], 'empty', array())) {
			$__compilerTemp9 .= '
									';
			if ($__templater->isTraversable($__vars['product']['Costs'])) {
				foreach ($__vars['product']['Costs'] AS $__vars['cost']) {
					$__compilerTemp9 .= '
										';
					if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
						$__compilerTemp9 .= '
											<dl class="pairs pairs--justified pairs--price">
												<dt>' . $__templater->escape($__vars['cost']['length']) . '</dt>
												<dd>
													' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array($__vars['license'], true, true, ))) . '
												</dd>
											</dl>
											';
						if ((!$__templater->method($__vars['cost'], 'isLifetime', array())) AND (!$__vars['license'])) {
							$__compilerTemp9 .= '
												<dl class="pairs pairs--justified pairs--price pairs--renewal">
													<dt>' . 'Renewal cost' . '</dt>
													<dd>
														' . $__templater->escape($__templater->method($__vars['cost'], 'getDigitalRenewalPrice', array(null, true, ))) . '
													</dd>
												</dl>
											';
						}
						$__compilerTemp9 .= '
											';
					} else {
						$__compilerTemp9 .= '
											<dl class="pairs pairs--justified pairs--stock">
												<dt>' . $__templater->escape($__vars['cost']['title']) . '</dt>
												';
						if ((!$__templater->method($__vars['product'], 'hasStockFunctionality', array())) OR $__vars['cost']['stock']) {
							$__compilerTemp9 .= '
													<dd>' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '</dd>
													';
						} else if ($__templater->method($__vars['product'], 'hasStockFunctionality', array())) {
							$__compilerTemp9 .= '
													<dd>' . 'Out of stock!' . ' - <span class="u-muted" style="text-decoration:line-through">' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '</span></dd>
												';
						}
						$__compilerTemp9 .= '
											</dl>
										';
					}
					$__compilerTemp9 .= '
									';
				}
			}
			$__compilerTemp9 .= '
								';
		}
		$__compilerTemp9 .= '
							</div>
						';
	}
	$__compilerTemp9 .= '

						';
	if (!$__templater->test($__vars['product']['Children'], 'empty', array())) {
		$__compilerTemp9 .= '
							';
		if ($__templater->isTraversable($__vars['product']['Children'])) {
			foreach ($__vars['product']['Children'] AS $__vars['childProduct']) {
				if ($__templater->method($__vars['childProduct'], 'canView', array())) {
					$__compilerTemp9 .= '
								<h3 class="block-minorHeader">' . $__templater->escape($__vars['childProduct']['title']) . '</h3>

								<div class="block-body block-row block-row--minor block-row--childProducts">
									';
					if (!$__templater->test($__vars['childProduct']['Costs'], 'empty', array())) {
						$__compilerTemp9 .= '
										';
						if ($__templater->isTraversable($__vars['childProduct']['Costs'])) {
							foreach ($__vars['childProduct']['Costs'] AS $__vars['cost']) {
								$__compilerTemp9 .= '
											';
								if ($__templater->method($__vars['childProduct'], 'hasLicenseFunctionality', array())) {
									$__compilerTemp9 .= '
												<dl class="pairs pairs--justified pairs--price">
													<dt>' . $__templater->escape($__vars['cost']['length']) . '</dt>
													<dd>' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '</dd>
												</dl>
												';
								} else {
									$__compilerTemp9 .= '
												<dl class="pairs pairs--justified pairs--price">
													<dt>' . $__templater->escape($__vars['cost']['title']) . '</dt>
													<dd>' . $__templater->escape($__templater->method($__vars['cost'], 'getPrice', array(null, true, true, ))) . '</dd>
												</dl>
											';
								}
								$__compilerTemp9 .= '
										';
							}
						}
						$__compilerTemp9 .= '
									';
					}
					$__compilerTemp9 .= '
								</div>
							';
				}
			}
		}
		$__compilerTemp9 .= '

						';
	}
	$__compilerTemp9 .= '

						';
	if ($__templater->method($__vars['product'], 'canPurchase', array($__vars['license'], ))) {
		$__compilerTemp9 .= '
							<div class="block-body block-row block-row--minor block-row--purchaseParent">
								';
		$__compilerTemp10 = '';
		if ($__vars['license']) {
			$__compilerTemp10 .= '
										' . 'Renew' . '
									';
		} else if (($__templater->func('count', array($__vars['product']['cost_cache'], ), false) == 1) AND ($__templater->method($__templater->method($__vars['product'], 'getStartingCost', array()), 'getPrice', array()) == 0)) {
			$__compilerTemp10 .= '
										';
			if ($__templater->method($__vars['product'], 'canPurchaseAddOns', array())) {
				$__compilerTemp10 .= '
											' . 'Get free / Purchase add-ons' . '
										';
			} else {
				$__compilerTemp10 .= '
											' . 'Get free' . '
										';
			}
			$__compilerTemp10 .= '
									';
		} else {
			$__compilerTemp10 .= '
										' . 'Purchase' . '
									';
		}
		$__compilerTemp9 .= $__templater->button('
									' . $__compilerTemp10 . '
								', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/purchase', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false),
			'class' => 'button--fullWidth button--cta',
			'icon' => 'purchase',
			'overlay' => 'true',
			'data-cache' => 'false',
		), '', array(
		)) . '
							</div>

							';
		if ($__templater->method($__vars['product'], 'canPurchaseAllAccess', array($__vars['license'], ))) {
			$__compilerTemp9 .= '
								<div class="block-body block-row block-row--minor block-row--purchaseParent">
									' . $__templater->button('
										' . 'Get via All-Access Pass' . '
									', array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/purchase/all-access', $__vars['product'], ), false),
				'class' => 'button--fullWidth button--primary',
				'icon' => 'download',
			), '', array(
			)) . '
								</div>
							';
		} else if (!$__templater->test($__vars['product']['AllAccessLicense'], 'empty', array())) {
			$__compilerTemp9 .= '
								<div class="block-body block-row block-row--minor block-row--purchaseParent">
									' . $__templater->button('
										' . 'Owned via All-Access Pass' . '
									', array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['product']['AllAccessLicense'], ), false),
				'class' => 'button--fullWidth button--primary is-disabled',
				'icon' => 'download',
			), '', array(
			)) . '
								</div>
							';
		}
		$__compilerTemp9 .= '
						';
	} else if ($__templater->method($__vars['product'], 'canPurchaseAddOns', array($__vars['license'], ))) {
		$__compilerTemp9 .= '
							<div class="block-body block-row block-row--minor block-row--purchaseAddOns">
								' . $__templater->button('
									' . 'Buy add-ons' . '
								', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/purchase/add-ons', $__vars['product'], ($__vars['license'] ? array('license_key' => $__vars['license']['license_key'], ) : array()), ), false),
			'icon' => 'purchase',
			'overlay' => 'true',
			'data-cache' => 'false',
		), '', array(
		)) . '
							</div>
						';
	}
	$__compilerTemp9 .= '

						';
	if ($__vars['showCheckout']) {
		$__compilerTemp9 .= '
							<div class="block-body block-row block-row--minor block-row--checkout">
								' . $__templater->button('Checkout', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/checkout', ), false),
			'class' => 'button--cta button--fullWidth',
			'fa' => 'fa-shopping-cart',
		), '', array(
		)) . '
							</div>
						';
	}
	$__compilerTemp9 .= '
					';
	if (strlen(trim($__compilerTemp9)) > 0) {
		$__compilerTemp8 .= '
			<div class="block">
				<div class="block-container">
					<h3 class="block-minorHeader">' . 'Pricing information' . '</h3>

					' . $__compilerTemp9 . '
				</div>
			</div>
		';
	}
	$__templater->modifySidebarHtml('pricingInfo', '
		' . $__compilerTemp8 . '
	', 'replace');
	$__finalCompiled .= '

	';
	$__compilerTemp11 = '';
	$__compilerTemp12 = '';
	$__compilerTemp12 .= '
						';
	$__compilerTemp13 = '';
	$__compilerTemp13 .= '
									' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
								';
	if (strlen(trim($__compilerTemp13)) > 0) {
		$__compilerTemp12 .= '
							<h3 class="block-minorHeader">' . 'Share this product' . '</h3>
							<div class="block-body block-row block-row--separated">
								' . $__compilerTemp13 . '
							</div>
						';
	}
	$__compilerTemp12 .= '
						';
	$__compilerTemp14 = '';
	$__compilerTemp14 .= '
									' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy PRODUCT BB code',
		'text' => '[PRODUCT=product, ' . $__vars['product']['product_id'] . '][/PRODUCT]',
	), $__vars) . '
								';
	if (strlen(trim($__compilerTemp14)) > 0) {
		$__compilerTemp12 .= '
							<div class="block-body block-row block-row--separated">
								' . $__compilerTemp14 . '
							</div>
						';
	}
	$__compilerTemp12 .= '
					';
	if (strlen(trim($__compilerTemp12)) > 0) {
		$__compilerTemp11 .= '
			<div class="block">
				<div class="block-container">
					' . $__compilerTemp12 . '
				</div>
			</div>
		';
	}
	$__templater->modifySidebarHtml('shareProduct', '
		' . $__compilerTemp11 . '
	', 'replace');
	$__finalCompiled .= '

	';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar1735b92d50eebe749ea7d489d993eac9', $__templater->widgetPosition('dbtech_ecommerce_product_sidebar', array(
		'product' => $__vars['product'],
	)), 'replace');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);