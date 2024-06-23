<?php
// FROM HASH: e5edd932f8881f0af9eabf8801b174bb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Showcase' . ' - ' . 'Map');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped('Map');
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/full-map', ), false),
	), $__vars) . '

<div class="block">
	<div class="block-container">
		' . $__templater->callMacro('xa_sc_index_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'showcase/full-map',
		'creatorFilter' => $__vars['creatorFilter'],
		'listType' => 'scIndexMapView',
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['mapItems'], 'empty', array())) {
		$__finalCompiled .= '
				' . $__templater->callMacro('xa_sc_map_macros', 'items_map', array(
			'mapItems' => $__vars['mapItems'],
			'mapId' => 'sc-index-full',
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