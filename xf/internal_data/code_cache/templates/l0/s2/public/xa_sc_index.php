<?php
// FROM HASH: f51db7a3e862f837bba1d0669919d045
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Items');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['xf']['options']['xaScMetaDescription'],
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase', null, array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), ));
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'canAddShowcaseItem', array())) {
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
	if (($__vars['xf']['options']['xaScFeaturedItemsDisplayType'] == 'featured_grid') AND (($__vars['featuredItemsCount'] > 1) AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', )))) {
		$__finalCompiled .= '
	' . $__templater->callMacro('xa_sc_featured_macros', 'featured_grid', array(
			'featuredItems' => $__vars['featuredItems'],
			'featuredItemsCount' => $__vars['featuredItemsCount'],
			'viewAllLink' => $__templater->func('link', array('showcase/featured', ), false),
		), $__vars) . '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->callMacro('xa_sc_featured_macros', 'featured_carousel', array(
			'featuredItems' => $__vars['featuredItems'],
			'viewAllLink' => $__templater->func('link', array('showcase/featured', ), false),
		), $__vars) . '
';
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

';
	if (!$__templater->test($__vars['mapItems'], 'empty', array()) AND (($__vars['xf']['options']['xaScIndexMapOptions']['map_display_location'] == 'above_listing') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewMultiMarkerMaps', )))) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				<div class="block-row">			
					' . $__templater->callMacro('xa_sc_map_macros', 'items_map', array(
			'mapItems' => $__vars['mapItems'],
			'mapId' => 'sc-index',
			'containerHeight' => ($__vars['xf']['options']['xaScIndexMapOptions']['container_height'] ?: 400),
		), $__vars) . '
				</div>
			</div>
			';
		if ($__vars['xf']['options']['xaScIndexFullPageMapOptions']['enable_full_page_map']) {
			$__finalCompiled .= '
				<div class="block-footer">	
					<div style="text-align: center;"><a href="' . $__templater->func('link', array('showcase/full-map', ), true) . '">' . 'View full map' . '</a></div>
				</div>
			';
		}
		$__finalCompiled .= '
		</div>
	</div>					
';
	}
	$__finalCompiled .= '

' . $__templater->widgetPosition('xa_sc_index_above_items', array()) . '

<div class="block ' . (($__vars['xf']['options']['xaScItemListLayoutType'] == 'item_view') ? 'block--messages' : '') . '" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="sc_item" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
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
		'link' => 'showcase',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp1 . '

	'), false) . '</div>

	<div class="block-container">
		' . $__templater->callMacro('xa_sc_index_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'showcase',
		'creatorFilter' => $__vars['creatorFilter'],
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
						'filterPrefix' => true,
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
						'filterPrefix' => true,
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
				<div class="block-row">' . 'There are no items matching your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No items have been created yet.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>

' . $__templater->widgetPosition('xa_sc_index_below_items', array()) . '

';
	if (!$__templater->test($__vars['mapItems'], 'empty', array()) AND (($__vars['xf']['options']['xaScIndexMapOptions']['map_display_location'] == 'below_listing') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewMultiMarkerMaps', )))) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				<div class="block-row">			
					' . $__templater->callMacro('xa_sc_map_macros', 'items_map', array(
			'mapItems' => $__vars['mapItems'],
			'mapId' => 'sc-index',
			'containerHeight' => ($__vars['xf']['options']['xaScIndexMapOptions']['container_height'] ?: 400),
		), $__vars) . '
				</div>
			</div>
			';
		if ($__vars['xf']['options']['xaScIndexFullPageMapOptions']['enable_full_page_map']) {
			$__finalCompiled .= '
				<div class="block-footer">	
					<div style="text-align: center;"><a href="' . $__templater->func('link', array('showcase/full-map', ), true) . '">' . 'View full map' . '</a></div>
				</div>
			';
		}
		$__finalCompiled .= '
		</div>
	</div>					
';
	}
	$__finalCompiled .= '

';
	$__templater->setPageParam('sideNavTitle', 'Categories');
	$__finalCompiled .= '
';
	$__compilerTemp3 = '';
	if ($__vars['xf']['options']['xaScIndexFullPageMapOptions']['enable_full_page_map'] AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewMultiMarkerMaps', ))) {
		$__compilerTemp3 .= '
		<div class="block">
			<div class="block-container">
				' . $__templater->button('View full map', array(
			'href' => $__templater->func('link', array('showcase/full-map', ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
			</div>
		</div>
	';
	}
	$__templater->modifySideNavHtml(null, '
	' . $__compilerTemp3 . '
	
	' . $__templater->callMacro('xa_sc_category_list_macros', 'simple_list_block', array(
		'categoryTree' => $__vars['categoryTree'],
		'categoryExtras' => $__vars['categoryExtras'],
		'selected' => '',
	), $__vars) . '
', 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySideNavHtml('_xfWidgetPositionSideNavb7abe9c55a67ff1c1f3d42a8a371ec25', $__templater->widgetPosition('xa_sc_index_sidenav', array()), 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar03e792261cf03a505ad9217a44a841c2', $__templater->widgetPosition('xa_sc_index_sidebar', array()), 'replace');
	return $__finalCompiled;
}
);