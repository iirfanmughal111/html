<?php
// FROM HASH: b898a86348278ecc5751db67b7b9b05c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title']));
	$__finalCompiled .= '

';
	$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['product']['tagline'], 250, array('stripBbCode' => true, ), ), false);
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['descSnippet'],
		'shareUrl' => $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['product'], ), false),
		'imageUrl' => ($__vars['product']['icon_date'] ? $__templater->method($__vars['product'], 'getIconUrl', array('m', true, )) : ''),
		'canonicalUrl' => $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['product'], ), false),
	), $__vars) . '


';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array()) AND !$__templater->test($__vars['product']['LatestVersion'], 'empty', array())) {
		$__compilerTemp1 .= '
			"model": "' . $__templater->filter($__vars['product']['LatestVersion']['version_string'], array(array('escape', array('json', )),), true) . '",
		';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['dbtechEcommerceBusinessTitle']) {
		$__compilerTemp2 .= '
			"brand": "' . $__templater->filter($__vars['xf']['options']['dbtechEcommerceBusinessTitle'], array(array('escape', array('json', )),), true) . '",
		';
	}
	$__compilerTemp3 = '';
	if ($__vars['product']['icon_date']) {
		$__compilerTemp3 .= '
			"image": [
				"' . $__templater->escape($__templater->method($__vars['product'], 'getIconUrl', array('s', true, ))) . '",
				"' . $__templater->escape($__templater->method($__vars['product'], 'getIconUrl', array('m', true, ))) . '",
				"' . $__templater->escape($__templater->method($__vars['product'], 'getIconUrl', array('l', true, ))) . '"
			],
		';
	}
	$__compilerTemp4 = '';
	if ($__vars['xf']['options']['dbtechEcommerceEnableRate'] AND $__vars['product']['rating_count']) {
		$__compilerTemp4 .= '"aggregateRating": {
			"@type": "AggregateRating",
			"ratingCount": "' . $__templater->filter($__vars['product']['rating_count'], array(array('escape', array('json', )),), true) . '",
			"ratingValue": "' . $__templater->filter($__vars['product']['rating_avg'], array(array('escape', array('json', )),), true) . '"
		},';
	}
	$__compilerTemp5 = '';
	if ($__vars['product']['is_paid']) {
		$__compilerTemp5 .= '"offers": ' . $__templater->filter($__vars['productCosts'], array(array('json', array()),array('raw', array()),), true) . ',
		';
	} else {
		$__compilerTemp5 .= '"offers": {
			"@type": "Offer",
			"price": "0.00",
			"priceCurrency": "' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '",
			"availability": "InStock",
			"url": "' . $__templater->escape($__templater->method($__vars['product'], 'getProductPageUrl', array())) . '"
		},
		';
	}
	$__templater->setPageParam('ldJsonHtml', '
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Product",
		"@id": "' . $__templater->filter($__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['product'], ), false), array(array('escape', array('json', )),), true) . '",
		"name": "' . $__templater->filter($__vars['product']['title'], array(array('escape', array('json', )),), true) . '",
		"disambiguatingDescription": "' . $__templater->filter($__vars['product']['tagline'], array(array('escape', array('json', )),), true) . '",
		"description": "' . $__templater->filter($__vars['descSnippet'], array(array('escape', array('json', )),), true) . '",
		' . $__compilerTemp1 . '
		"sku": "' . $__templater->escape($__vars['product']['product_id']) . '",
		' . $__compilerTemp2 . '
		' . $__compilerTemp3 . '
		' . $__compilerTemp4 . '
		' . $__compilerTemp5 . '
		"releaseDate": "' . $__templater->filter($__templater->func('date', array($__vars['product']['last_update'], 'c', ), false), array(array('escape', array('json', )),), true) . '"
	}
	</script>
');
	$__finalCompiled .= '

';
	$__templater->setPageParam('product', $__vars['product']);
	$__finalCompiled .= '

';
	$__compilerTemp6 = $__vars;
	$__compilerTemp6['pageSelected'] = 'overview';
	$__templater->wrapTemplate('dbtech_ecommerce_product_wrapper', $__compilerTemp6);
	$__finalCompiled .= '

';
	$__compilerTemp7 = '';
	$__compilerTemp7 .= $__templater->func('bb_code', array($__vars['product']['description_full'], 'dbtech_ecommerce_description_full', $__vars['product'], ), true);
	if (strlen(trim($__compilerTemp7)) > 0) {
		$__finalCompiled .= '
	<div class="block-body">
		<div class="block-row">
			' . $__compilerTemp7 . '
		</div>
	</div>
';
	}
	$__finalCompiled .= '

<div class="block-body js-productBody">
	<div class="productBody">
		<div class="productBody--main">
			';
	$__compilerTemp8 = '';
	$__compilerTemp8 .= '
						';
	$__compilerTemp9 = '';
	$__compilerTemp9 .= '
									' . $__templater->callMacro('bookmark_macros', 'link', array(
		'content' => $__vars['product'],
		'confirmUrl' => $__templater->func('link', array('dbtech-ecommerce/bookmark', $__vars['product'], ), false),
	), $__vars) . '
									' . $__templater->func('react', array(array(
		'content' => $__vars['product'],
		'link' => 'dbtech-ecommerce/react',
		'list' => '< .js-productBody | .js-reactionsList',
	))) . '
								';
	if (strlen(trim($__compilerTemp9)) > 0) {
		$__compilerTemp8 .= '
							<div class="actionBar-set actionBar-set--external">
								' . $__compilerTemp9 . '
							</div>
						';
	}
	$__compilerTemp8 .= '

						';
	$__compilerTemp10 = '';
	$__compilerTemp10 .= '
									';
	if ($__templater->method($__vars['product'], 'canReport', array())) {
		$__compilerTemp10 .= '
										<a href="' . $__templater->func('link', array('dbtech-ecommerce/report', $__vars['product'], ), true) . '"
										   class="actionBar-action actionBar-action--report" data-xf-click="overlay">' . 'Report' . '</a>
									';
	}
	$__compilerTemp10 .= '

									';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp10 .= '
									';
	if ($__templater->method($__vars['product'], 'canEdit', array())) {
		$__compilerTemp10 .= '
										<a href="' . $__templater->func('link', array('dbtech-ecommerce/edit', $__vars['product'], ), true) . '"
										   class="actionBar-action actionBar-action--edit actionBar-action--menuItem">' . 'Edit' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
									';
	}
	$__compilerTemp10 .= '
									';
	if ($__templater->method($__vars['product'], 'canDelete', array('soft', ))) {
		$__compilerTemp10 .= '
										<a href="' . $__templater->func('link', array('dbtech-ecommerce/delete', $__vars['product'], ), true) . '"
										   class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
										   data-xf-click="overlay">' . 'Delete' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
									';
	}
	$__compilerTemp10 .= '
									';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['product']['ip_id']) {
		$__compilerTemp10 .= '
										<a href="' . $__templater->func('link', array('dbtech-ecommerce/ip', $__vars['product'], ), true) . '"
										   class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
										   data-xf-click="overlay">' . 'IP' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
									';
	}
	$__compilerTemp10 .= '
									';
	if ($__templater->method($__vars['product'], 'canWarn', array())) {
		$__compilerTemp10 .= '
										<a href="' . $__templater->func('link', array('dbtech-ecommerce/warn', $__vars['product'], ), true) . '"
										   class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
										';
	} else if ($__vars['product']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp10 .= '
										<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['product']['warning_id'], ), ), true) . '"
										   class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
										   data-xf-click="overlay">' . 'View warning' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp10 .= '
									';
	}
	$__compilerTemp10 .= '

									';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp10 .= '
										<a class="actionBar-action actionBar-action--menuTrigger"
										   data-xf-click="menu"
										   title="' . 'More options' . '"
										   role="button"
										   tabindex="0"
										   aria-expanded="false"
										   aria-haspopup="true">&#8226;&#8226;&#8226;</a>

										<div class="menu" data-menu="menu" aria-hidden="true" data-menu-builder="actionBar">
											<div class="menu-content">
												<h4 class="menu-header">' . 'More options' . '</h4>
												<div class="js-menuBuilderTarget"></div>
											</div>
										</div>
									';
	}
	$__compilerTemp10 .= '
								';
	if (strlen(trim($__compilerTemp10)) > 0) {
		$__compilerTemp8 .= '
							<div class="actionBar-set actionBar-set--internal">
								' . $__compilerTemp10 . '
							</div>
						';
	}
	$__compilerTemp8 .= '
					';
	if (strlen(trim($__compilerTemp8)) > 0) {
		$__finalCompiled .= '
				<div class="actionBar">
					' . $__compilerTemp8 . '
				</div>
			';
	}
	$__finalCompiled .= '

			<div class="reactionsBar js-reactionsList ' . ($__vars['product']['reactions'] ? 'is-active' : '') . '">
				' . $__templater->func('reactions', array($__vars['product'], 'dbtech-ecommerce/reactions', array())) . '
			</div>

			<div class="js-historyTarget toggleTarget" data-href="trigger-href"></div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);