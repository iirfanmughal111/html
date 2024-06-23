<?php
// FROM HASH: 636e334964aa1c732b413a5c79007b0c
return array(
'macros' => array('product_change_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'products' => '!',
		'route' => '!',
		'routeData' => '!',
		'routeParams' => array(),
		'currentProduct' => null,
		'linkClass' => 'button button--link',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<a class="' . $__templater->escape($__vars['linkClass']) . ' menuTrigger"
		data-xf-click="menu"
		role="button"
		tabindex="0"
		aria-expanded="false"
		aria-haspopup="true">' . 'Product' . $__vars['xf']['language']['label_separator'] . ' ' . ($__vars['currentProduct'] ? $__templater->escape($__vars['currentProduct']['full_title']) : $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close']) . '</a>

	<div class="menu" data-menu="menu" aria-hidden="true">
		<div class="menu-content">
			<h3 class="menu-header">' . 'Product' . '</h3>
			<a href="' . $__templater->func('link', array($__vars['route'], $__vars['routeData'], array('product_id' => null, ) + $__vars['routeParams'], ), true) . '"
				class="menu-linkRow ' . ((!$__vars['currentProduct']) ? 'is-selected' : '') . '">
				' . $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'] . '
			</a>
			';
	if ($__templater->isTraversable($__vars['products'])) {
		foreach ($__vars['products'] AS $__vars['treeEntry']) {
			$__finalCompiled .= '
				<a href="' . $__templater->func('link', array($__vars['route'], $__vars['routeData'], array('product_id' => $__vars['treeEntry']['record']['product_id'], ) + $__vars['routeParams'], ), true) . '"
					class="menu-linkRow ' . (($__vars['currentProduct'] AND ($__vars['currentProduct']['product_id'] == $__vars['treeEntry']['record']['product_id'])) ? 'is-selected' : '') . '">
					' . $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['full_title']) . '
				</a>
			';
		}
	}
	$__finalCompiled .= '
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