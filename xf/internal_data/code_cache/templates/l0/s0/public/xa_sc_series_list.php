<?php
// FROM HASH: 2b80131e202cc92aef1489123de883d5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Series list');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/series', null, array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), ));
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'canCreateShowcaseSeries', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Create series', array(
			'href' => $__templater->func('link', array('showcase/series/create-series', ), false),
			'class' => 'button--cta',
			'icon' => 'add',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->callMacro('xa_sc_series_list_macros', 'featured_carousel', array(
		'featuredSeries' => $__vars['featuredSeries'],
		'viewAllLink' => $__templater->func('link', array('showcase/series/featured', ), false),
	), $__vars) . '

' . $__templater->widgetPosition('xa_sc_series_list_above_series_list', array()) . '

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

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="sc_series" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
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
		'link' => 'showcase/series',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp1 . '

	'), false) . '</div>

	<div class="block-container">
		' . $__templater->callMacro('xa_sc_series_list_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'showcase/series',
		'creatorFilter' => $__vars['creatorFilter'],
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['series'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['series'])) {
			foreach ($__vars['series'] AS $__vars['seriesItem']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_series_list_macros', 'series', array(
					'series' => $__vars['seriesItem'],
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . 'There are no series matching your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No series have been created yet.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/series',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>

' . $__templater->widgetPosition('xa_sc_series_list_below_series_list', array()) . '

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar4869a92a3f4c2d5b3fd8822b4f2617c0', $__templater->widgetPosition('xa_sc_series_list_sidebar', array()), 'replace');
	return $__finalCompiled;
}
);