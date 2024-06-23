<?php
// FROM HASH: 62a0ced43d3d222c921999e7dedb85ab
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . ($__vars['item']['meta_title'] ? $__templater->escape($__vars['item']['meta_title']) : $__templater->escape($__vars['item']['title'])) . ' - ' . $__templater->escape($__vars['sectionTitle']));
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	if (($__vars['sectionId'] == 2) AND ($__vars['item']['message_s2'] != '')) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['item']['message_s2'], 250, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else if (($__vars['sectionId'] == 3) AND ($__vars['item']['message_s3'] != '')) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['item']['message_s3'], 250, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else if (($__vars['sectionId'] == 4) AND ($__vars['item']['message_s4'] != '')) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['item']['message_s4'], 250, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else if (($__vars['sectionId'] == 5) AND ($__vars['item']['message_s5'] != '')) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['item']['message_s5'], 250, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else if (($__vars['sectionId'] == 6) AND ($__vars['item']['message_s6'] != '')) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['item']['message_s6'], 250, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		if ($__vars['item']['meta_description']) {
			$__finalCompiled .= '
		';
			$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['item']['meta_description'], 320, array('stripBbCode' => true, ), ), false);
			$__finalCompiled .= '
	';
		} else if ($__vars['item']['description']) {
			$__finalCompiled .= '
		';
			$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['item']['description'], 256, array('stripBbCode' => true, ), ), false);
			$__finalCompiled .= '
	';
		} else {
			$__finalCompiled .= '
		';
			$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['item']['message'], 256, array('stripBbCode' => true, ), ), false);
			$__finalCompiled .= '
	';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'title' => ($__vars['item']['og_title'] ? $__vars['item']['og_title'] : ($__vars['item']['meta_title'] ? $__vars['item']['meta_title'] : $__vars['item']['title'])) . ' - ' . $__vars['sectionTitle'],
		'description' => $__vars['descSnippet'],
		'type' => 'article',
		'shareUrl' => $__templater->func('link', array('canonical:showcase', $__vars['item'], array('section' => $__vars['sectionId'], ), ), false),
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase', $__vars['item'], array('section' => $__vars['sectionId'], ), ), false),
		'imageUrl' => ($__vars['item']['CoverImage'] ? $__templater->func('link', array('canonical:showcase/cover-image', $__vars['item'], ), false) : ($__vars['item']['Category']['content_image_url'] ? $__templater->func('base_url', array($__vars['item']['Category']['content_image_url'], true, ), false) : '')),
		'twitterCard' => 'summary_large_image',
	), $__vars) . '

';
	$__compilerTemp1 = '';
	if ($__vars['item']['meta_title']) {
		$__compilerTemp1 .= '
			"headline": "' . $__templater->filter($__vars['item']['meta_title'], array(array('escape', array('json', )),), true) . ' - ' . $__templater->filter($__vars['sectionTitle'], array(array('escape', array('json', )),), true) . '",
		';
	} else {
		$__compilerTemp1 .= '
			"headline": "' . $__templater->filter($__vars['item']['title'], array(array('escape', array('json', )),), true) . ' - ' . $__templater->filter($__vars['sectionTitle'], array(array('escape', array('json', )),), true) . '",
		';
	}
	$__compilerTemp2 = '';
	if ($__vars['item']['og_title']) {
		$__compilerTemp2 .= '
			"alternativeHeadline": "' . $__templater->filter($__vars['item']['og_title'], array(array('escape', array('json', )),), true) . ' - ' . $__templater->filter($__vars['sectionTitle'], array(array('escape', array('json', )),), true) . '",
		';
	} else {
		$__compilerTemp2 .= '
			"alternativeHeadline": "' . $__templater->filter($__vars['item']['title'], array(array('escape', array('json', )),), true) . ' - ' . $__templater->filter($__vars['sectionTitle'], array(array('escape', array('json', )),), true) . '",
		';
	}
	$__compilerTemp3 = '';
	if ($__vars['item']['CoverImage']) {
		$__compilerTemp3 .= '
			"thumbnailUrl": "' . $__templater->filter($__templater->method($__vars['item']['CoverImage'], 'getThumbnailUrlFull', array()), array(array('escape', array('json', )),), true) . '",
		';
	} else if ($__vars['item']['Category']['content_image_url']) {
		$__compilerTemp3 .= '
			"thumbnailUrl": "' . $__templater->filter($__templater->func('base_url', array($__vars['item']['Category']['content_image_url'], true, ), false), array(array('escape', array('json', )),), true) . '",
		';
	}
	$__compilerTemp4 = '';
	if ($__vars['item']['rating_count']) {
		$__compilerTemp4 .= '"aggregateRating": {
			"@type": "AggregateRating",
			"ratingCount": "' . $__templater->filter($__vars['item']['rating_count'], array(array('escape', array('json', )),), true) . '",
			"ratingValue": "' . $__templater->filter($__vars['item']['rating_avg'], array(array('escape', array('json', )),), true) . '"
		},';
	}
	$__compilerTemp5 = '';
	if ($__templater->method($__vars['item'], 'hasViewableDiscussion', array())) {
		$__compilerTemp5 .= '
			"discussionUrl": "' . $__templater->filter($__templater->func('link', array('canonical:threads', $__vars['item']['Discussion'], ), false), array(array('escape', array('json', )),), true) . '",
		';
	}
	$__templater->setPageParam('ldJsonHtml', '
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "CreativeWorkSeries",
		"@id": "' . $__templater->filter($__templater->func('link', array('canonical:showcase', $__vars['item'], array('section' => $__vars['sectionId'], ), ), false), array(array('escape', array('json', )),), true) . '",
		"name": "' . $__templater->filter($__vars['item']['title'], array(array('escape', array('json', )),), true) . '",

		' . $__compilerTemp1 . '
		' . $__compilerTemp2 . '
		"description": "' . $__templater->filter($__vars['descSnippet'], array(array('escape', array('json', )),), true) . '",
		' . $__compilerTemp3 . '
		"dateCreated": "' . $__templater->filter($__templater->func('date', array($__vars['item']['create_date'], 'c', ), false), array(array('escape', array('json', )),), true) . '",
		"dateModified": "' . $__templater->filter($__templater->func('date', array($__vars['item']['last_update'], 'c', ), false), array(array('escape', array('json', )),), true) . '",
		' . $__compilerTemp4 . '
		' . $__compilerTemp5 . '
		"author": {
			"@type": "Person",
			"name": "' . $__templater->filter(($__vars['item']['User'] ? $__vars['item']['User']['username'] : $__vars['item']['username']), array(array('escape', array('json', )),), true) . '"
		}
	}
	</script>
');
	$__finalCompiled .= '

';
	$__compilerTemp6 = $__vars;
	$__compilerTemp6['pageSelected'] = 'section_' . $__vars['sectionId'];
	$__templater->wrapTemplate('xa_sc_item_wrapper', $__compilerTemp6);
	$__finalCompiled .= '

<div class="block">
	';
	$__compilerTemp7 = '';
	$__compilerTemp7 .= '
				' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'action_buttons', array(
		'item' => $__vars['item'],
		'section' => $__vars['section'],
		'sectionTitle' => $__vars['sectionTitle'],
		'seriesToc' => $__vars['seriesToc'],
		'itemPages' => $__vars['itemPages'],
		'showTableOfContents' => ($__templater->method($__vars['item'], 'canViewFullItem', array()) ? true : false),
		'showPostAnUpdateButton' => true,
		'showRateButton' => 'true',
		'showAddPageButton' => true,
		'showAddToSeriesButton' => true,
	), $__vars) . '
			';
	if (strlen(trim($__compilerTemp7)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer">
			<div class="block-outer-opposite">
			' . $__compilerTemp7 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	<div class="block-container">
		<div class="block-body">
			<div class="itemBody">
				<article class="itemBody-main">

					' . $__templater->callAdsMacro('sc_item_view_above_item_sections_content', array(
		'item' => $__vars['item'],
	), $__vars) . '

					';
	if ($__vars['sectionId'] == 2) {
		$__finalCompiled .= '
						';
		$__compilerTemp8 = '';
		$__compilerTemp8 .= '
								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_2_above',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--before',
		), $__vars) . '

								';
		if ($__vars['category']['editor_s2'] AND ($__vars['item']['message_s2'] != '')) {
			$__compilerTemp8 .= '
									' . $__templater->func('bb_code', array($__vars['item']['message_s2'], 'sc_item', $__vars['item'], ), true) . '
								';
		}
		$__compilerTemp8 .= '

								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_2_below',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--after',
		), $__vars) . '
							';
		if (strlen(trim($__compilerTemp8)) > 0) {
			$__finalCompiled .= '
							' . $__compilerTemp8 . '
						';
		}
		$__finalCompiled .= '
					';
	} else if ($__vars['sectionId'] == 3) {
		$__finalCompiled .= '
						';
		$__compilerTemp9 = '';
		$__compilerTemp9 .= '
								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_3_above',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--before',
		), $__vars) . '

								';
		if ($__vars['category']['editor_s3'] AND ($__vars['item']['message_s3'] != '')) {
			$__compilerTemp9 .= '
									' . $__templater->func('bb_code', array($__vars['item']['message_s3'], 'sc_item', $__vars['item'], ), true) . '
								';
		}
		$__compilerTemp9 .= '

								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_3_below',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--after',
		), $__vars) . '
							';
		if (strlen(trim($__compilerTemp9)) > 0) {
			$__finalCompiled .= '
							' . $__compilerTemp9 . '
						';
		}
		$__finalCompiled .= '
					';
	} else if ($__vars['sectionId'] == 4) {
		$__finalCompiled .= '
						';
		$__compilerTemp10 = '';
		$__compilerTemp10 .= '
								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_4_above',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--before',
		), $__vars) . '

								';
		if ($__vars['category']['editor_s4'] AND ($__vars['item']['message_s4'] != '')) {
			$__compilerTemp10 .= '
									' . $__templater->func('bb_code', array($__vars['item']['message_s4'], 'sc_item', $__vars['item'], ), true) . '
								';
		}
		$__compilerTemp10 .= '

								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_4_below',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--after',
		), $__vars) . '
							';
		if (strlen(trim($__compilerTemp10)) > 0) {
			$__finalCompiled .= '
							' . $__compilerTemp10 . '
						';
		}
		$__finalCompiled .= '
					';
	} else if ($__vars['sectionId'] == 5) {
		$__finalCompiled .= '
						';
		$__compilerTemp11 = '';
		$__compilerTemp11 .= '
								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_5_above',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--before',
		), $__vars) . '

								';
		if ($__vars['category']['editor_s5'] AND ($__vars['item']['message_s5'] != '')) {
			$__compilerTemp11 .= '
									' . $__templater->func('bb_code', array($__vars['item']['message_s5'], 'sc_item', $__vars['item'], ), true) . '
								';
		}
		$__compilerTemp11 .= '

								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_5_below',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--after',
		), $__vars) . '
							';
		if (strlen(trim($__compilerTemp11)) > 0) {
			$__finalCompiled .= '
							' . $__compilerTemp11 . '
						';
		}
		$__finalCompiled .= '
					';
	} else if ($__vars['sectionId'] == 6) {
		$__finalCompiled .= '
						';
		$__compilerTemp12 = '';
		$__compilerTemp12 .= '
								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_6_above',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--before',
		), $__vars) . '

								';
		if ($__vars['category']['editor_s6'] AND ($__vars['item']['message_s6'] != '')) {
			$__compilerTemp12 .= '
									' . $__templater->func('bb_code', array($__vars['item']['message_s6'], 'sc_item', $__vars['item'], ), true) . '
								';
		}
		$__compilerTemp12 .= '

								' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_6_below',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--after',
		), $__vars) . '
							';
		if (strlen(trim($__compilerTemp12)) > 0) {
			$__finalCompiled .= '
							' . $__compilerTemp12 . '
						';
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '

					' . $__templater->callAdsMacro('sc_item_view_below_item_sections_content', array(
		'item' => $__vars['item'],
	), $__vars) . '
				</article>
			</div>
		</div>
	</div>
</div>

';
	$__compilerTemp13 = '';
	$__compilerTemp13 .= '
				' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
		'label' => 'Share' . ':',
	), $__vars) . '
			';
	if (strlen(trim($__compilerTemp13)) > 0) {
		$__finalCompiled .= '
	<div class="block">
		<div class="blockMessage blockMessage--none">
			' . $__compilerTemp13 . '
		</div>
	</div>	
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp14 = '';
	if ($__vars['item']['message_s2'] AND ($__vars['item']['message_s2'] != '')) {
		$__compilerTemp14 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp14 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 2, ), ), true) . '#section_2" class="blockLink ' . (($__vars['section'] == 'section_2') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s2']) . '</a>
					';
		} else {
			$__compilerTemp14 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 2, ), ), true) . '" class="blockLink ' . (($__vars['section'] == 'section_2') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s2']) . '</a>
					';
		}
		$__compilerTemp14 .= '
				';
	}
	$__compilerTemp15 = '';
	if ($__vars['item']['message_s3'] AND ($__vars['item']['message_s3'] != '')) {
		$__compilerTemp15 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp15 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 3, ), ), true) . '#section_3" class="blockLink ' . (($__vars['section'] == 'section_3') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s3']) . '</a>
					';
		} else {
			$__compilerTemp15 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 3, ), ), true) . '" class="blockLink ' . (($__vars['section'] == 'section_3') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s3']) . '</a>
					';
		}
		$__compilerTemp15 .= '
				';
	}
	$__compilerTemp16 = '';
	if ($__vars['item']['message_s4'] AND ($__vars['item']['message_s4'] != '')) {
		$__compilerTemp16 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp16 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 4, ), ), true) . '#section_4" class="blockLink ' . (($__vars['section'] == 'section_4') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s4']) . '</a>
					';
		} else {
			$__compilerTemp16 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 4, ), ), true) . '" class="blockLink ' . (($__vars['section'] == 'section_4') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s4']) . '</a>
					';
		}
		$__compilerTemp16 .= '
				';
	}
	$__compilerTemp17 = '';
	if ($__vars['item']['message_s5'] AND ($__vars['item']['message_s5'] != '')) {
		$__compilerTemp17 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp17 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 5, ), ), true) . '#section_5" class="blockLink ' . (($__vars['section'] == 'section_5') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s5']) . '</a>
					';
		} else {
			$__compilerTemp17 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 5, ), ), true) . '" class="blockLink ' . (($__vars['section'] == 'section_5') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s5']) . '</a>
					';
		}
		$__compilerTemp17 .= '
				';
	}
	$__compilerTemp18 = '';
	if ($__vars['item']['message_s6'] AND ($__vars['item']['message_s6'] != '')) {
		$__compilerTemp18 .= '
					';
		if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
			$__compilerTemp18 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 6, ), ), true) . '#section_6" class="blockLink ' . (($__vars['section'] == 'section_6') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s6']) . '</a>
					';
		} else {
			$__compilerTemp18 .= '	
						<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 6, ), ), true) . '" class="blockLink ' . (($__vars['section'] == 'section_6') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s6']) . '</a>
					';
		}
		$__compilerTemp18 .= '
				';
	}
	$__compilerTemp19 = '';
	$__compilerTemp20 = $__templater->method($__vars['item'], 'getExtraFieldTabs', array());
	if ($__templater->isTraversable($__compilerTemp20)) {
		foreach ($__compilerTemp20 AS $__vars['_fieldId'] => $__vars['_fieldValue']) {
			$__compilerTemp19 .= '
					<a href="' . $__templater->func('link', array('showcase/field', $__vars['item'], array('field' => $__vars['_fieldId'], ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['_fieldValue']) . '</a>
				';
		}
	}
	$__compilerTemp21 = '';
	if (!$__templater->test($__vars['itemPages'], 'empty', array())) {
		$__compilerTemp21 .= '
					';
		if ($__templater->isTraversable($__vars['itemPages'])) {
			foreach ($__vars['itemPages'] AS $__vars['item_page']) {
				$__compilerTemp21 .= '
						<a href="' . $__templater->func('link', array('showcase/page', $__vars['item_page'], ), true) . '" class="blockLink">
							' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['item_page']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['item_page']['title']) . '</a>
					';
			}
		}
		$__compilerTemp21 .= '

					';
		if ($__vars['xf']['options']['xaScViewFullItem']) {
			$__compilerTemp21 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('full' => 1, ), ), true) . '#section_1" class="blockLink">' . 'Full view' . '</a>
					';
		}
		$__compilerTemp21 .= '
				';
	}
	$__templater->modifySidebarHtml('itemTocSidebar', '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Table of contents' . '</h3>
			<div class="block-body">
				<a class="blockLink" href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#section_1">' . $__templater->escape($__vars['category']['title_s1']) . '</a>
 
				' . $__compilerTemp14 . '

				' . $__compilerTemp15 . '

				' . $__compilerTemp16 . '

				' . $__compilerTemp17 . '

				' . $__compilerTemp18 . '

				' . $__compilerTemp19 . '

				' . $__compilerTemp21 . '
			</div>
		</div>
	</div>
', 'replace');
	$__finalCompiled .= '


';
	if (!$__templater->test($__vars['seriesToc'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp22 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp22 .= '
						<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '">' . 'Series' . '</a>
					';
		} else {
			$__compilerTemp22 .= '
						' . 'Series' . '
					';
		}
		$__compilerTemp23 = '';
		if ($__templater->isTraversable($__vars['seriesToc'])) {
			foreach ($__vars['seriesToc'] AS $__vars['seriesToc_part']) {
				$__compilerTemp23 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['seriesToc_part']['Item'], ), true) . '" class="blockLink ' . (($__vars['seriesToc_part']['series_part_id'] == $__vars['item']['series_part_id']) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['seriesToc_part']['Item']['title']) . '</a>
					';
			}
		}
		$__compilerTemp24 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp24 .= '
						<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '" class="blockLink">' . 'View series' . '</a>
					';
		}
		$__templater->modifySidebarHtml('seriesTocSidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-header">
					' . $__compilerTemp22 . '
				</h3>
				<div class="block-body">
					' . $__compilerTemp23 . '

					' . $__compilerTemp24 . '
				</div>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp25 = '';
	$__compilerTemp26 = '';
	$__compilerTemp26 .= '
					';
	if ($__templater->method($__vars['item'], 'hasViewableDiscussion', array())) {
		$__compilerTemp26 .= '
						' . $__templater->button('Join discussion', array(
			'href' => $__templater->func('link', array('threads', $__vars['item']['Discussion'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp26 .= '
				';
	if (strlen(trim($__compilerTemp26)) > 0) {
		$__compilerTemp25 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp26 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('discussionButtonSidebar', '
	' . $__compilerTemp25 . '	
', 'replace');
	$__finalCompiled .= '

';
	$__compilerTemp27 = '';
	$__compilerTemp28 = '';
	$__compilerTemp28 .= '
					<h3 class="block-minorHeader">' . ($__vars['item']['Category']['content_term'] ? 'Share this ' . $__templater->filter($__vars['item']['Category']['content_term'], array(array('to_lower', array()),), true) . ' section' : 'xa_sc_share_this_section') . '</h3>
					';
	$__compilerTemp29 = '';
	$__compilerTemp29 .= '
								' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp29)) > 0) {
		$__compilerTemp28 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp29 . '
						</div>
					';
	}
	$__compilerTemp28 .= '
					';
	$__compilerTemp30 = '';
	$__compilerTemp30 .= '
								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy URL BB code',
		'text' => '[URL="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], array('section' => $__vars['sectionId'], ), ), false) . '"]' . $__vars['itemPage']['title'] . '[/URL]',
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp30)) > 0) {
		$__compilerTemp28 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp30 . '
						</div>
					';
	}
	$__compilerTemp28 .= '
				';
	if (strlen(trim($__compilerTemp28)) > 0) {
		$__compilerTemp27 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp28 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('shareSidebar', '
	' . $__compilerTemp27 . '
', 'replace');
	return $__finalCompiled;
}
);