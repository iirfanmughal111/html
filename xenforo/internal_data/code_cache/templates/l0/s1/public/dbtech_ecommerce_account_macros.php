<?php
// FROM HASH: 7c0452f18e997aad7917dd67090fa700
return array(
'macros' => array('list_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'baseLinkPath' => '!',
		'addressFilter' => null,
		'stateFilter' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['sortOrders'] = array('order_date' => 'Order date', 'order_state' => 'Order state', 'cost_amount' => 'Order total', );
	$__finalCompiled .= '

	<div class="block-filterBar">
		<div class="filterBar">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['address'] AND $__vars['addressFilter']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array('address', null, )),), false), ), true) . '"
								   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Address' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['addressFilter']['title']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['state'] AND $__vars['stateFilter']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array('state', null, )),), false), ), true) . '"
								   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Order state' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['stateFilter']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
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
				 data-href="' . $__templater->func('link', array($__vars['baseLinkPath'] . '/filters', null, $__vars['filters'], ), true) . '"
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