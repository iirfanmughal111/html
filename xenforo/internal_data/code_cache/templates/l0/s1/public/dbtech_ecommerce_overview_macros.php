<?php
// FROM HASH: a31a05b7cb6e918b69f72731ab956e30
return array(
'macros' => array('list_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'baseLinkPath' => '!',
		'category' => null,
		'ownerFilter' => null,
		'platformFilter' => null,
		'productFieldFilter' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['sortOrders'] = array('last_update' => 'Last update', 'creation_date' => 'Creation date', 'rating_weighted' => 'Rating', 'download_count' => 'Downloads', 'title' => 'Title', 'random' => 'Random', );
	$__finalCompiled .= '

	<div class="block-filterBar">
		<div class="filterBar">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['type']) {
		$__compilerTemp1 .= '
							';
		if ($__vars['filters']['type'] == 'on_sale') {
			$__compilerTemp1 .= '
								<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('type', null, )),), false), ), true) . '"
									   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'On sale' . '</span></a></li>
							';
		} else {
			$__compilerTemp1 .= '
								<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('type', null, )),), false), ), true) . '"
									   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Price' . $__vars['xf']['language']['label_separator'] . '</span>
									';
			if ($__vars['filters']['type'] == 'free') {
				$__compilerTemp1 .= 'Free';
			} else {
				$__compilerTemp1 .= 'Paid';
			}
			$__compilerTemp1 .= '</a></li>
							';
		}
		$__compilerTemp1 .= '
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['prefix_id']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('prefix_id', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Prefix' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->func('prefix_title', array('dbtechEcommerceProduct', $__vars['filters']['prefix_id'], ), true) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['platform'] AND $__vars['platformFilter']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('platform', null, )),), false), ), true) . '"
								   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Platform' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['platformFilter']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['owner_id'] AND $__vars['ownerFilter']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('owner_id', null, )),), false), ), true) . '"
								   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Product owner' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['ownerFilter']['username']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['product_fields'] AND $__vars['productFieldFilter']) {
		$__compilerTemp1 .= '
							';
		if ($__templater->isTraversable($__vars['productFieldFilter'])) {
			foreach ($__vars['productFieldFilter'] AS $__vars['fieldId'] => $__vars['field']) {
				$__compilerTemp1 .= '
								<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('product_fields', $__templater->filter($__vars['filters']['product_fields'], array(array('replace', array($__vars['fieldId'], null, )),), false), )),), false), ), true) . '"
									   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . $__templater->escape($__vars['field']['title']) . ':</span>

									' . $__templater->callMacro('custom_fields_macros', 'custom_field_value', array(
					'definition' => $__vars['field']['definition'],
					'value' => $__vars['field']['value'],
				), $__vars) . '
								</a></li>
							';
			}
		}
		$__compilerTemp1 .= '
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['sortOrders'][$__vars['filters']['order']]) . '
								<i class="fa ' . (($__vars['filters']['direction'] == 'asc') ? 'fa-angle-up' : 'fa-angle-down') . '" aria-hidden="true"></i>
								<span class="u-srOnly">';
		if ($__vars['filters']['direction'] == 'asc') {
			$__compilerTemp1 .= 'Ascending';
		} else {
			$__compilerTemp1 .= 'Descending';
		}
		$__compilerTemp1 .= '</span>
							</a></li>
						';
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
				<ul class="filterBar-filters">
					' . $__compilerTemp1 . '
				</ul>
			';
	}
	$__finalCompiled .= '

			<a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
			<div class="menu menu--wide" data-menu="menu" aria-hidden="true"
				data-href="' . $__templater->func('link', array($__vars['baseLinkPath'] . '/filters', $__vars['category'], $__vars['filters'], ), true) . '"
				data-load-target=".js-filterMenuBody">
				<div class="menu-content">
					<h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
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

	return $__finalCompiled;
}
);