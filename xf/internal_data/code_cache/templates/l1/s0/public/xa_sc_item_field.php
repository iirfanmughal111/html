<?php
// FROM HASH: b89baa5090b6856543601ddc8e7151aa
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . ($__vars['item']['meta_title'] ? $__templater->escape($__vars['item']['meta_title']) : $__templater->escape($__vars['item']['title'])) . ' - ' . $__templater->escape($__vars['fieldDefinition']['title']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'field_' . $__vars['fieldId'];
	$__templater->wrapTemplate('xa_sc_item_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="block">
	';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
				' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'action_buttons', array(
		'item' => $__vars['item'],
		'itemField' => $__vars['fieldId'],
		'itemPages' => $__vars['itemPages'],
		'seriesToc' => $__vars['seriesToc'],
		'showTableOfContents' => ($__templater->method($__vars['item'], 'canViewFullItem', array()) ? true : false),
		'showPostAnUpdateButton' => true,
		'showRateButton' => 'true',
		'showAddPageButton' => true,
		'showAddToSeriesButton' => true,
	), $__vars) . '
			';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer">
			<div class="block-outer-opposite">
			' . $__compilerTemp2 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	<div class="block-container">
		<div class="block-body block-row">
			' . $__templater->callMacro('custom_fields_macros', 'custom_field_value', array(
		'definition' => $__vars['fieldDefinition'],
		'value' => $__vars['fieldValue'],
	), $__vars) . '
		</div>
	</div>
</div>

';
	$__compilerTemp3 = '';
	if ($__vars['item']['message_s2'] AND ($__vars['item']['message_s2'] != '')) {
		$__compilerTemp3 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp3 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 2, ), ), true) . '#section_2" class="blockLink">' . $__templater->escape($__vars['category']['title_s2']) . '</a>
					';
		} else {
			$__compilerTemp3 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 2, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s2']) . '</a>
					';
		}
		$__compilerTemp3 .= '
				';
	}
	$__compilerTemp4 = '';
	if ($__vars['item']['message_s3'] AND ($__vars['item']['message_s3'] != '')) {
		$__compilerTemp4 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp4 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 3, ), ), true) . '#section_3" class="blockLink">' . $__templater->escape($__vars['category']['title_s3']) . '</a>
					';
		} else {
			$__compilerTemp4 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 3, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s3']) . '</a>
					';
		}
		$__compilerTemp4 .= '
				';
	}
	$__compilerTemp5 = '';
	if ($__vars['item']['message_s4'] AND ($__vars['item']['message_s4'] != '')) {
		$__compilerTemp5 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp5 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 4, ), ), true) . '#section_4" class="blockLink">' . $__templater->escape($__vars['category']['title_s4']) . '</a>
					';
		} else {
			$__compilerTemp5 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 4, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s4']) . '</a>
					';
		}
		$__compilerTemp5 .= '
				';
	}
	$__compilerTemp6 = '';
	if ($__vars['item']['message_s5'] AND ($__vars['item']['message_s5'] != '')) {
		$__compilerTemp6 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp6 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 5, ), ), true) . '#section_5" class="blockLink">' . $__templater->escape($__vars['category']['title_s5']) . '</a>
					';
		} else {
			$__compilerTemp6 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 5, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s5']) . '</a>
					';
		}
		$__compilerTemp6 .= '
				';
	}
	$__compilerTemp7 = '';
	if ($__vars['item']['message_s6'] AND ($__vars['item']['message_s6'] != '')) {
		$__compilerTemp7 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp7 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 6, ), ), true) . '#section_6" class="blockLink">' . $__templater->escape($__vars['category']['title_s6']) . '</a>
					';
		} else {
			$__compilerTemp7 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 6, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s6']) . '</a>
					';
		}
		$__compilerTemp7 .= '
				';
	}
	$__compilerTemp8 = '';
	$__compilerTemp9 = $__templater->method($__vars['item'], 'getExtraFieldTabs', array());
	if ($__templater->isTraversable($__compilerTemp9)) {
		foreach ($__compilerTemp9 AS $__vars['_fieldId'] => $__vars['_fieldValue']) {
			$__compilerTemp8 .= '
					<a href="' . $__templater->func('link', array('showcase/field', $__vars['item'], array('field' => $__vars['_fieldId'], ), ), true) . '" class="blockLink ' . (($__vars['fieldId'] == $__vars['_fieldId']) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['_fieldValue']) . '</a>
				';
		}
	}
	$__compilerTemp10 = '';
	if (!$__templater->test($__vars['itemPages'], 'empty', array())) {
		$__compilerTemp10 .= '
					';
		if ($__templater->isTraversable($__vars['itemPages'])) {
			foreach ($__vars['itemPages'] AS $__vars['item_page']) {
				$__compilerTemp10 .= '
						<a href="' . $__templater->func('link', array('showcase/page', $__vars['item_page'], ), true) . '" class="blockLink">
							' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['item_page']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['item_page']['title']) . '</a>
					';
			}
		}
		$__compilerTemp10 .= '

					';
		if ($__vars['xf']['options']['xaScViewFullItem']) {
			$__compilerTemp10 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('full' => 1, ), ), true) . '#section_1" class="blockLink">' . 'Full view' . '</a>
					';
		}
		$__compilerTemp10 .= '
				';
	}
	$__templater->modifySidebarHtml('itemTocSidebar', '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Table of contents' . '</h3>
			<div class="block-body">
				<a class="blockLink" href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#section_1">' . $__templater->escape($__vars['category']['title_s1']) . '</a>
 
				' . $__compilerTemp3 . '

				' . $__compilerTemp4 . '

				' . $__compilerTemp5 . '

				' . $__compilerTemp6 . '

				' . $__compilerTemp7 . '

				' . $__compilerTemp8 . '

				' . $__compilerTemp10 . '
			</div>
		</div>
	</div>
', 'replace');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['seriesToc'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp11 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp11 .= '
						<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '">' . 'Series' . '</a>
					';
		} else {
			$__compilerTemp11 .= '
						' . 'Series' . '
					';
		}
		$__compilerTemp12 = '';
		if ($__templater->isTraversable($__vars['seriesToc'])) {
			foreach ($__vars['seriesToc'] AS $__vars['seriesToc_part']) {
				$__compilerTemp12 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['seriesToc_part']['Item'], ), true) . '" class="blockLink ' . (($__vars['seriesToc_part']['series_part_id'] == $__vars['item']['series_part_id']) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['seriesToc_part']['Item']['title']) . '</a>
					';
			}
		}
		$__compilerTemp13 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp13 .= '
						<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '" class="blockLink">' . 'View series' . '</a>
					';
		}
		$__templater->modifySidebarHtml('seriesTocSidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-header">
					' . $__compilerTemp11 . '
				</h3>
				<div class="block-body">
					' . $__compilerTemp12 . '

					' . $__compilerTemp13 . '
				</div>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp14 = '';
	$__compilerTemp15 = '';
	$__compilerTemp15 .= '
					';
	if ($__templater->method($__vars['item'], 'hasViewableDiscussion', array())) {
		$__compilerTemp15 .= '
						' . $__templater->button('Join discussion', array(
			'href' => $__templater->func('link', array('threads', $__vars['item']['Discussion'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp15 .= '
				';
	if (strlen(trim($__compilerTemp15)) > 0) {
		$__compilerTemp14 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp15 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('discussionButtonSidebar', '
	' . $__compilerTemp14 . '	
', 'replace');
	$__finalCompiled .= '

';
	$__compilerTemp16 = '';
	$__compilerTemp17 = '';
	$__compilerTemp17 .= '
					<h3 class="block-minorHeader">' . ($__vars['item']['Category']['content_term'] ? 'Share this ' . $__templater->filter($__vars['item']['Category']['content_term'], array(array('to_lower', array()),), true) . ' section' : 'xa_sc_share_this_section') . '</h3>
					';
	$__compilerTemp18 = '';
	$__compilerTemp18 .= '
								' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp18)) > 0) {
		$__compilerTemp17 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp18 . '
						</div>
					';
	}
	$__compilerTemp17 .= '
					';
	$__compilerTemp19 = '';
	$__compilerTemp19 .= '
								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy URL BB code',
		'text' => '[URL="' . $__templater->func('link', array('canonical:showcase/field', $__vars['item'], array('field' => $__vars['fieldId'], ), ), false) . '"]' . $__vars['fieldDefinition']['title'] . '[/URL]',
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp19)) > 0) {
		$__compilerTemp17 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp19 . '
						</div>
					';
	}
	$__compilerTemp17 .= '
				';
	if (strlen(trim($__compilerTemp17)) > 0) {
		$__compilerTemp16 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp17 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('shareSidebar', '
	' . $__compilerTemp16 . '
', 'replace');
	return $__finalCompiled;
}
);