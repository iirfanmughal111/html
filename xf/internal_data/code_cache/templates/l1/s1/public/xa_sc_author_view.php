<?php
// FROM HASH: 155353bb486d70772105f325846abdd9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Your published items');
		$__templater->pageParams['pageNumber'] = $__vars['page'];
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '	
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Items by ' . $__templater->escape($__vars['user']['username']) . '');
		$__templater->pageParams['pageNumber'] = $__vars['page'];
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['xf']['options']['xaScEnableAuthorList']) {
		$__finalCompiled .= '
	';
		$__templater->breadcrumb($__templater->preEscaped('Author list'), $__templater->func('link', array('showcase/authors', ), false), array(
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/authors', $__vars['user'], array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), ));
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

';
	if (($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) AND $__templater->method($__vars['xf']['visitor'], 'canAddShowcaseItem', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Add item' . $__vars['xf']['language']['ellipsis'], array(
			'href' => $__templater->func('link', array('showcase/add', ), false),
			'class' => 'button--cta',
			'icon' => 'add',
			'overlay' => 'true',
		), '', array(
		)) . '
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

<div class="block ' . (((($__vars['xf']['options']['xaScItemListLayoutType'] == 'item_view') AND (!$__vars['fromProfile']))) ? 'block--messages' : '') . '" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="sc_item" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	<div class="block-outer">';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['canInlineMod']) {
		$__compilerTemp2 .= '
							' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
						';
	}
	$__compilerTemp2 .= '
					';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
			<div class="block-outer-opposite">
				<div class="buttonGroup">
					' . $__compilerTemp2 . '
				</div>
			</div>
		';
	}
	$__finalCompiled .= $__templater->func('trim', array('

		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/authors',
		'data' => $__vars['user'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp1 . '

	'), false) . '</div>

	<div class="block-container">
		' . $__templater->callMacro('xa_sc_author_item_list_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'showcase/authors',
		'linkData' => $__vars['user'],
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
				';
		if (($__vars['xf']['options']['xaScItemListLayoutType'] == 'item_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', ))) {
			$__finalCompiled .= '
					';
			if ($__templater->isTraversable($__vars['items'])) {
				foreach ($__vars['items'] AS $__vars['item']) {
					$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_item_list_macros', 'item_view_layout', array(
						'item' => $__vars['item'],
					), $__vars) . '
					';
				}
			}
			$__finalCompiled .= '
				';
		} else if (($__vars['xf']['options']['xaScItemListLayoutType'] == 'grid_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', ))) {
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc_grid_view_layout.less');
			$__finalCompiled .= '

					<div class="gridContainerScGridView">		
						<ul class="sc-grid-view">
							';
			if ($__templater->isTraversable($__vars['items'])) {
				foreach ($__vars['items'] AS $__vars['item']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'grid_view_layout', array(
						'filterPrefix' => true,
						'item' => $__vars['item'],
					), $__vars) . '
							';
				}
			}
			$__finalCompiled .= '
						</ul>
					</div>
				';
		} else if (($__vars['xf']['options']['xaScItemListLayoutType'] == 'tile_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', ))) {
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc_tile_view_layout.less');
			$__finalCompiled .= '

					<div class="gridContainerScTileView">		
						<ul class="sc-tile-view">
							';
			if ($__templater->isTraversable($__vars['items'])) {
				foreach ($__vars['items'] AS $__vars['item']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'tile_view_layout', array(
						'filterPrefix' => true,
						'item' => $__vars['item'],
					), $__vars) . '
							';
				}
			}
			$__finalCompiled .= '
						</ul>
					</div>			
				';
		} else {
			$__finalCompiled .= '
					<div class="structItemContainer structItemContainerScListView">
						';
			if ($__templater->isTraversable($__vars['items'])) {
				foreach ($__vars['items'] AS $__vars['item']) {
					$__finalCompiled .= '
							' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
						'item' => $__vars['item'],
					), $__vars) . '
						';
				}
			}
			$__finalCompiled .= '
					</div>
				';
		}
		$__finalCompiled .= '
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="blockMessage">
					';
		if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
			$__finalCompiled .= '
						' . 'You have not added any items which matches your filters.' . '
					';
		} else {
			$__finalCompiled .= '
						' . '' . $__templater->escape($__vars['user']['username']) . ' has not added any items which matches your filters.' . '
					';
		}
		$__finalCompiled .= '
				</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="blockMessage">
					';
		if ($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) {
			$__finalCompiled .= '
						' . 'You have not posted any items yet.' . '
					';
		} else {
			$__finalCompiled .= '
						' . '' . $__templater->escape($__vars['user']['username']) . ' has not posted any items yet.' . '
					';
		}
		$__finalCompiled .= '
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/authors',
		'data' => $__vars['user'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);