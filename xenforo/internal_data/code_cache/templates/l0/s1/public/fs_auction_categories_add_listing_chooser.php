<?php
// FROM HASH: 78a8f7cf8bb1e0cf6b5ce3e563583063
return array(
'macros' => array('category_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['category']) {
			$__finalCompiled .= '
		<div class="block-row block-row--clickable block-row--separated fauxBlockLink">
		<div class="contentRow contentRow--alignMiddle' . (($__vars['depth'] > 1) ? (' u-depth' . ($__vars['depth'] - 1)) : '') . '">
			<div class="contentRow-main">
				<h2 class="contentRow-title">
					<a href="' . $__templater->func('link', array('auction/categories/add', $__vars['category'], ), true) . '" class="fauxBlockLink-blockLink">
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
					<dt>' . 'Auctions' . '</dt>
					<dd>' . $__templater->filter($__vars['category']['auctions_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
			</div>
		</div>
	</div>
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add Auction to' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

' . $__templater->callMacro(null, 'category_list', array(
		'children' => $__vars['categories'],
	), $__vars) . '

';
	return $__finalCompiled;
}
);