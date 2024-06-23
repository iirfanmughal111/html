<?php
// FROM HASH: b1e676cab1d71fb5762e2c57ce963841
return array(
'macros' => array('sale_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'sales' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['sales'])) {
		foreach ($__vars['sales'] AS $__vars['sale']) {
			$__finalCompiled .= '
		';
			$__compilerTemp1 = '';
			if ($__vars['sale']['sale_state'] == 'visible') {
				$__compilerTemp1 .= '
						' . 'Starts ' . $__templater->func('date_time', array($__vars['sale']['start_date'], ), true) . ' and ends ' . $__templater->func('date_time', array($__vars['sale']['end_date'], ), true) . '' . '
					';
			} else if ($__vars['sale']['sale_state'] == 'deleted') {
				$__compilerTemp1 .= '
						' . $__templater->callMacro('public:deletion_macros', 'notice', array(
					'log' => $__vars['sale']['DeletionLog'],
				), $__vars) . '
					';
			}
			$__compilerTemp2 = array(array(
				'href' => ($__vars['xf']['options']['dbtechEcommerceSales']['enabled'] ? $__templater->func('link', array('dbtech-ecommerce/sales/edit', $__vars['sale'], ), false) : ''),
				'label' => $__templater->escape($__vars['sale']['title']),
				'hash' => $__vars['sale']['sale_id'],
				'dir' => 'auto',
				'explain' => '
					' . $__compilerTemp1 . '				
				',
				'_type' => 'main',
				'html' => '',
			));
			if ($__vars['xf']['options']['dbtechEcommerceSales']['enabled']) {
				$__compilerTemp2[] = array(
					'href' => $__templater->func('link', array('dbtech-ecommerce/sales/add', null, array('source_sale_id' => $__vars['sale']['sale_id'], ), ), false),
					'_type' => 'action',
					'html' => '
					' . 'Copy' . '
				',
				);
			}
			$__compilerTemp2[] = array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/sales/delete', $__vars['sale'], ), false),
				'tooltip' => 'Delete' . ' ',
				'_type' => 'delete',
				'html' => '',
			);
			$__finalCompiled .= $__templater->dataRow(array(
				'rowclass' => (($__vars['sale']['sale_state'] == 'deleted') ? 'dataList-row--deleted' : ''),
			), $__compilerTemp2) . '
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
				data-href="' . $__templater->func('link', array('dbtech-ecommerce/sales/refine-search', null, $__vars['conditions'], ), true) . '"
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Sales');
	$__finalCompiled .= '

';
	if ($__vars['xf']['options']['dbtechEcommerceSales']['enabled']) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('
		' . 'Add sale' . '
	', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/sales/add', ), false),
			'icon' => 'add',
			'data-xf-click' => 'prefix-grabber',
			'data-filter-element' => '[data-xf-init~=filter]',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	if (!$__vars['xf']['options']['dbtechEcommerceSales']['enabled']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
			' . 'Sales are currently globally disabled. You cannot add or edit sales, and any current sales will not apply.' . '
		</div>
	</div>
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('option_macros', 'option_form_block', array(
		'options' => $__vars['options'],
	), $__vars) . '

<div class="block">
	<div class="block-outer">
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => 'dbtech-ecommerce/sales',
		'ajax' => $__templater->func('link', array('dbtech-ecommerce/sales', ), false),
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
				' . $__templater->callMacro(null, 'sale_list', array(
		'sales' => $__vars['sales'],
	), $__vars) . '
				' . $__compilerTemp1 . '
			', array(
	)) . '
		</div>

		<div class="block-footer">
			<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['sales'], $__vars['total'], ), true) . '</span>
		</div>
	</div>

	' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/sales',
		'wrapperclass' => 'js-filterHide block-outer block-outer--after',
		'perPage' => $__vars['perPage'],
	))) . '
</div>

' . '

';
	return $__finalCompiled;
}
);