<?php
// FROM HASH: a17f095a9a62607506304b300f2efb11
return array(
'macros' => array('category_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'extras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['id'] => $__vars['child']) {
			$__finalCompiled .= '
		' . $__templater->callMacro(null, 'category_list_entry', array(
				'category' => $__vars['child']['record'],
				'extras' => $__vars['extras'][$__vars['id']],
				'children' => $__vars['child']['children'],
				'childExtras' => $__vars['extras'],
				'depth' => $__vars['depth'],
			), $__vars) . '
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'category_list_entry' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->callMacro(null, (($__vars['depth'] == 0) ? 'category_list_entry_add_level_0' : 'category_list_entry_add'), array(
		'category' => $__vars['category'],
		'extras' => $__vars['extras'],
		'children' => $__vars['children'],
		'childExtras' => $__vars['childExtras'],
		'depth' => $__vars['depth'],
	), $__vars) . '
';
	return $__finalCompiled;
}
),
'category_list_entry_add_level_0' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block block--treeEntryChooser">
		<div class="block-container">
			' . $__templater->callMacro(null, 'category_list_entry_add', array(
		'category' => $__vars['category'],
		'extras' => $__vars['extras'],
		'children' => $__vars['children'],
		'childExtras' => $__vars['childExtras'],
		'depth' => $__vars['depth'],
	), $__vars) . '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'category_list_entry_add' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block-row block-row--clickable block-row--separated fauxBlockLink">
		<div class="contentRow contentRow--alignMiddle' . (($__vars['depth'] > 1) ? (' u-depth' . ($__vars['depth'] - 1)) : '') . '">
			<div class="contentRow-main">
				<h2 class="contentRow-title">
					<a href="' . $__templater->func('link', array('dbtech-ecommerce/products/add', null, array('category_id' => $__vars['category']['category_id'], 'product_type' => $__vars['extras']['product_type'], ), ), true) . '" class="fauxBlockLink-blockLink">
						' . $__templater->escape($__vars['category']['title']) . '
					</a>
				</h2>
				';
	if ($__vars['category']['description']) {
		$__finalCompiled .= '
					<div class="contentRow-minor contentRow-minor--singleLine">
						' . $__templater->filter($__vars['category']['description'], array(array('strip_tags', array()),), true) . '
					</div>
				';
	}
	$__finalCompiled .= '
			</div>
			<div class="contentRow-suffix">
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Products' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['product_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
			</div>
		</div>
	</div>
	' . $__templater->callMacro(null, 'category_list', array(
		'children' => $__vars['children'],
		'extras' => $__vars['childExtras'],
		'depth' => ($__vars['depth'] + 1),
	), $__vars) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add product in' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

' . $__templater->callMacro(null, 'category_list', array(
		'children' => $__vars['categoryTree'],
		'extras' => $__vars['categoryExtras'],
	), $__vars) . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);