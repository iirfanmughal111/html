<?php
// FROM HASH: 91a29e30edc44b5e50b250f2b05b2016
return array(
'macros' => array('simple_category_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'selected' => '0',
		'pathToSelected' => array(),
		'children' => '!',
		'isActive' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<ol class="categoryList toggleTarget' . ($__vars['isActive'] ? ' is-active' : '') . '">

		';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['id'] => $__vars['child']) {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'simple_category_list_item', array(
				'selected' => $__vars['selected'],
				'pathToSelected' => $__vars['pathToSelected'],
				'category' => $__vars['child']['record'],
				'children' => $__vars['child'],
			), $__vars) . '
		
		';
		}
	}
	$__finalCompiled .= '
	</ol>
';
	return $__finalCompiled;
}
),
'simple_category_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'selected' => '!',
		'pathToSelected' => array(),
		'category' => '!',
		'children' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['isSelected'] = ($__vars['category']['category_id'] == $__vars['selected']);
	$__finalCompiled .= '
	';
	$__vars['hasPathToSelected'] = $__vars['pathToSelected'][$__vars['category']['category_id']];
	$__finalCompiled .= '
	';
	$__vars['isActive'] = ($__vars['isSelected'] OR ($__vars['hasPathToSelected'] AND !$__templater->test($__vars['children'], 'empty', array())));
	$__finalCompiled .= '

	<li class="categoryList-item">
		<div class="categoryList-itemRow">
			';
	if (!$__templater->test($__vars['children'], 'empty', array())) {
		$__finalCompiled .= '
				<a class="categoryList-toggler' . ($__vars['isActive'] ? ' is-active' : '') . '"
					data-xf-click="toggle" data-target="< :up :next"
					role="button" tabindex="0" aria-label="' . 'Toggle expanded' . '"
				></a>
			';
	} else {
		$__finalCompiled .= '
				<span class="categoryList-togglerSpacer"></span>
			';
	}
	$__finalCompiled .= '
			<a href="' . $__templater->func('link', array('auction', $__vars['category'], ), true) . '" class="categoryList-link' . ($__vars['isSelected'] ? ' is-selected' : '') . '">
				' . $__templater->escape($__vars['category']['title']) . '
			</a>
			<span class="categoryList-label">
				<span class="label label--subtle label--smallest">' . $__templater->escape($__vars['category']['auctions_count']) . '</span>
			</span>
		</div>
		';
	if ($__vars['children']) {
		$__finalCompiled .= '
		
			' . $__templater->callMacro(null, 'simple_category_list', array(
			'selected' => $__vars['selected'],
			'pathToSelected' => $__vars['pathToSelected'],
			'children' => $__vars['children'],
			'isActive' => $__vars['isActive'],
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
	</li>
';
	return $__finalCompiled;
}
),
'simple_list_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'categoryTree' => '!',
		'selected' => 0,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Categories' . '</h3>
			<div class="block-body">
				';
	if ($__templater->method($__vars['categoryTree'], 'count', array())) {
		$__finalCompiled .= '
					' . $__templater->callMacro(null, 'simple_category_list', array(
			'children' => $__vars['categoryTree'],
			'isActive' => true,
			'selected' => $__vars['selected'],
			'pathToSelected' => ($__vars['selected'] ? $__templater->method($__vars['categoryTree'], 'getPathTo', array($__vars['selected'], )) : array()),
		), $__vars) . '
				';
	} else {
		$__finalCompiled .= '
					<div class="block-row">' . 'N/A' . '</div>
				';
	}
	$__finalCompiled .= '
				
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
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);