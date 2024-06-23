<?php
// FROM HASH: 1ef014e0eec5c85d023c096a0b67c7cd
return array(
'extensions' => array('structured_data_extra_params' => function($__templater, array $__vars, $__extensions = null)
{
	return array();
},
'structured_data' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
        ';
	$__vars['ldJson'] = $__templater->method($__vars['series'], 'getLdStructuredData', array($__vars['page'], $__templater->renderExtension('structured_data_extra_params', $__vars, $__extensions), ));
	$__finalCompiled .= '
        ';
	if ($__vars['ldJson']) {
		$__finalCompiled .= '
            <script type="application/ld+json">
                ' . $__templater->filter($__vars['ldJson'], array(array('json', array(true, )),array('raw', array()),), true) . '
            </script>
        ';
	}
	$__finalCompiled .= '
    ';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__vars['series']['meta_title'] ? $__templater->escape($__vars['series']['meta_title']) : $__templater->escape($__vars['series']['title'])));
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	if (!$__templater->method($__vars['series'], 'isSearchEngineIndexable', array())) {
		$__finalCompiled .= '
	';
		$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['series']['meta_description']) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['series']['meta_description'], 320, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else if ($__vars['series']['description']) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['series']['description'], 250, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['series']['message'], 250, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'title' => ($__vars['series']['og_title'] ? $__vars['series']['og_title'] : ($__vars['series']['meta_title'] ? $__vars['series']['meta_title'] : $__vars['series']['title'])),
		'description' => $__vars['descSnippet'],
		'shareUrl' => $__templater->func('link', array('canonical:showcase/series', $__vars['series'], ), false),
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/series', $__vars['series'], array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('ldJsonHtml', '
    ' . '' . '
    ' . $__templater->renderExtension('structured_data', $__vars, $__extensions) . '
');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'overview';
	$__templater->wrapTemplate('xa_sc_series_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="block">
	<div class="block-outer">';
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
				' . $__templater->callMacro('xa_sc_series_wrapper_macros', 'action_buttons', array(
		'series' => $__vars['series'],
		'canInlineMod' => $__vars['canInlineMod'],
		'showAddItemButton' => 'true',
	), $__vars) . '
			';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '
			<div class="block-outer-opposite">
			' . $__compilerTemp3 . '
			</div>
		';
	}
	$__finalCompiled .= $__templater->func('trim', array('
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/series',
		'data' => $__vars['series'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp2 . '
	'), false) . '</div>
	
	';
	if ($__vars['poll']) {
		$__finalCompiled .= '
		' . $__templater->callMacro('poll_macros', 'poll_block', array(
			'poll' => $__vars['poll'],
		), $__vars) . '
	';
	}
	$__finalCompiled .= '

	<div class="block-container">
		<div class="block-body">
			';
	if (!$__templater->test($__vars['seriesParts'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['seriesParts'])) {
			foreach ($__vars['seriesParts'] AS $__vars['seriesPart']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_series_part_list_macros', 'series_part_list', array(
					'seriesPart' => $__vars['seriesPart'],
					'series' => $__vars['seriesPart']['Series'],
					'item' => $__vars['seriesPart']['Item'],
					'category' => $__vars['seriesPart']['Item']['Category'],
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . 'There are no items matching your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No items have been added to this series.' . '</div>
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
		'data' => $__vars['series'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>

';
	$__compilerTemp4 = '';
	if ($__vars['series']['community_series']) {
		$__compilerTemp4 .= '
				<h3 class="block-minorHeader">' . 'Community series information' . '</h3>
			';
	} else {
		$__compilerTemp4 .= '
				<h3 class="block-minorHeader">' . 'Series information' . '</h3>
			';
	}
	$__compilerTemp5 = '';
	if ($__vars['series']['community_series']) {
		$__compilerTemp5 .= '
						<dt>' . 'Manager' . '</dt>
					';
	} else {
		$__compilerTemp5 .= '
						<dt>' . 'Author' . '</dt>
					';
	}
	$__compilerTemp6 = '';
	if ($__vars['series']['community_series']) {
		$__compilerTemp6 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Contributors' . '</dt>
						<dd>' . $__templater->filter($__vars['totalContributors'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp7 = '';
	if ($__vars['series']['item_count']) {
		$__compilerTemp7 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Items' . '</dt>
						<dd>' . $__templater->filter($__vars['series']['item_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp8 = '';
	if ($__vars['series']['view_count']) {
		$__compilerTemp8 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Views' . '</dt>
						<dd>' . $__templater->filter($__vars['series']['view_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp9 = '';
	if ($__vars['series']['watch_count']) {
		$__compilerTemp9 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Watching' . '</dt>
						<dd>' . $__templater->filter($__vars['series']['watch_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp10 = '';
	if ($__vars['series']['last_part_date']) {
		$__compilerTemp10 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Last update' . '</dt>
						<dd>' . $__templater->func('date_dynamic', array($__vars['series']['last_part_date'], array(
		))) . '</dd>
					</dl>
				';
	}
	$__templater->modifySidebarHtml('infoSidebar', '
	<div class="block">
		<div class="block-container">
			' . $__compilerTemp4 . '
			<div class="block-body block-row block-row--minor">
				<dl class="pairs pairs--justified">
					' . $__compilerTemp5 . '
					<dd>' . $__templater->func('username_link', array($__vars['series']['User'], false, array(
		'defaultname' => $__vars['series']['User']['username'],
	))) . '</dd>
				</dl>
				' . $__compilerTemp6 . '
				' . $__compilerTemp7 . '
				' . $__compilerTemp8 . '
				' . $__compilerTemp9 . '				
				' . $__compilerTemp10 . '
			</div>
		</div>
	</div>
', 'replace');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['communityContributors'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp11 = '';
		if ($__templater->isTraversable($__vars['communityContributors'])) {
			foreach ($__vars['communityContributors'] AS $__vars['communityContributor']) {
				$__compilerTemp11 .= '
						<li class="block-row">
							<div class="contentRow">
								<div class="contentRow-figure">
									' . $__templater->func('avatar', array($__vars['communityContributor'], 'xs', false, array(
				))) . '
								</div>
								<div class="contentRow-main contentRow-main--close">
									' . $__templater->func('username_link', array($__vars['communityContributor'], true, array(
				))) . '
									<div class="contentRow-minor">
										' . $__templater->func('user_title', array($__vars['communityContributor'], false, array(
				))) . '
									</div>
								</div>
							</div>
						</li>
					';
			}
		}
		$__templater->modifySidebarHtml('communityBloggersSidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader">' . 'Contributors' . '</h3>
				<ul class="block-body">
					' . $__compilerTemp11 . '
				</ul>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp12 = '';
	$__compilerTemp13 = '';
	$__compilerTemp13 .= '
					<h3 class="block-minorHeader">' . 'Share this series' . '</h3>
					';
	$__compilerTemp14 = '';
	$__compilerTemp14 .= '
								' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp14)) > 0) {
		$__compilerTemp13 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp14 . '
						</div>
					';
	}
	$__compilerTemp13 .= '
					';
	$__compilerTemp15 = '';
	$__compilerTemp15 .= '
								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy URL BB code',
		'text' => '[URL="' . $__templater->func('link', array('canonical:showcase/series', $__vars['series'], ), false) . '"]' . $__vars['series']['title'] . '[/URL]',
	), $__vars) . '

								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy SHOWCASE BB code',
		'text' => '[SHOWCASE=series, ' . $__vars['series']['series_id'] . '][/SHOWCASE]',
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp15)) > 0) {
		$__compilerTemp13 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp15 . '
						</div>
					';
	}
	$__compilerTemp13 .= '
				';
	if (strlen(trim($__compilerTemp13)) > 0) {
		$__compilerTemp12 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp13 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('shareSidebar', '
	' . $__compilerTemp12 . '
', 'replace');
	return $__finalCompiled;
}
);