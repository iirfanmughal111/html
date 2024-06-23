<?php
// FROM HASH: 0bac3965e0421845139b3ed0c5e899ed
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__vars['category']['meta_title'] ? $__templater->escape($__vars['category']['meta_title']) : $__templater->escape($__vars['category']['title'])) . ' - ' . 'Map');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped('Map');
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

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/categories/map', $__vars['category'], ), false),
	), $__vars) . '

' . $__templater->callMacro('xa_sc_item_page_macros', 'item_page_options', array(
		'category' => $__vars['category'],
	), $__vars) . '
';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array(true, )));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		' . $__templater->callMacro('xa_sc_index_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'showcase/categories/map',
		'category' => $__vars['category'],
		'creatorFilter' => $__vars['creatorFilter'],
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['mapItems'], 'empty', array())) {
		$__finalCompiled .= '
				' . $__templater->callMacro('xa_sc_map_macros', 'items_map', array(
			'mapItems' => $__vars['mapItems'],
			'mapId' => 'sc-cat-' . $__vars['category']['category_id'] . '-full',
			'containerHeight' => '800',
		), $__vars) . '	
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . 'There are no items matching your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No items have been added yet.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);