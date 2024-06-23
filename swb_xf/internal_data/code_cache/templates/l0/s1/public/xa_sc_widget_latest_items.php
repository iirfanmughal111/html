<?php
// FROM HASH: 2c2e0a18a9d091f1ac233d0ace3e4a80
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['items'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . ' data-type="sc_item">
		';
		if (($__vars['style'] == 'grid') AND (($__vars['itemsCount'] > 1) AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', )))) {
			$__finalCompiled .= '
			';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
			<h3 class="block-header">
				<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest items') . '</a>
			</h3>
			' . $__templater->callMacro('xa_sc_widget_items_macros', 'items_grid', array(
				'items' => $__vars['items'],
				'itemsCount' => $__vars['itemsCount'],
				'viewAllLink' => $__vars['link'],
			), $__vars) . '
		';
		} else if ((($__vars['style'] == 'carousel') OR ($__vars['style'] == 'simple_carousel')) OR ((($__vars['style'] == 'grid') AND ((($__vars['itemsCount'] < 2) OR (!$__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', )))))))) {
			$__finalCompiled .= '
			';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
			<h3 class="block-header">
				<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest items') . '</a>
			</h3>				
			';
			if ($__vars['style'] == 'simple_carousel') {
				$__finalCompiled .= '
					' . $__templater->callMacro('xa_sc_widget_items_macros', 'items_carousel_simple', array(
					'items' => $__vars['items'],
					'viewAllLink' => $__vars['link'],
				), $__vars) . '
			';
			} else {
				$__finalCompiled .= '
				' . $__templater->callMacro('xa_sc_widget_items_macros', 'items_carousel', array(
					'items' => $__vars['items'],
					'viewAllLink' => $__vars['link'],
				), $__vars) . '
			';
			}
			$__finalCompiled .= '	
		';
		} else {
			$__finalCompiled .= '
			<div class="block-container">
				';
			if ($__vars['style'] == 'simple') {
				$__finalCompiled .= '
					<h3 class="block-minorHeader">
						<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest items') . '</a>
					</h3>
					<ul class="block-body">
						';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
							<li class="block-row">
								' . $__templater->callMacro('xa_sc_item_list_macros', 'item_simple', array(
							'item' => $__vars['item'],
						), $__vars) . '
							</li>
						';
					}
				}
				$__finalCompiled .= '
					</ul>
				';
			} else if (($__vars['style'] == 'item_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', ))) {
				$__finalCompiled .= '
					<h3 class="block-header">
						<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest items') . '</a>
					</h3>
					<div class="block-body">
						';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
							' . $__templater->callMacro('xa_sc_item_list_macros', 'item_view_layout', array(
							'allowInlineMod' => false,
							'item' => $__vars['item'],
						), $__vars) . '
						';
					}
				}
				$__finalCompiled .= '
					</div>
				';
			} else if (($__vars['style'] == 'grid_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', ))) {
				$__finalCompiled .= '
					';
				$__templater->includeCss('xa_sc.less');
				$__finalCompiled .= '
					';
				$__templater->includeCss('xa_sc_grid_view_layout.less');
				$__finalCompiled .= '
					<h3 class="block-header">
						<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest items') . '</a>
					</h3>
					<div class="block-body">
						<div class="gridContainerScGridView">		
							<ul class="sc-grid-view">
								';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
									' . $__templater->callMacro('xa_sc_item_list_macros', 'grid_view_layout', array(
							'allowInlineMod' => false,
							'item' => $__vars['item'],
						), $__vars) . '
								';
					}
				}
				$__finalCompiled .= '
							</ul>
						</div>
					</div>
				';
			} else if (($__vars['style'] == 'tile_view') AND $__templater->method($__vars['xf']['visitor'], 'hasShowcaseItemPermission', array('viewItemAttach', ))) {
				$__finalCompiled .= '
					';
				$__templater->includeCss('xa_sc.less');
				$__finalCompiled .= '
					';
				$__templater->includeCss('xa_sc_tile_view_layout.less');
				$__finalCompiled .= '

					<h3 class="block-header">
						<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest items') . '</a>
					</h3>
					<div class="block-body">
						<div class="gridContainerScTileView">		
							<ul class="sc-tile-view">
								';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
									' . $__templater->callMacro('xa_sc_item_list_macros', 'tile_view_layout', array(
							'allowInlineMod' => false,
							'item' => $__vars['item'],
						), $__vars) . '
								';
					}
				}
				$__finalCompiled .= '
							</ul>
						</div>
					</div>	
				';
			} else {
				$__finalCompiled .= '
					<h3 class="block-header">
						<a href="' . $__templater->escape($__vars['link']) . '" rel="nofollow">' . ($__templater->escape($__vars['title']) ?: 'Latest items') . '</a>
					</h3>
					<div class="block-body">
						<div class="structItemContainer structItemContainerScListView">
							';
				if ($__templater->isTraversable($__vars['items'])) {
					foreach ($__vars['items'] AS $__vars['item']) {
						$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
							'allowInlineMod' => false,
							'item' => $__vars['item'],
						), $__vars) . '
							';
					}
				}
				$__finalCompiled .= '
						</div>
					</div>
				';
			}
			$__finalCompiled .= '
			</div>
		';
		}
		$__finalCompiled .= '
	</div>
';
	}
	return $__finalCompiled;
}
);