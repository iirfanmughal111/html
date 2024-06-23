<?php
// FROM HASH: 76877a9e3924e1815f06ef5acb721111
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<!DOCTYPE html>
<html id="XF" lang="' . $__templater->escape($__vars['xf']['language']['language_code']) . '" dir="' . $__templater->escape($__vars['xf']['language']['text_direction']) . '" class="has-no-js">
<head>
	<base href="' . $__templater->escape($__vars['xf']['options']['boardUrl']) . '/">
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

	';
	$__vars['siteName'] = $__vars['xf']['options']['boardTitle'];
	$__finalCompiled .= '
	';
	$__vars['h1'] = $__templater->preEscaped($__templater->func('page_h1', array($__vars['siteName'])));
	$__finalCompiled .= '
	';
	$__vars['description'] = $__templater->preEscaped($__templater->func('page_description'));
	$__finalCompiled .= '

	<title>' . $__templater->func('page_title', array('%s | %s', $__vars['xf']['options']['boardTitle'], $__vars['pageNumber'])) . '</title>

	';
	if ($__templater->isTraversable($__vars['head'])) {
		foreach ($__vars['head'] AS $__vars['headTag']) {
			$__finalCompiled .= '
		' . $__templater->escape($__vars['headTag']) . '
	';
		}
	}
	$__finalCompiled .= '

	';
	$__vars['cssUrls'] = array('public:normalize.css', 'public:core.less', 'public:app.less', 'public:dbtech_ecommerce_invoice.less', );
	$__finalCompiled .= '

	' . $__templater->includeTemplate('font_awesome_setup', $__vars) . '

	<link rel="stylesheet" href="' . $__templater->func('css_url', array($__vars['cssUrls'], ), true) . '" />
</head>
<body>

<div class="p-pageWrapper p-pageWrapper--invoice" id="top">

	';
	if ($__vars['includeLogo']) {
		$__finalCompiled .= '
		<header class="invoice-header" id="header">
			<div class="p-header-inner">
				<div class="p-header-content">

					<div class="invoice-header-logo invoice-header-logo--image">
						<img src="' . $__templater->escape($__vars['logo']) . '" />
					</div>

					<div class="invoice-header-details">
						<h1 class="p-title-value">
							' . 'Invoice' . '
							<span class="invoice-header-paid">
								' . 'Paid' . '
							</span>
						</h1>
						<dl class="invoice-details-pairs pairs--columns pairs--fixedSmall">
							<dt>' . 'Invoice ID' . '</dt>
							<dd>INV' . $__templater->escape($__vars['order']['order_id']) . '</dd>
						</dl>
						<dl class="invoice-details-pairs pairs--columns pairs--fixedSmall">
							<dt>' . 'Date' . '</dt>
							<dd>' . $__templater->func('date', array($__vars['order']['order_date'], ), true) . '</dd>
						</dl>
						<dl class="invoice-details-pairs pairs--columns pairs--fixedSmall">
							<dt>' . 'time' . '</dt>
							<dd>' . $__templater->func('time', array($__vars['order']['order_date'], ), true) . '</dd>
						</dl>
					</div>
				</div>
				';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
							';
		if ($__vars['xf']['options']['dbtechEcommerceBusinessTitle']) {
			$__compilerTemp1 .= '
								';
			$__vars['countryRepo'] = $__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Country', ));
			$__compilerTemp1 .= '

								<div class="address-seller">
									<div>' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceBusinessTitle']) . '</div>

									';
			if ($__vars['xf']['options']['dbtechEcommerceBusinessCo']) {
				$__compilerTemp1 .= '
										<div>' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceBusinessCo']) . '</div>
									';
			}
			$__compilerTemp1 .= '

									';
			if ($__vars['xf']['options']['dbtechEcommerceBusinessAddress1']) {
				$__compilerTemp1 .= '
										<div>' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceBusinessAddress1']) . '</div>
									';
			}
			$__compilerTemp1 .= '

									';
			if ($__vars['xf']['options']['dbtechEcommerceBusinessAddress2']) {
				$__compilerTemp1 .= '
										<div>' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceBusinessAddress2']) . '</div>
									';
			}
			$__compilerTemp1 .= '

									';
			if ($__vars['xf']['options']['dbtechEcommerceBusinessAddress3']) {
				$__compilerTemp1 .= '
										<div>' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceBusinessAddress3']) . '</div>
									';
			}
			$__compilerTemp1 .= '

									';
			if ($__vars['xf']['options']['dbtechEcommerceBusinessAddress4']) {
				$__compilerTemp1 .= '
										<div>' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceBusinessAddress4']) . '</div>
									';
			}
			$__compilerTemp1 .= '

									';
			if ($__vars['xf']['options']['dbtechEcommerceAddressCountry']) {
				$__compilerTemp1 .= '
										<div>' . $__templater->escape($__templater->arrayKey($__templater->method($__templater->method($__vars['countryRepo'], 'findCountryByCode', array($__vars['xf']['options']['dbtechEcommerceAddressCountry'], )), 'fetchOne', array()), 'native_name')) . '</div>
									';
			}
			$__compilerTemp1 .= '

									';
			if ($__vars['xf']['options']['dbtechEcommerceBusinessTaxId']) {
				$__compilerTemp1 .= '
										<div>' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceBusinessTaxId']) . '</div>
									';
			}
			$__compilerTemp1 .= '
								</div>

								';
			if ($__vars['order']['Address']) {
				$__compilerTemp1 .= '
									';
				$__vars['address'] = $__vars['order']['Address'];
				$__compilerTemp1 .= '

									<div class="address-buyer">
										<div>' . $__templater->escape($__vars['address']['business_title']) . '</div>

										';
				if ($__vars['address']['business_co']) {
					$__compilerTemp1 .= '
											<div>' . $__templater->escape($__vars['address']['business_co']) . '</div>
										';
				}
				$__compilerTemp1 .= '

										';
				if ($__vars['address']['address1']) {
					$__compilerTemp1 .= '
											<div>' . $__templater->escape($__vars['address']['address1']) . '</div>
										';
				}
				$__compilerTemp1 .= '

										';
				if ($__vars['address']['address2']) {
					$__compilerTemp1 .= '
											<div>' . $__templater->escape($__vars['address']['address2']) . '</div>
										';
				}
				$__compilerTemp1 .= '

										';
				if ($__vars['address']['address3']) {
					$__compilerTemp1 .= '
											<div>' . $__templater->escape($__vars['address']['address3']) . '</div>
										';
				}
				$__compilerTemp1 .= '

										';
				if ($__vars['address']['address4']) {
					$__compilerTemp1 .= '
											<div>' . $__templater->escape($__vars['address']['address4']) . '</div>
										';
				}
				$__compilerTemp1 .= '

										';
				if ($__vars['address']['Country']) {
					$__compilerTemp1 .= '
											<div>' . $__templater->escape($__vars['address']['Country']['native_name']) . '</div>
										';
				}
				$__compilerTemp1 .= '

										';
				if ($__vars['address']['sales_tax_id']) {
					$__compilerTemp1 .= '
											<div>' . $__templater->escape($__vars['address']['sales_tax_id']) . '</div>
										';
				}
				$__compilerTemp1 .= '
									</div>
								';
			}
			$__compilerTemp1 .= '
							';
		}
		$__compilerTemp1 .= '
						';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
					<div class="invoice-header-addresses">
						' . $__compilerTemp1 . '
					</div>
				';
		}
		$__finalCompiled .= '
			</div>

			<div style="clear: both;"></div>
		</header>
	';
	}
	$__finalCompiled .= '

	<div class="p-body">
		<div class="p-body-inner">
			<!--XF:EXTRA_OUTPUT-->

			<div class="p-body-main">

				<div class="p-body-content">
					<div class="p-body-pageContent">' . $__templater->filter($__vars['content'], array(array('raw', array()),), true) . '</div>
				</div>
			</div>
		</div>
	</div>

	<footer class="p-footer" id="footer">
		<div class="p-footer-inner">

			<div class="p-footer-row">
				<div class="p-footer-row-main" align="center" style="float: none">
					' . 'Thank you for your business.' . '
				</div>
			</div>
		</div>
	</footer>

</div> <!-- closing p-pageWrapper -->

</body>
</html>';
	return $__finalCompiled;
}
);