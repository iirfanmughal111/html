<?php
// FROM HASH: 4eebfbeb0bb99525051bb441d24e1e87
return array(
'macros' => array('filter_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'findNew' => '!',
		'submitRoute' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="menu" data-menu="menu" aria-hidden="true">
		<div class="menu-content">
			<h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
			' . $__templater->form('
				<div class="menu-row">
					' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'watched',
		'selected' => $__vars['findNew']['filters']['watched'],
		'label' => 'Watched content',
		'_type' => 'option',
	))) . '
				</div>
				' . '

				' . $__templater->callMacro('filter_macros', 'find_new_filter_footer', array(), $__vars) . '
			', array(
		'action' => $__templater->func('link', array($__vars['submitRoute'], ), false),
	)) . '
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('New products');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('new_dbtech_ecommerce_product');
	$__templater->wrapTemplate('whats_new_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'canAddDbtechEcommerceProduct', array())) {
		$__compilerTemp2 = '';
		$__compilerTemp3 = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductTypeHandlers', array());
		if ($__templater->isTraversable($__compilerTemp3)) {
			foreach ($__compilerTemp3 AS $__vars['productType'] => $__vars['handler']) {
				$__compilerTemp2 .= '
				<a href="' . $__templater->func('link', array('dbtech-ecommerce/add', null, array('product_type' => $__vars['productType'], ), ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . $__templater->escape($__templater->method($__vars['handler'], 'getProductTypePhrase', array())) . '</a>
			';
			}
		}
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Manage' . $__vars['xf']['language']['ellipsis'], array(
			'class' => 'menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
		), '', array(
		)) . '
	<div class="menu" data-menu="menu" aria-hidden="true">
		<div class="menu-content">
			<h3 class="menu-header">' . 'Add product' . '</h3>

			' . $__compilerTemp2 . '
		</div>
	</div>
');
	}
	$__finalCompiled .= '

';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="dbtech_ecommerce_product" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="block-outer">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['findNew']['result_count'],
			'link' => 'whats-new/ecommerce-products',
			'data' => $__vars['findNew'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			';
		$__compilerTemp4 = '';
		$__compilerTemp4 .= '
						';
		if ($__vars['canInlineMod']) {
			$__compilerTemp4 .= '
							' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
						';
		}
		$__compilerTemp4 .= '
					';
		if (strlen(trim($__compilerTemp4)) > 0) {
			$__finalCompiled .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
					' . $__compilerTemp4 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= '
		</div>
	';
	}
	$__finalCompiled .= '

	<div class="block-container">
		';
	if ($__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
			<div class="block-filterBar">
				<div class="filterBar">
					';
		$__compilerTemp5 = '';
		$__compilerTemp5 .= '
							' . '
							';
		if ($__vars['findNew']['filters']['watched']) {
			$__compilerTemp5 .= '
								<li><a href="' . $__templater->func('link', array('whats-new/ecommerce-products', $__vars['findNew'], array('remove' => 'watched', ), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Show only' . '</span>
									' . 'Watched' . '</a></li>
							';
		}
		$__compilerTemp5 .= '
							' . '
						';
		if (strlen(trim($__compilerTemp5)) > 0) {
			$__finalCompiled .= '
						<ul class="filterBar-filters">
						' . $__compilerTemp5 . '
						</ul>
					';
		}
		$__finalCompiled .= '

					<a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
					' . $__templater->callMacro(null, 'filter_menu', array(
			'findNew' => $__vars['findNew'],
			'submitRoute' => $__templater->func('link', array('whats-new/ecommerce-products', ), false),
		), $__vars) . '
				</div>
			</div>
		';
	}
	$__finalCompiled .= '

		';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
			<div class="structItemContainer">
				';
		if ($__templater->isTraversable($__vars['products'])) {
			foreach ($__vars['products'] AS $__vars['product']) {
				$__finalCompiled .= '
					' . $__templater->callMacro('dbtech_ecommerce_product_list_macros', 'product', array(
					'product' => $__vars['product'],
				), $__vars) . '
				';
			}
		}
		$__finalCompiled .= '
			</div>
		';
	} else {
		$__finalCompiled .= '
			<div class="block-row">' . 'No results found.' . '</div>
		';
	}
	$__finalCompiled .= '
	</div>

	';
	if ($__vars['findNew']['result_count']) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['findNew']['result_count'],
			'link' => 'whats-new/ecommerce-products',
			'data' => $__vars['findNew'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>
	';
	}
	$__finalCompiled .= '
</div>

';
	return $__finalCompiled;
}
);