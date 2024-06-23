<?php
// FROM HASH: 969789da5cd1ca9837ab55d7a1f0c742
return array(
'macros' => array('license_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'licenses' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['licenses'])) {
		foreach ($__vars['licenses'] AS $__vars['license']) {
			$__finalCompiled .= '
		';
			$__compilerTemp1 = '';
			if ($__vars['license']['license_state'] == 'visible') {
				$__compilerTemp1 .= '
						<ul class="listInline listInline--bullet">
							<li>' . $__templater->func('date_dynamic', array($__vars['license']['purchase_date'], array(
					'data-full-date' => 'true',
				))) . '</li>
							<li>' . ($__vars['license']['User'] ? $__templater->escape($__vars['license']['User']['username']) : 'Unknown user') . '</li>
						</ul>
					';
			} else if ($__vars['license']['license_state'] == 'deleted') {
				$__compilerTemp1 .= '
						' . $__templater->callMacro('public:deletion_macros', 'notice', array(
					'log' => $__vars['license']['DeletionLog'],
				), $__vars) . '
					';
			}
			$__finalCompiled .= $__templater->dataRow(array(
				'rowclass' => (($__vars['license']['license_state'] == 'deleted') ? 'dataList-row--deleted' : ''),
			), array(array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/edit', $__vars['license'], ), false),
				'hash' => $__vars['license']['license_id'],
				'dir' => 'auto',
				'label' => '
					' . $__templater->escape($__vars['license']['full_title']) . '
				',
				'hint' => '
					' . ($__templater->escape($__vars['license']['license_key']) ?: 'Unknown license') . '
				',
				'explain' => '
					' . $__compilerTemp1 . '
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
						' . '
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/reassign', $__vars['license'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Reassign' . '</a>
					</div>
				</div>
			',
			),
			array(
				'class' => 'dataList-cell--action',
				'label' => 'View' . $__vars['xf']['language']['ellipsis'],
				'_type' => 'popup',
				'html' => '

				<div class="menu" data-menu="menu" aria-hidden="true">
					<div class="menu-content">
						<h3 class="menu-header">' . 'View' . $__vars['xf']['language']['ellipsis'] . '</h3>
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/view', $__vars['license'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'License info' . '</a>
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/change-log', $__vars['license'], ), true) . '" class="menu-linkRow">' . 'Change log' . '</a>
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/downloads', null, array('criteria' => array('license_id' => $__vars['license']['license_id'], ), ), ), true) . '" class="menu-linkRow">' . 'Download log' . '</a>
						<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/purchases', null, array('criteria' => array('license_id' => $__vars['license']['license_id'], ), ), ), true) . '" class="menu-linkRow">' . 'Purchase log' . '</a>
					</div>
				</div>
			',
			),
			array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/delete', $__vars['license'], ), false),
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
				data-href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/refine-search', null, $__vars['conditions'], ), true) . '"
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Licenses');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('
		' . 'Add license' . '
	', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/add', ), false),
		'class' => 'button--cta',
		'icon' => 'add',
		'data-xf-click' => 'prefix-grabber overlay',
		'data-filter-element' => '[data-xf-init~=filter]',
	), '', array(
	)) . '
	' . $__templater->button('Search licenses', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/search', ), false),
		'icon' => 'search',
	), '', array(
	)) . '
	' . $__templater->button('Extend licenses', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/extend', ), false),
		'icon' => 'refresh',
		'overlay' => 'true',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

<div class="block">
	<div class="block-outer">
		<div class="block-outer-main">
			' . $__templater->callMacro('dbtech_ecommerce_product_macros', 'product_change_menu', array(
		'products' => $__vars['products'],
		'currentProduct' => $__vars['currentProduct'],
		'route' => 'dbtech-ecommerce/licenses',
		'routeData' => null,
		'routeParams' => array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ),
	), $__vars) . '
		</div>
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => 'dbtech-ecommerce/licenses',
		'ajax' => $__templater->func('link', array('dbtech-ecommerce/licenses', null, array('criteria' => $__vars['criteria'], ), ), false),
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
				' . $__templater->callMacro(null, 'license_list', array(
		'licenses' => $__vars['licenses'],
	), $__vars) . '
				' . $__compilerTemp1 . '
			', array(
	)) . '
		</div>

		<div class="block-footer">
			<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['licenses'], $__vars['total'], ), true) . '</span>
		</div>
	</div>

	' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/licenses',
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