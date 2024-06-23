<?php
// FROM HASH: d6c429db4ee57e880994a3596df1ee77
return array(
'macros' => array('product_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'depth' => '1',
		'customPermissions' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['child']) {
			$__finalCompiled .= '
		' . $__templater->callMacro(null, 'product_list_entry', array(
				'product' => $__vars['child']['record'],
				'children' => $__vars['child']['children'],
				'depth' => $__vars['depth'],
				'customPermissions' => $__vars['customPermissions'],
			), $__vars) . '
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'product_list_entry' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'product' => '!',
		'children' => '!',
		'depth' => '1',
		'customPermissions' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = array(array(
		'class' => 'dataList-cell--min dataList-cell--image dataList-cell--imageMedium',
		'href' => $__templater->func('link', array('dbtech-ecommerce/products/edit-icon', $__vars['product'], ), false),
		'overlay' => 'true',
		'_type' => 'cell',
		'html' => '
			' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['product'], 's', ), true) . '
		',
	));
	if ($__vars['depth'] == 1) {
		$__compilerTemp2 = '';
		if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
			$__compilerTemp2 .= '
						' . 'Digital (' . $__templater->filter($__vars['product']['license_count'], array(array('number', array()),), true) . ' copies sold)' . '
					';
		} else {
			$__compilerTemp2 .= '
						' . $__templater->escape($__templater->method($__vars['product'], 'getProductTypePhrase', array())) . '
					';
		}
		$__compilerTemp3 = '';
		if ($__vars['product']['product_state'] == 'visible') {
			$__compilerTemp3 .= '
						' . $__templater->escape($__vars['product']['tagline']) . '
					';
		} else if ($__vars['product']['product_state'] == 'deleted') {
			$__compilerTemp3 .= '
						' . $__templater->callMacro('public:deletion_macros', 'notice', array(
				'log' => $__vars['product']['DeletionLog'],
			), $__vars) . '
					';
		}
		$__compilerTemp1[] = array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/products/edit', $__vars['product'], ), false),
			'hash' => $__vars['product']['product_id'],
			'label' => $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . ' ' . $__templater->escape($__vars['product']['title']),
			'hint' => '
					' . $__compilerTemp2 . '
				',
			'explain' => '
					' . $__compilerTemp3 . '				
				',
			'_type' => 'main',
			'html' => '',
		);
	} else {
		$__compilerTemp4 = '';
		if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
			$__compilerTemp4 .= '
							' . 'Digital (' . $__templater->filter($__vars['product']['license_count'], array(array('number', array()),), true) . ' copies sold)' . '
						';
		} else {
			$__compilerTemp4 .= '
							' . $__templater->escape($__templater->method($__vars['product'], 'getProductTypePhrase', array())) . '
						';
		}
		$__compilerTemp5 = '';
		if ($__vars['product']['product_state'] == 'visible') {
			$__compilerTemp5 .= '
					';
			if ($__vars['product']['tagline']) {
				$__compilerTemp5 .= '<div class="dataList-subRow">' . $__templater->escape($__vars['product']['tagline']) . '</div>';
			}
			$__compilerTemp5 .= '
				';
		} else if ($__vars['product']['product_state'] == 'deleted') {
			$__compilerTemp5 .= '
					<div class="dataList-subRow">' . $__templater->callMacro('public:deletion_macros', 'notice', array(
				'log' => $__vars['product']['DeletionLog'],
			), $__vars) . '</div>
				';
		}
		$__compilerTemp1[] = array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/products/edit', $__vars['product'], ), false),
			'class' => 'dataList-cell--d' . $__vars['depth'],
			'hash' => $__vars['product']['product_id'],
			'_type' => 'cell',
			'html' => '

				<div class="dataList-textRow" dir="auto">
					' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . ' ' . $__templater->escape($__vars['product']['title']) . '
					<span class="dataList-hint" dir="auto">
						' . $__compilerTemp4 . '
					</span>
				</div>
				' . $__compilerTemp5 . '
			',
		);
	}
	$__compilerTemp1[] = array(
		'class' => ($__vars['customPermissions'][$__vars['product']['product_id']] ? 'dataList-cell--highlighted' : '') . ' u-hideMedium',
		'href' => $__templater->func('link', array('dbtech-ecommerce/products/permissions', $__vars['product'], ), false),
		'_type' => 'action',
		'html' => '
			' . 'Permissions' . '
		',
	);
	$__compilerTemp6 = '';
	if ($__vars['depth'] == 1) {
		$__compilerTemp6 .= '
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/move', $__vars['product'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Move' . '</a>
					';
	} else {
		$__compilerTemp6 .= '
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/add-on/move', $__vars['product'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Change parent product' . '</a>
					';
	}
	$__compilerTemp1[] = array(
		'class' => 'dataList-cell--action',
		'label' => 'Manage' . $__vars['xf']['language']['ellipsis'],
		'_type' => 'popup',
		'html' => '

			<div class="menu" data-menu="menu" aria-hidden="true">
				<div class="menu-content">
					<h3 class="menu-header">' . 'Manage' . $__vars['xf']['language']['ellipsis'] . '</h3>
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/add', null, array('source_product_id' => $__vars['product']['product_id'], ), ), true) . '" class="menu-linkRow">' . 'Copy' . '</a>
					' . $__compilerTemp6 . '
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/reassign', $__vars['product'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Reassign' . '</a>
				</div>
			</div>
		',
	);
	if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array()) OR $__templater->method($__vars['product'], 'hasDownloadFunctionality', array())) {
		$__compilerTemp7 = '';
		if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array()) AND ($__vars['depth'] == 1)) {
			$__compilerTemp7 .= '
							<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/add-on/add', null, array('parent_product_id' => $__vars['product']['product_id'], ), ), true) . '" class="menu-linkRow">' . 'Add-on product' . '</a>
						';
		}
		$__compilerTemp8 = '';
		if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array())) {
			$__compilerTemp8 .= '
							<a href="' . $__templater->func('link', array('dbtech-ecommerce/downloads/add', null, array('product_id' => $__vars['product']['product_id'], ), ), true) . '" class="menu-linkRow">' . 'Download' . '</a>
						';
		}
		$__compilerTemp9 = '';
		if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
			$__compilerTemp9 .= '
							<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/add', null, array('product_id' => $__vars['product']['product_id'], ), ), true) . '" class="menu-linkRow">' . 'License' . '</a>
						';
		}
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--action u-hideMedium',
			'label' => 'Add' . $__vars['xf']['language']['ellipsis'],
			'_type' => 'popup',
			'html' => '

				<div class="menu" data-menu="menu" aria-hidden="true">
					<div class="menu-content">
						<h3 class="menu-header">' . 'Add' . $__vars['xf']['language']['ellipsis'] . '</h3>
						' . $__compilerTemp7 . '

						' . $__compilerTemp8 . '

						' . $__compilerTemp9 . '
					</div>
				</div>
			',
		);
	} else {
		$__compilerTemp1[] = array(
			'class' => 'dataList-cell--alt',
			'_type' => 'cell',
			'html' => '&nbsp;',
		);
	}
	$__compilerTemp10 = '';
	if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array())) {
		$__compilerTemp10 .= '
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/downloads', null, array('criteria' => array('product_id' => $__vars['product']['product_id'], ), ), ), true) . '" class="menu-linkRow">' . 'Downloads' . '</a>
					';
	}
	$__compilerTemp11 = '';
	if ($__templater->method($__vars['product'], 'hasLicenseFunctionality', array())) {
		$__compilerTemp11 .= '
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses', null, array('criteria' => array('product_id' => $__vars['product']['product_id'], ), ), ), true) . '" class="menu-linkRow">' . 'Licenses' . '</a>
					';
	}
	$__compilerTemp12 = '';
	if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array())) {
		$__compilerTemp12 .= '
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/downloads', null, array('criteria' => array('product_id' => $__vars['product']['product_id'], ), ), ), true) . '" class="menu-linkRow">' . 'Download log' . '</a>
					';
	}
	$__compilerTemp1[] = array(
		'class' => 'dataList-cell--action u-hideMedium',
		'label' => 'View' . $__vars['xf']['language']['ellipsis'],
		'_type' => 'popup',
		'html' => '

			<div class="menu" data-menu="menu" aria-hidden="true">
				<div class="menu-content">
					<h3 class="menu-header">' . 'View' . $__vars['xf']['language']['ellipsis'] . '</h3>
					' . $__compilerTemp10 . '

					' . $__compilerTemp11 . '

					' . $__compilerTemp12 . '

					<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/purchases', null, array('criteria' => array('product_id' => $__vars['product']['product_id'], ), ), ), true) . '" class="menu-linkRow">' . 'Purchase log' . '</a>
				</div>
			</div>
		',
	);
	$__compilerTemp1[] = array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/products/delete', $__vars['product'], ), false),
		'_type' => 'delete',
		'html' => '',
	);
	$__finalCompiled .= $__templater->dataRow(array(
		'rowclass' => (($__vars['product']['product_state'] == 'deleted') ? 'dataList-row--deleted' : ''),
	), $__compilerTemp1) . '

	' . $__templater->callMacro(null, 'product_list', array(
		'children' => $__vars['children'],
		'depth' => ($__vars['depth'] + 1),
		'customPermissions' => $__vars['customPermissions'],
	), $__vars) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Products');
	$__finalCompiled .= '

';
	$__templater->includeCss('public:dbtech_ecommerce_product_list_labels.less');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp2 = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductTypeHandlers', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['productType'] => $__vars['handler']) {
			$__compilerTemp1 .= '
				<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/add', null, array('product_type' => $__vars['productType'], ), ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . $__templater->escape($__templater->method($__vars['handler'], 'getProductTypePhrase', array())) . '</a>
			';
		}
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add product' . $__vars['xf']['language']['ellipsis'], array(
		'class' => 'menuTrigger',
		'data-xf-click' => 'menu',
		'aria-expanded' => 'false',
		'aria-haspopup' => 'true',
	), '', array(
	)) . '
	<div class="menu" data-menu="menu" aria-hidden="true">
		<div class="menu-content">
			<h3 class="menu-header">' . 'Add product' . $__vars['xf']['language']['ellipsis'] . '</h3>

			' . $__compilerTemp1 . '
		</div>
	</div>
');
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['tree'], 'countChildren', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">
			' . $__templater->callMacro(null, 'filter_macros::quick_filter', array(
			'key' => 'dbtech-ecommerce-products',
			'class' => 'block-outer-opposite',
		), $__vars) . '
		</div>
		<div class="block-container">
			<div class="block-body">
				' . $__templater->dataList('
					' . $__templater->callMacro(null, 'product_list', array(
			'children' => $__vars['tree'],
			'customPermissions' => $__vars['customPermissions'],
		), $__vars) . '
				', array(
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['perPage'], $__vars['total'], ), true) . ' ' . $__vars['xf']['language']['parenthesis_open'] . 'Add-on products are listed, but not included in the totals' . $__vars['xf']['language']['parenthesis_close'] . '</span>
			</div>
		</div>

		' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'dbtech-ecommerce/products',
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No items have been created yet.' . '</div>
';
	}
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);