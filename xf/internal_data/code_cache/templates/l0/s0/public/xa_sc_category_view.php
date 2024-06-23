<?php
// FROM HASH: 80c3fe4655545b5e95f1b3da7b25c44e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__vars['category']['meta_title'] ? $__templater->escape($__vars['category']['meta_title']) : $__templater->escape($__vars['category']['title'])));
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->escape($__vars['category']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped($__templater->filter($__vars['category']['description'], array(array('raw', array()),), true));
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	if ($__vars['category']['meta_description']) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['category']['meta_description'], 320, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['category']['description'], 256, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

';
	if (!$__templater->method($__vars['category'], 'isSearchEngineIndexable', array())) {
		$__finalCompiled .= '
	';
		$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'title' => ($__vars['category']['og_title'] ? $__vars['category']['og_title'] : ($__vars['category']['meta_title'] ? $__vars['category']['meta_title'] : $__vars['category']['title'])),
		'description' => $__vars['descSnippet'],
		'shareUrl' => $__templater->func('link', array('canonical:showcase/categories', $__vars['category'], ), false),
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/categories', $__vars['category'], array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
		'imageUrl' => ($__vars['category']['content_image_url'] ? $__templater->func('base_url', array($__vars['category']['content_image_url'], true, ), false) : ''),
		'twitterCard' => 'summary_large_image',
	), $__vars) . '

' . $__templater->callMacro('xa_sc_item_page_macros', 'item_page_options', array(
		'category' => $__vars['category'],
	), $__vars) . '
';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['category'], 'canAddItem', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button(($__vars['category']['content_term'] ? 'Add ' . $__templater->escape($__vars['category']['content_term']) . '' : 'Add item'), array(
			'href' => $__templater->func('link', array('showcase/categories/add', $__vars['category'], ), false),
			'class' => 'button--cta',
			'icon' => 'add',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	if ($__vars['pendingApproval']) {
		$__finalCompiled .= '
	<div class="blockMessage blockMessage--important">' . 'Your content has been submitted and will be displayed pending approval by a moderator.' . '</div>
';
	}
	$__finalCompiled .= '

';
	if (($__vars['xf']['options']['xaScFeaturedItemsDisplayType'] == 'featured_grid') AND (($__vars['featuredItemsCount'] > 1) AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemCategoryPermission', array($__vars['category']['category_id'], 'viewItemAttach', )))) {
		$__finalCompiled .= '
	' . $__templater->callMacro('xa_sc_featured_macros', 'featured_grid', array(
			'featuredItems' => $__vars['featuredItems'],
			'featuredItemsCount' => $__vars['featuredItemsCount'],
			'category' => $__vars['category'],
			'viewAllLink' => $__templater->func('link', array('showcase/categories/featured', $__vars['category'], ), false),
		), $__vars) . '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->callMacro('xa_sc_featured_macros', 'featured_carousel', array(
			'featuredItems' => $__vars['featuredItems'],
			'category' => $__vars['category'],
			'viewAllLink' => $__templater->func('link', array('showcase/categories/featured', $__vars['category'], ), false),
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
	if (($__vars['category']['content_message'] != '') AND ($__vars['page'] < 2)) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			';
		if ($__vars['category']['content_title']) {
			$__finalCompiled .= '
				<h3 class="block-header">' . $__templater->escape($__vars['category']['content_title']) . '</h3>
			';
		}
		$__finalCompiled .= '
			<div class="block-body">
				<div class="block-row">
					' . $__templater->filter($__vars['category']['content_message'], array(array('raw', array()),), true) . '
				</div>
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['mapItems'], 'empty', array()) AND (($__vars['category']['map_options']['map_display_location'] == 'above_listing') AND $__templater->method($__vars['category'], 'canViewCategoryMap', array()))) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				<div class="block-row">			
					' . $__templater->callMacro('xa_sc_map_macros', 'items_map', array(
			'mapItems' => $__vars['mapItems'],
			'mapId' => 'sc-cat-' . $__vars['category']['category_id'],
			'containerHeight' => ($__vars['category']['map_options']['container_height'] ?: 400),
		), $__vars) . '
				</div>
			</div>
			<div class="block-footer">	
				<div style="text-align: center;">
					';
		if ($__vars['category']['map_options']['enable_full_page_map']) {
			$__finalCompiled .= '	
						<a href="' . $__templater->func('link', array('showcase/categories/map', $__vars['category'], ), true) . '">' . 'View full map' . '</a> |
					';
		}
		$__finalCompiled .= '

					<a href="' . $__templater->func('link', array('showcase/categories/map-marker-legend', $__vars['category'], ), true) . '" data-xf-click="overlay">' . 'View legend' . '</a>
				</div>
			</div>
		</div>
	</div>					
';
	}
	$__finalCompiled .= '

' . $__templater->widgetPosition('xa_sc_category_above_items', array(
		'category' => $__vars['category'],
	)) . '

<div class="block ' . (($__vars['layoutType'] == 'item_view') ? 'block--messages' : '') . '" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="sc_item" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
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
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp2 .= '
							' . $__templater->button('
								' . 'Mark read' . '
							', array(
			'href' => $__templater->func('link', array('showcase/categories/mark-read', $__vars['category'], array('date' => $__vars['xf']['time'], ), ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
						';
	}
	$__compilerTemp2 .= '
						';
	if ($__templater->method($__vars['category'], 'canWatch', array())) {
		$__compilerTemp2 .= '
							';
		$__compilerTemp3 = '';
		if ($__vars['category']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp3 .= 'Unwatch';
		} else {
			$__compilerTemp3 .= 'Watch';
		}
		$__compilerTemp2 .= $__templater->button('
								' . $__compilerTemp3 . '
							', array(
			'href' => $__templater->func('link', array('showcase/categories/watch', $__vars['category'], ), false),
			'class' => 'button--link',
			'data-xf-click' => 'switch-overlay',
			'data-sk-watch' => 'Watch',
			'data-sk-unwatch' => 'Unwatch',
		), '', array(
		)) . '
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
		'link' => 'showcase/categories',
		'data' => $__vars['category'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp1 . '

	'), false) . '</div>

	<div class="block-container">
		' . $__templater->callMacro('xa_sc_index_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'showcase/categories',
		'category' => $__vars['category'],
		'creatorFilter' => $__vars['creatorFilter'],
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['stickyItems'], 'empty', array()) OR !$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
				';
		if (($__vars['layoutType'] == 'item_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemCategoryPermission', array($__vars['category']['category_id'], 'viewItemAttach', ))) {
			$__finalCompiled .= '
					';
			if (!$__templater->test($__vars['stickyItems'], 'empty', array())) {
				$__finalCompiled .= '
						<div class="structItemContainer-group structItemContainer-group--sticky">
							';
				if ($__templater->isTraversable($__vars['stickyItems'])) {
					foreach ($__vars['stickyItems'] AS $__vars['stickyItem']) {
						$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'item_view_layout', array(
							'item' => $__vars['stickyItem'],
							'category' => $__vars['category'],
							'showSticky' => true,
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
			if (!$__templater->test($__vars['items'], 'empty', array())) {
				$__finalCompiled .= '
						';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
							' . $__templater->callMacro('xa_sc_item_list_macros', 'item_view_layout', array(
							'item' => $__vars['item'],
							'category' => $__vars['category'],
						), $__vars) . '
						';
					}
				}
				$__finalCompiled .= '
					';
			}
			$__finalCompiled .= '
				';
		} else if (($__vars['layoutType'] == 'grid_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemCategoryPermission', array($__vars['category']['category_id'], 'viewItemAttach', ))) {
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc_grid_view_layout.less');
			$__finalCompiled .= '

					';
			if (!$__templater->test($__vars['stickyItems'], 'empty', array())) {
				$__finalCompiled .= '
						<div class="structItemContainer-group structItemContainer-group--sticky">
							';
				if ($__templater->isTraversable($__vars['stickyItems'])) {
					foreach ($__vars['stickyItems'] AS $__vars['stickyItem']) {
						$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
							'item' => $__vars['stickyItem'],
							'category' => $__vars['category'],
							'showSticky' => true,
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
			if (!$__templater->test($__vars['items'], 'empty', array())) {
				$__finalCompiled .= '
						<div class="gridContainerScGridView">		
							<ul class="sc-grid-view">
								';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
									' . $__templater->callMacro('xa_sc_item_list_macros', 'grid_view_layout', array(
							'item' => $__vars['item'],
							'category' => $__vars['category'],
						), $__vars) . '
								';
					}
				}
				$__finalCompiled .= '
							</ul>
						</div>
					';
			}
			$__finalCompiled .= '
				';
		} else if (($__vars['layoutType'] == 'tile_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemCategoryPermission', array($__vars['category']['category_id'], 'viewItemAttach', ))) {
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc_tile_view_layout.less');
			$__finalCompiled .= '

					';
			if (!$__templater->test($__vars['stickyItems'], 'empty', array())) {
				$__finalCompiled .= '
						<div class="structItemContainer-group structItemContainer-group--sticky">
							';
				if ($__templater->isTraversable($__vars['stickyItems'])) {
					foreach ($__vars['stickyItems'] AS $__vars['stickyItem']) {
						$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
							'item' => $__vars['stickyItem'],
							'category' => $__vars['category'],
							'showSticky' => true,
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
			if (!$__templater->test($__vars['items'], 'empty', array())) {
				$__finalCompiled .= '
						<div class="gridContainerScTileView">		
							<ul class="sc-tile-view">
								';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
									' . $__templater->callMacro('xa_sc_item_list_macros', 'tile_view_layout', array(
							'item' => $__vars['item'],
							'category' => $__vars['category'],
						), $__vars) . '
								';
					}
				}
				$__finalCompiled .= '
							</ul>
						</div>
					';
			}
			$__finalCompiled .= '						
				';
		} else {
			$__finalCompiled .= '
					<div class="structItemContainer structItemContainerScListView">
						';
			if (!$__templater->test($__vars['stickyItems'], 'empty', array())) {
				$__finalCompiled .= '
							<div class="structItemContainer-group structItemContainer-group--sticky">
								';
				if ($__templater->isTraversable($__vars['stickyItems'])) {
					foreach ($__vars['stickyItems'] AS $__vars['stickyItem']) {
						$__finalCompiled .= '
									' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
							'item' => $__vars['stickyItem'],
							'category' => $__vars['category'],
							'showSticky' => true,
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
			if (!$__templater->test($__vars['items'], 'empty', array())) {
				$__finalCompiled .= '
							<div class="structItemContainer-group js-itemList">
								';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
									' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
							'item' => $__vars['item'],
							'category' => $__vars['category'],
						), $__vars) . '
								';
					}
				}
				$__finalCompiled .= '
							</div>
						';
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
		'link' => 'showcase/categories',
		'data' => $__vars['category'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>

';
	if (!$__templater->test($__vars['mapItems'], 'empty', array()) AND (($__vars['category']['map_options']['map_display_location'] == 'below_listing') AND $__templater->method($__vars['category'], 'canViewCategoryMap', array()))) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				<div class="block-row">			
					' . $__templater->callMacro('xa_sc_map_macros', 'items_map', array(
			'mapItems' => $__vars['mapItems'],
			'mapId' => 'sc-cat-' . $__vars['category']['category_id'],
			'containerHeight' => ($__vars['category']['map_options']['container_height'] ?: 400),
		), $__vars) . '
				</div>
			</div>
			<div class="block-footer">	
				<div style="text-align: center;">
					';
		if ($__vars['category']['map_options']['enable_full_page_map']) {
			$__finalCompiled .= '	
						<a href="' . $__templater->func('link', array('showcase/categories/map', $__vars['category'], ), true) . '">' . 'View full map' . '</a> |
					';
		}
		$__finalCompiled .= '

					<a href="' . $__templater->func('link', array('showcase/categories/map-marker-legend', $__vars['category'], ), true) . '" data-xf-click="overlay">' . 'View legend' . '</a>
				</div>
			</div>
		</div>
	</div>					
';
	}
	$__finalCompiled .= '

' . $__templater->widgetPosition('xa_sc_category_below_items', array(
		'category' => $__vars['category'],
	)) . '

';
	$__templater->setPageParam('sideNavTitle', 'Categories');
	$__finalCompiled .= '
';
	$__compilerTemp4 = '';
	if ($__vars['category']['map_options']['enable_full_page_map'] AND $__templater->method($__vars['category'], 'canViewCategoryMap', array())) {
		$__compilerTemp4 .= '
		<div class="block">
			<div class="block-container">
				' . $__templater->button('View full map', array(
			'href' => $__templater->func('link', array('showcase/categories/map', $__vars['category'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
			</div>
		</div>
	';
	}
	$__templater->modifySideNavHtml(null, '
	' . $__compilerTemp4 . '
	
	' . $__templater->callMacro('xa_sc_category_list_macros', 'simple_list_block', array(
		'categoryTree' => $__vars['categoryTree'],
		'categoryExtras' => $__vars['categoryExtras'],
		'selected' => $__vars['category']['category_id'],
	), $__vars) . '
', 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySideNavHtml('_xfWidgetPositionSideNavf5ccb626f04b2b059de7fb01161bea97', $__templater->widgetPosition('xa_sc_category_sidenav', array(
		'category' => $__vars['category'],
	)), 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar0d26e3140017d80012771963920bff86', $__templater->widgetPosition('xa_sc_category_sidebar', array(
		'category' => $__vars['category'],
	)), 'replace');
	return $__finalCompiled;
}
);