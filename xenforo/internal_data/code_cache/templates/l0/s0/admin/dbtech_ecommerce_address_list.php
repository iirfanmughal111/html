<?php
// FROM HASH: 7a9e56d4cb0f017ee2a1ec4e3c35a672
return array(
'macros' => array('address_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'addresses' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['addresses'])) {
		foreach ($__vars['addresses'] AS $__vars['address']) {
			$__finalCompiled .= '
		';
			$__compilerTemp1 = '';
			if ($__vars['xf']['options']['dbtechEcommerceSalesTax']['enabled'] AND ($__vars['xf']['options']['dbtechEcommerceSalesTax']['enableVat'] AND $__vars['address']['sales_tax_id'])) {
				$__compilerTemp1 .= '
						' . $__templater->escape($__vars['address']['sales_tax_id']) . ' -
					';
			}
			$__compilerTemp2 = '';
			if (($__vars['address']['address_state'] == 'visible') OR ($__vars['address']['address_state'] == 'verified')) {
				$__compilerTemp2 .= '
						<ul class="listInline listInline--bullet">
							<li>' . ($__vars['address']['User'] ? $__templater->escape($__vars['address']['User']['username']) : 'Unknown user') . '</li>
							<li>' . $__templater->escape($__vars['address']['Country']['name']) . '</li>
						</ul>
					';
			} else if ($__vars['address']['address_state'] == 'deleted') {
				$__compilerTemp2 .= '
						' . $__templater->callMacro('public:deletion_macros', 'notice', array(
					'log' => $__vars['address']['DeletionLog'],
				), $__vars) . '
					';
			}
			$__finalCompiled .= $__templater->dataRow(array(
				'rowclass' => (($__vars['address']['address_state'] == 'deleted') ? 'dataList-row--deleted' : ''),
			), array(array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/addresses/edit', $__vars['address'], ), false),
				'hash' => $__vars['address']['address_id'],
				'dir' => 'auto',
				'label' => $__templater->escape($__vars['address']['title']),
				'hint' => '
					' . $__compilerTemp1 . '
					' . $__templater->escape($__vars['address']['business_title']) . '
				',
				'explain' => '
					' . $__compilerTemp2 . '
				',
				'_type' => 'main',
				'html' => '',
			),
			array(
				'class' => 'dataList-cell--action',
				'label' => 'View' . $__vars['xf']['language']['ellipsis'],
				'_type' => 'popup',
				'html' => '

				<div class="menu" data-menu="menu" aria-hidden="true">
					<div class="menu-content">
						<h3 class="menu-header">' . 'View' . $__vars['xf']['language']['ellipsis'] . '</h3>
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/purchases', null, array('criteria' => array('address_id' => $__vars['address']['address_id'], ), ), ), true) . '" class="menu-linkRow">' . 'Purchase log' . '</a>
					</div>
				</div>
			',
			),
			array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/addresses/delete', $__vars['address'], ), false),
				'tooltip' => 'Delete' . ' ',
				'_type' => 'delete',
				'html' => '',
			))) . '
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'search_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'conditions' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block-filterBar">
		<div class="filterBar">
			<a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Refine search' . '</a>
			<div class="menu menu--wide" data-menu="menu" aria-hidden="true"
				data-href="' . $__templater->func('link', array('dbtech-ecommerce/addresses/refine-search', null, $__vars['conditions'], ), true) . '"
				data-load-target=".js-filterMenuBody">
				<div class="menu-content">
					<h4 class="menu-header">' . 'Refine search' . '</h4>
					<div class="js-filterMenuBody">
						<div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
					</div>
				</div>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Addresses');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('
		' . 'Add address' . '
	', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/addresses/add', ), false),
		'class' => 'button--cta',
		'icon' => 'add',
		'data-xf-click' => 'prefix-grabber',
		'data-filter-element' => '[data-xf-init~=filter]',
	), '', array(
	)) . '
	' . $__templater->button('Search addresses', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/addresses/search', ), false),
		'icon' => 'search',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

<div class="block">
	<div class="block-outer">
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => 'dbtech-ecommerce/addresses',
		'ajax' => $__templater->func('link', array('dbtech-ecommerce/addresses', null, array('criteria' => $__vars['criteria'], ), ), false),
		'class' => 'block-outer-opposite',
	), $__vars) . '
	</div>
	<div class="block-container">

		<div class="block-body">
			';
	$__compilerTemp1 = '';
	if ($__vars['filter'] AND ($__vars['total'] > $__vars['perPage'])) {
		$__compilerTemp1 .= '
					' . $__templater->dataRow(array(
			'rowclass' => 'dataList-row--note dataList-row--noHover js-filterForceShow',
		), array(array(
			'colspan' => '2',
			'_type' => 'cell',
			'html' => 'There are more records matching your filter. Please be more specific.',
		))) . '
				';
	}
	$__finalCompiled .= $__templater->dataList('
				' . $__templater->callMacro(null, 'address_list', array(
		'addresses' => $__vars['addresses'],
	), $__vars) . '
				' . $__compilerTemp1 . '
			', array(
	)) . '
		</div>

		<div class="block-footer">
			<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['addresses'], $__vars['total'], ), true) . '</span>
		</div>
	</div>

	' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/addresses',
		'params' => array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ),
		'wrapperclass' => 'js-filterHide block-outer block-outer--after',
		'perPage' => $__vars['perPage'],
	))) . '
</div>

' . '

';
	return $__finalCompiled;
}
);