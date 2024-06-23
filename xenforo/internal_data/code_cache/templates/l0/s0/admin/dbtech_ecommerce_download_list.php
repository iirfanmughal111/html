<?php
// FROM HASH: 92d7ac24a355c368ecada866d0aeba53
return array(
'macros' => array('download_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'downloads' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['downloads'])) {
		foreach ($__vars['downloads'] AS $__vars['download']) {
			$__finalCompiled .= '
		';
			$__compilerTemp1 = '';
			if ($__vars['download']['download_state'] == 'visible') {
				$__compilerTemp1 .= '
						<ul class="listInline listInline--bullet">
							<li>' . '' . $__templater->filter($__vars['download']['download_count'], array(array('number', array()),), true) . ' unique downloads' . '</li>
							<li>' . '' . $__templater->filter($__vars['download']['full_download_count'], array(array('number', array()),), true) . ' total downloads' . '</li>
						</ul>
					';
			} else if ($__vars['download']['download_state'] == 'deleted') {
				$__compilerTemp1 .= '
						' . $__templater->callMacro('public:deletion_macros', 'notice', array(
					'log' => $__vars['download']['DeletionLog'],
				), $__vars) . '
					';
			}
			$__finalCompiled .= $__templater->dataRow(array(
				'rowclass' => (($__vars['download']['download_state'] == 'deleted') ? 'dataList-row--deleted' : '') . ' ' . (($__vars['download']['download_state'] == 'scheduled') ? 'dataList-row--highlighted' : ''),
			), array(array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/downloads/edit', $__vars['download'], ), false),
				'label' => $__templater->escape($__vars['download']['title']),
				'hint' => $__templater->func('date_time', array($__vars['download']['release_date'], ), true),
				'hash' => $__vars['download']['download_id'],
				'dir' => 'auto',
				'explain' => '
					' . $__compilerTemp1 . '				
				',
				'_type' => 'main',
				'html' => '',
			),
			array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/downloads/add', null, array('source_download_id' => $__vars['download']['download_id'], ), ), false),
				'class' => 'u-hideMedium',
				'_type' => 'action',
				'html' => 'Copy',
			),
			array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/downloads/delete', $__vars['download'], ), false),
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
				data-href="' . $__templater->func('link', array('dbtech-ecommerce/downloads/refine-search', null, $__vars['conditions'], ), true) . '"
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Downloads');
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('
		' . 'Add download' . '
	', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/downloads/add', ), false),
		'class' => 'button--cta',
		'overlay' => 'true',
		'icon' => 'add',
		'data-cache' => 'false',
		'data-xf-click' => 'prefix-grabber',
		'data-filter-element' => '[data-xf-init~=filter]',
	), '', array(
	)) . '
	' . $__templater->button('Search downloads', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/downloads/search', ), false),
		'icon' => 'search',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

<div class="block">
	<div class="block-outer">
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => 'dbtech-ecommerce/downloads',
		'ajax' => $__templater->func('link', array('dbtech-ecommerce/downloads', null, array('criteria' => $__vars['criteria'], 'order' => $__vars['order'], 'direction' => $__vars['direction'], ), ), false),
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
				' . $__templater->callMacro(null, 'download_list', array(
		'downloads' => $__vars['downloads'],
	), $__vars) . '
				' . $__compilerTemp1 . '
			', array(
	)) . '
		</div>

		<div class="block-footer">
			<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['downloads'], $__vars['total'], ), true) . '</span>
		</div>
	</div>

	' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'dbtech-ecommerce/downloads',
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