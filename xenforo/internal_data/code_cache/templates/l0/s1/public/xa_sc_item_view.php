<?php
// FROM HASH: 98611a4c7749780a8dda77ccd58fc9ee
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
	$__vars['ldJson'] = $__templater->method($__vars['item'], 'getLdStructuredData', array($__vars['page'], $__templater->renderExtension('structured_data_extra_params', $__vars, $__extensions), ));
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . ($__vars['item']['meta_title'] ? $__templater->escape($__vars['item']['meta_title']) : $__templater->escape($__vars['item']['title'])));
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	if (!$__templater->method($__vars['item'], 'isSearchEngineIndexable', array())) {
		$__finalCompiled .= '
	';
		$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
		$__finalCompiled .= '
';
	}
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

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'title' => ($__vars['item']['og_title'] ? $__vars['item']['og_title'] : ($__vars['item']['meta_title'] ? $__vars['item']['meta_title'] : $__vars['item']['title'])),
		'description' => $__vars['descSnippet'],
		'type' => 'article',
		'shareUrl' => $__templater->func('link', array('canonical:showcase', $__vars['item'], ), false),
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase', $__vars['item'], array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
		'imageUrl' => ($__vars['item']['CoverImage'] ? $__templater->func('link', array('canonical:showcase/cover-image', $__vars['item'], ), false) : ($__vars['item']['Category']['content_image_url'] ? $__templater->func('base_url', array($__vars['item']['Category']['content_image_url'], true, ), false) : '')),
		'twitterCard' => 'summary_large_image',
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
	$__templater->wrapTemplate('xa_sc_item_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
	), $__vars) . '

<div class="block">
	';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
				' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'action_buttons', array(
		'item' => $__vars['item'],
		'section' => $__vars['section'],
		'seriesToc' => $__vars['seriesToc'],
		'itemPages' => $__vars['itemPages'],
		'isFullView' => $__vars['isFullView'],
		'showTableOfContents' => ($__templater->method($__vars['item'], 'canViewFullItem', array()) ? true : false),
		'showPostAnUpdateButton' => true,
		'showRateButton' => true,
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
	
	';
	if ($__vars['poll']) {
		$__finalCompiled .= '
		' . $__templater->callMacro('poll_macros', 'poll_block', array(
			'poll' => $__vars['poll'],
		), $__vars) . '
	';
	}
	$__finalCompiled .= '
	
	' . $__templater->widgetPosition('xa_sc_item_view_above_item', array(
		'item' => $__vars['item'],
	)) . '
	
	<div class="block-container">
		<div class="block-body lbContainer js-itemBody"
			data-xf-init="lightbox"
			data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
			data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '"
			id="js-itemBody-' . $__templater->escape($__vars['item']['item_id']) . '">

			<div class="itemBody">
				<article class="itemBody-main js-lbContainer"
					data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
					data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '">

					';
	if (($__vars['xf']['options']['xaScGalleryLocation'] == 'above_item') AND $__vars['item']['attach_count']) {
		$__finalCompiled .= '
						';
		$__compilerTemp3 = '';
		$__compilerTemp3 .= '
									';
		if ($__templater->isTraversable($__vars['item']['Attachments'])) {
			foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail'] AND (!$__templater->method($__vars['item'], 'isAttachmentEmbedded', array($__vars['attachment'], )))) {
					$__compilerTemp3 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp3 .= '
								';
		if (strlen(trim($__compilerTemp3)) > 0) {
			$__finalCompiled .= '
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList itemBody-attachments">
								' . $__compilerTemp3 . '
							</ul>
						';
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '

					' . $__templater->callAdsMacro('sc_item_view_above_item_sections_content', array(
		'item' => $__vars['item'],
	), $__vars) . '
					
					';
	if (($__vars['item']['description'] != '') AND $__vars['xf']['options']['xaScDisplayDescriptionItemDetails']) {
		$__finalCompiled .= '
						<div class="bbWrapper itemBody-description">
							' . $__templater->func('snippet', array($__vars['item']['description'], 255, array('stripBbCode' => true, ), ), true) . '
						</div>
					';
	}
	$__finalCompiled .= '					

					';
	if (!$__templater->method($__vars['item'], 'canViewFullItem', array())) {
		$__finalCompiled .= '
						<span class="u-anchorTarget" id="section_1"></span>

						<h2 class="scSectionH2Class scCategory-' . $__templater->escape($__vars['category']['category_id']) . ' scSection-1" id="section-1">' . $__templater->escape($__vars['category']['title_s1']) . '</h2>

						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_1_above',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--before',
		), $__vars) . '

						' . $__templater->func('bb_code', array($__vars['trimmedItem'], 'sc_item', $__vars['item'], ), true) . '

						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_1_below',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--after',
		), $__vars) . '

						<div class="block-rowMessage block-rowMessage--important">
							' . 'You do not have permission to view the full content of this item.' . '
							';
		if (!$__vars['xf']['visitor']['user_id']) {
			$__finalCompiled .= '
								<a href="' . $__templater->func('link', array('login', ), true) . '" data-xf-click="overlay">' . 'Log in or register now.' . '</a>
							';
		}
		$__finalCompiled .= '
						</div>
					';
	} else {
		$__finalCompiled .= '
						<span class="u-anchorTarget" id="section_1"></span>

						<h2 class="scSectionH2Class scCategory-' . $__templater->escape($__vars['category']['category_id']) . ' scSection-1" id="section-1">' . $__templater->escape($__vars['category']['title_s1']) . '</h2>

						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_1_above',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--before',
		), $__vars) . '

						' . $__templater->func('bb_code', array($__vars['item']['message'], 'sc_item', $__vars['item'], ), true) . '

						' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'section_1_below',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--after',
		), $__vars) . '

						';
		if (($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') OR $__vars['isFullView']) {
			$__finalCompiled .= '
							';
			if ($__vars['category']['title_s2']) {
				$__finalCompiled .= '
								';
				$__compilerTemp4 = '';
				$__compilerTemp4 .= '
										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_2_above',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--before',
				), $__vars) . '

										';
				if ($__vars['category']['editor_s2'] AND ($__vars['item']['message_s2'] != '')) {
					$__compilerTemp4 .= '
											' . $__templater->func('bb_code', array($__vars['item']['message_s2'], 'sc_item', $__vars['item'], ), true) . '
										';
				}
				$__compilerTemp4 .= '

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_2_below',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--after',
				), $__vars) . '
									';
				if (strlen(trim($__compilerTemp4)) > 0) {
					$__finalCompiled .= '
									<span class="u-anchorTarget" id="section_2"></span>

									<h2 class="scSectionH2Class scCategory-' . $__templater->escape($__vars['category']['category_id']) . ' scSection-2" id="section-2">' . $__templater->escape($__vars['category']['title_s2']) . '</h2>

									' . $__compilerTemp4 . '
								';
				}
				$__finalCompiled .= '
							';
			}
			$__finalCompiled .= '

							';
			if ($__vars['category']['title_s3']) {
				$__finalCompiled .= '
								';
				$__compilerTemp5 = '';
				$__compilerTemp5 .= '
										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_3_above',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--before',
				), $__vars) . '

										';
				if ($__vars['category']['editor_s3'] AND ($__vars['item']['message_s3'] != '')) {
					$__compilerTemp5 .= '
											' . $__templater->func('bb_code', array($__vars['item']['message_s3'], 'sc_item', $__vars['item'], ), true) . '
										';
				}
				$__compilerTemp5 .= '

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_3_below',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--after',
				), $__vars) . '
									';
				if (strlen(trim($__compilerTemp5)) > 0) {
					$__finalCompiled .= '
									<span class="u-anchorTarget" id="section_3"></span>

									<h2 class="scSectionH2Class scCategory-' . $__templater->escape($__vars['category']['category_id']) . ' scSection-3" id="section-3">' . $__templater->escape($__vars['category']['title_s3']) . '</h2>

									' . $__compilerTemp5 . '
								';
				}
				$__finalCompiled .= '
							';
			}
			$__finalCompiled .= '

							';
			if ($__vars['category']['title_s4']) {
				$__finalCompiled .= '
								';
				$__compilerTemp6 = '';
				$__compilerTemp6 .= '
										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_4_above',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--before',
				), $__vars) . '

										';
				if ($__vars['category']['editor_s4'] AND ($__vars['item']['message_s4'] != '')) {
					$__compilerTemp6 .= '
											' . $__templater->func('bb_code', array($__vars['item']['message_s4'], 'sc_item', $__vars['item'], ), true) . '
										';
				}
				$__compilerTemp6 .= '

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_4_below',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--after',
				), $__vars) . '
									';
				if (strlen(trim($__compilerTemp6)) > 0) {
					$__finalCompiled .= '
									<span class="u-anchorTarget" id="section_4"></span>

									<h2 class="scSectionH2Class scCategory-' . $__templater->escape($__vars['category']['category_id']) . ' scSection-4" id="section-4">' . $__templater->escape($__vars['category']['title_s4']) . '</h2>

									' . $__compilerTemp6 . '
								';
				}
				$__finalCompiled .= '
							';
			}
			$__finalCompiled .= '

							';
			if ($__vars['category']['title_s5']) {
				$__finalCompiled .= '
								';
				$__compilerTemp7 = '';
				$__compilerTemp7 .= '
										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_5_above',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--before',
				), $__vars) . '

										';
				if ($__vars['category']['editor_s5'] AND ($__vars['item']['message_s5'] != '')) {
					$__compilerTemp7 .= '
											' . $__templater->func('bb_code', array($__vars['item']['message_s5'], 'sc_item', $__vars['item'], ), true) . '
										';
				}
				$__compilerTemp7 .= '

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_5_below',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--after',
				), $__vars) . '
									';
				if (strlen(trim($__compilerTemp7)) > 0) {
					$__finalCompiled .= '
									<span class="u-anchorTarget" id="section_5"></span>

									<h2 class="scSectionH2Class scCategory-' . $__templater->escape($__vars['category']['category_id']) . ' scSection-5" id="section-5">' . $__templater->escape($__vars['category']['title_s5']) . '</h2>

									' . $__compilerTemp7 . '
								';
				}
				$__finalCompiled .= '
							';
			}
			$__finalCompiled .= '

							';
			if ($__vars['category']['title_s6']) {
				$__finalCompiled .= '
								';
				$__compilerTemp8 = '';
				$__compilerTemp8 .= '
										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_6_above',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--before',
				), $__vars) . '

										';
				if ($__vars['category']['editor_s6'] AND ($__vars['item']['message_s6'] != '')) {
					$__compilerTemp8 .= '
											' . $__templater->func('bb_code', array($__vars['item']['message_s6'], 'sc_item', $__vars['item'], ), true) . '
										';
				}
				$__compilerTemp8 .= '

										' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
					'type' => 'sc_items',
					'group' => 'section_6_below',
					'onlyInclude' => $__vars['category']['field_cache'],
					'set' => $__vars['item']['custom_fields'],
					'wrapperClass' => 'itemBody-fields itemBody-fields--after',
				), $__vars) . '
									';
				if (strlen(trim($__compilerTemp8)) > 0) {
					$__finalCompiled .= '
									<span class="u-anchorTarget" id="section_6"></span>

									<h2 class="scSectionH2Class scCategory-' . $__templater->escape($__vars['category']['category_id']) . ' scSection-6" id="section-6">' . $__templater->escape($__vars['category']['title_s6']) . '</h2>

									' . $__compilerTemp8 . '
								';
				}
				$__finalCompiled .= '
							';
			}
			$__finalCompiled .= '
						';
		}
		$__finalCompiled .= '

						';
		if ($__vars['isFullView'] AND $__templater->method($__vars['item'], 'getExtraFieldTabs', array())) {
			$__finalCompiled .= '
							';
			$__compilerTemp9 = $__templater->method($__vars['item'], 'getExtraFieldTabs', array());
			if ($__templater->isTraversable($__compilerTemp9)) {
				foreach ($__compilerTemp9 AS $__vars['_fieldId'] => $__vars['_fieldValue']) {
					$__finalCompiled .= '
								<h2 class="scItemFieldH2Class scCategory-' . $__templater->escape($__vars['category']['category_id']) . ' scItemField-' . $__templater->escape($__vars['_fieldId']) . '" id="item-field-' . $__templater->escape($__vars['_fieldId']) . '">' . $__templater->escape($__vars['_fieldValue']) . '</h2>

								' . $__templater->callMacro('custom_fields_macros', 'custom_field_value', array(
						'definition' => $__templater->method($__vars['item']['custom_fields'], 'getDefinition', array($__vars['_fieldId'], )),
						'value' => $__templater->method($__vars['item']['custom_fields'], 'getFieldValue', array($__vars['_fieldId'], )),
					), $__vars) . ' 
							';
				}
			}
			$__finalCompiled .= '
						';
		}
		$__finalCompiled .= '

						';
		if ($__vars['isFullView']) {
			$__finalCompiled .= '
							';
			$__templater->includeCss('xa_sc_page.less');
			$__finalCompiled .= '

							';
			if ($__templater->isTraversable($__vars['itemPages'])) {
				foreach ($__vars['itemPages'] AS $__vars['_item_page']) {
					$__finalCompiled .= '

								<h2 class="scPageH2Class" id="pageid-' . $__templater->escape($__vars['_item_page']['page_id']) . '">' . $__templater->escape($__vars['_item_page']['title']) . '</h2>

								';
					if ($__vars['_item_page']['display_byline']) {
						$__finalCompiled .= '
									<div class="message-attribution message-attribution-scPageMeta">
										<ul class="listInline listInline--bullet">
											<li>
												' . $__templater->fontAwesome('fa-user', array(
							'title' => $__templater->filter('Author', array(array('for_attr', array()),), false),
						)) . '
												<span class="u-srOnly">' . 'Author' . '</span>
												' . $__templater->func('username_link', array($__vars['_item_page']['User'], false, array(
							'defaultname' => $__vars['_item_page']['username'],
							'class' => 'u-concealed',
						))) . '
											</li>
											<li>
												' . $__templater->fontAwesome('fa-clock', array(
							'title' => $__templater->filter('Create date', array(array('for_attr', array()),), false),
						)) . '
												<span class="u-srOnly">' . 'Create date' . '</span>
												<a href="' . $__templater->func('link', array('showcase/page', $__vars['_item_page'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['_item_page']['create_date'], array(
						))) . '</a>
											</li>

											';
						if ($__vars['_item_page']['edit_date'] > $__vars['_item_page']['create_date']) {
							$__finalCompiled .= '								
												<li>
													' . $__templater->fontAwesome('fa-clock', array(
								'title' => $__templater->filter('Last update', array(array('for_attr', array()),), false),
							)) . '
													<span class="u-concealed">' . 'Updated' . '</span>

													' . $__templater->func('date_dynamic', array($__vars['_item_page']['edit_date'], array(
							))) . '
												</li>
											';
						}
						$__finalCompiled .= '
										</ul>
									</div>
								';
					}
					$__finalCompiled .= '

								';
					if ($__vars['_item_page']['CoverImage'] AND $__vars['_item_page']['cover_image_above_page']) {
						$__finalCompiled .= '
									<div class="scCoverImage ' . ($__vars['_item_page']['cover_image_caption'] ? 'has-caption' : '') . '">
										<div class="scCoverImage-container">
											<div class="scCoverImage-container-image js-coverImageContainerImage">
												<img src="' . $__templater->func('link', array('showcase/page/cover-image', $__vars['_item_page'], ), true) . '" alt="' . $__templater->escape($__vars['_item_page']['CoverImage']['filename']) . '" class="js-itemCoverImage" />
											</div>
										</div>
									</div>
									
									';
						if ($__vars['_item_page']['cover_image_caption']) {
							$__finalCompiled .= '
										<div class="scCoverImage-caption">
											' . $__templater->func('snippet', array($__vars['_item_page']['cover_image_caption'], 500, array('stripBbCode' => true, ), ), true) . '
										</div>
									';
						}
						$__finalCompiled .= '									
								';
					}
					$__finalCompiled .= '

								' . $__templater->func('bb_code', array($__vars['_item_page']['message'], 'sc_page', $__vars['_item_page'], ), true) . '
							';
				}
			}
			$__finalCompiled .= '
						';
		}
		$__finalCompiled .= '

					';
	}
	$__finalCompiled .= '

					' . $__templater->callAdsMacro('sc_item_view_below_item_sections_content', array(
		'item' => $__vars['item'],
	), $__vars) . '

					';
	if ($__vars['nextPage'] OR ($__templater->method($__vars['item'], 'isInSeries', array(true, )) AND $__vars['seriesToc'])) {
		$__finalCompiled .= '
						<div style="padding-top: 10px;">
							';
		if ($__vars['nextPage']) {
			$__finalCompiled .= '
								<dl class="blockStatus blockStatus--info blockStatus--standalone">
									<dt></dt>
									<dd class="blockStatus-message">
										<span class="">' . 'Next page' . ':</span>
										<a href="' . $__templater->func('link', array('showcase/page', $__vars['nextPage'], ), true) . '" class="" title="' . $__templater->escape($__vars['nextPage']['title']) . '">' . $__templater->escape($__vars['nextPage']['title']) . '</a>
									</dd>
								</dl>
							';
		}
		$__finalCompiled .= '

							';
		if ($__templater->method($__vars['item'], 'isInSeries', array(true, )) AND $__vars['seriesToc']) {
			$__finalCompiled .= '
								';
			if ($__vars['nextSeriesPart']) {
				$__finalCompiled .= '
									<dl class="blockStatus blockStatus--info blockStatus--standalone">
										<dt></dt>
										<dd class="blockStatus-message">
											<span class="">' . 'Next item in the series \'' . $__templater->escape($__vars['nextSeriesPart']['Series']['title']) . '\'' . $__vars['xf']['language']['label_separator'] . '</span>
											<a href="' . $__templater->func('link', array('showcase', $__vars['nextSeriesPart']['Item'], ), true) . '" class="" title="' . $__templater->escape($__vars['nextSeriesPart']['Item']['title']) . '">
												' . $__templater->escape($__vars['nextSeriesPart']['Item']['title']) . '</a>
										</dd>
									</dl>
								';
			}
			$__finalCompiled .= '

								';
			if ($__vars['previousSeriesPart']) {
				$__finalCompiled .= '
									<dl class="blockStatus blockStatus--info blockStatus--standalone">
										<dt></dt>
										<dd class="blockStatus-message">
											<span class="">' . 'Previous item in the series \'' . $__templater->escape($__vars['previousSeriesPart']['Series']['title']) . '\'' . $__vars['xf']['language']['label_separator'] . '</span>
											<a href="' . $__templater->func('link', array('showcase', $__vars['previousSeriesPart']['Item'], ), true) . '" class="" title="' . $__templater->escape($__vars['previousSeriesPart']['Item']['title']) . '">
												 ' . $__templater->escape($__vars['previousSeriesPart']['Item']['title']) . '</a>
										</dd>
									</dl>
								';
			}
			$__finalCompiled .= '
							';
		}
		$__finalCompiled .= '
						</div>
					';
	}
	$__finalCompiled .= '

					';
	if (($__vars['xf']['options']['xaScGalleryLocation'] == 'below_item') AND $__vars['item']['attach_count']) {
		$__finalCompiled .= '
						';
		$__compilerTemp10 = '';
		$__compilerTemp10 .= '
									';
		if ($__templater->isTraversable($__vars['item']['Attachments'])) {
			foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail'] AND (!$__templater->method($__vars['item'], 'isAttachmentEmbedded', array($__vars['attachment'], )))) {
					$__compilerTemp10 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp10 .= '
								';
		if (strlen(trim($__compilerTemp10)) > 0) {
			$__finalCompiled .= '
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList itemBody-attachments">
								' . $__compilerTemp10 . '
							</ul>
						';
		}
		$__finalCompiled .= '
					';
	} else if ($__vars['item']['attach_count'] AND (($__vars['xf']['options']['xaScGalleryLocation'] == 'own_tab') OR ($__vars['xf']['options']['xaScGalleryLocation'] == 'no_gallery'))) {
		$__finalCompiled .= '
						';
		$__compilerTemp11 = '';
		$__compilerTemp11 .= '
									';
		if ($__templater->isTraversable($__vars['item']['Attachments'])) {
			foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail'] AND (!$__templater->method($__vars['item'], 'isAttachmentEmbedded', array($__vars['attachment'], )))) {
					$__compilerTemp11 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
					), $__vars) . '
									';
				}
			}
		}
		$__compilerTemp11 .= '
								';
		if (strlen(trim($__compilerTemp11)) > 0) {
			$__finalCompiled .= '
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList itemBody-attachments" style="display:none;">
								' . $__compilerTemp11 . '
							</ul>
						';
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '

					';
	if (($__vars['xf']['options']['xaScFilesLocation'] == 'below_item') AND $__vars['item']['attach_count']) {
		$__finalCompiled .= '
						';
		$__compilerTemp12 = '';
		$__compilerTemp12 .= '
									';
		if ($__templater->isTraversable($__vars['item']['Attachments'])) {
			foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
				$__compilerTemp12 .= '
										';
				if ($__vars['attachment']['has_thumbnail'] OR $__vars['attachment']['is_video']) {
					$__compilerTemp12 .= '
											' . '
										';
				} else {
					$__compilerTemp12 .= '
											' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
					), $__vars) . '
										';
				}
				$__compilerTemp12 .= '
									';
			}
		}
		$__compilerTemp12 .= '
								';
		if (strlen(trim($__compilerTemp12)) > 0) {
			$__finalCompiled .= '
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<h3>' . 'Downloads' . '</h3>
							<ul class="attachmentList itemBody-attachments">
								' . $__compilerTemp12 . '
							</ul>
						';
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '

					';
	$__compilerTemp13 = '';
	$__compilerTemp13 .= '
								';
	$__compilerTemp14 = '';
	$__compilerTemp14 .= '
										' . $__templater->func('react', array(array(
		'content' => $__vars['item'],
		'link' => 'showcase/react',
		'list' => '< .js-itemBody | .js-reactionsList',
	))) . '
									';
	if (strlen(trim($__compilerTemp14)) > 0) {
		$__compilerTemp13 .= '
									<div class="actionBar-set actionBar-set--external">
									' . $__compilerTemp14 . '
									</div>
								';
	}
	$__compilerTemp13 .= '

								';
	$__compilerTemp15 = '';
	$__compilerTemp15 .= '
										';
	if ($__templater->method($__vars['item'], 'canReport', array())) {
		$__compilerTemp15 .= '
											<a href="' . $__templater->func('link', array('showcase/report', $__vars['item'], ), true) . '"
												class="actionBar-action actionBar-action--report" 
												data-xf-click="overlay">' . 'Report' . '</a>
										';
	}
	$__compilerTemp15 .= '

										';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp15 .= '
										';
	if ($__templater->method($__vars['item'], 'canEdit', array())) {
		$__compilerTemp15 .= '
											<a href="' . $__templater->func('link', array('showcase/edit', $__vars['item'], ), true) . '"
												class="actionBar-action actionBar-action--edit actionBar-action--menuItem">' . 'Edit' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp15 .= '
										';
	}
	$__compilerTemp15 .= '
										';
	if ($__vars['item']['edit_count'] AND $__templater->method($__vars['item'], 'canViewHistory', array())) {
		$__compilerTemp15 .= '
											<a href="' . $__templater->func('link', array('showcase/history', $__vars['item'], ), true) . '" 
												class="actionBar-action actionBar-action--history actionBar-action--menuItem"
												data-xf-click="toggle"
												data-target="#js-itemBody-' . $__templater->escape($__vars['item']['item_id']) . ' .js-historyTarget"
												data-menu-closer="true">' . 'History' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp15 .= '
										';
	}
	$__compilerTemp15 .= '
										';
	if ($__templater->method($__vars['item'], 'canDelete', array('soft', ))) {
		$__compilerTemp15 .= '
											<a href="' . $__templater->func('link', array('showcase/delete', $__vars['item'], ), true) . '"
												class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
												data-xf-click="overlay">' . 'Delete' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp15 .= '
										';
	}
	$__compilerTemp15 .= '
										';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['item']['ip_id']) {
		$__compilerTemp15 .= '
											<a href="' . $__templater->func('link', array('showcase/ip', $__vars['item'], ), true) . '"
												class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
												data-xf-click="overlay">' . 'IP' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp15 .= '
										';
	}
	$__compilerTemp15 .= '
										';
	if ($__templater->method($__vars['item'], 'canWarn', array())) {
		$__compilerTemp15 .= '
											<a href="' . $__templater->func('link', array('showcase/warn', $__vars['item'], ), true) . '"
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp15 .= '
										';
	} else if ($__vars['item']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp15 .= '
											<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['item']['warning_id'], ), ), true) . '"
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
												data-xf-click="overlay">' . 'View warning' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp15 .= '
										';
	}
	$__compilerTemp15 .= '

										';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp15 .= '
											<a class="actionBar-action actionBar-action--menuTrigger"
												data-xf-click="menu"
												title="' . 'More options' . '"
												role="button"
												tabindex="0"
												aria-expanded="false"
												aria-haspopup="true">&#8226;&#8226;&#8226;</a>

											<div class="menu" data-menu="menu" aria-hidden="true" data-menu-builder="actionBar">
												<div class="menu-content">
													<h4 class="menu-header">' . 'More options' . '</h4>
													<div class="js-menuBuilderTarget"></div>
												</div>
											</div>
										';
	}
	$__compilerTemp15 .= '
									';
	if (strlen(trim($__compilerTemp15)) > 0) {
		$__compilerTemp13 .= '
									<div class="actionBar-set actionBar-set--internal">
									' . $__compilerTemp15 . '
									</div>
								';
	}
	$__compilerTemp13 .= '
							';
	if (strlen(trim($__compilerTemp13)) > 0) {
		$__finalCompiled .= '
						<div class="actionBar">
							' . $__compilerTemp13 . '
						</div>
					';
	}
	$__finalCompiled .= '

					<div class="reactionsBar js-reactionsList ' . ($__vars['item']['reactions'] ? 'is-active' : '') . '">
						' . $__templater->func('reactions', array($__vars['item'], 'showcase/reactions', array())) . '
					</div>

					<div class="js-historyTarget toggleTarget" data-href="trigger-href"></div>
				</article>
			</div>
		</div>
	</div>
</div>

';
	$__compilerTemp16 = '';
	$__compilerTemp16 .= '
			' . $__templater->widgetPosition('xa_sc_item_view_below_item', array(
		'item' => $__vars['item'],
	)) . '	
		';
	if (strlen(trim($__compilerTemp16)) > 0) {
		$__finalCompiled .= '
	<div class="block">
		' . $__compilerTemp16 . '
	</div>	
';
	}
	$__finalCompiled .= '
	
';
	if ($__vars['item']['location'] AND ($__templater->method($__vars['item'], 'canViewItemMap', array()) AND ($__vars['category']['allow_location'] AND (($__vars['xf']['options']['xaScLocationDisplayType'] == 'map_below_content') AND $__vars['xf']['options']['xaScGoogleMapsEmbedApiKey'])))) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Location' . '</h3>
			<div class="block-body block-row contentRow-lesser">
				<p class="mapLocationName"><a href="' . $__templater->func('link', array('misc/location-info', '', array('location' => $__vars['item']['location'], ), ), true) . '" rel="nofollow" target="_blank" class="">' . $__templater->escape($__vars['item']['location']) . '</a></p>
			</div>	
			<div class="block-body block-row">
				<div class="mapContainer">
					<iframe
						width="100%" height="200" frameborder="0" style="border: 0"
						src="https://www.google.com/maps/embed/v1/place?key=' . $__templater->escape($__vars['xf']['options']['xaScGoogleMapsEmbedApiKey']) . '&q=' . $__templater->filter($__vars['item']['location'], array(array('censor', array()),), true) . ($__vars['xf']['options']['xaScLocalizeGoogleMaps'] ? ('&language=' . $__templater->filter($__vars['xf']['language']['language_code'], array(array('substr', array()),), true)) : '') . '">
					</iframe>
				</div>
			</div>	
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp17 = '';
	$__compilerTemp17 .= '
				' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
		'label' => 'Share' . ':',
	), $__vars) . '

				' . '
			';
	if (strlen(trim($__compilerTemp17)) > 0) {
		$__finalCompiled .= '
	<div class="block">
		<div class="blockMessage blockMessage--none">
			' . $__compilerTemp17 . '
		</div>
	</div>	
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['item'], 'canViewUpdates', array()) AND !$__templater->test($__vars['latestUpdates'], 'empty', array())) {
		$__finalCompiled .= '
	';
		if ($__vars['canInlineModUpdates']) {
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

	<div class="block block--messages"
		data-xf-init="' . ($__vars['canInlineModUpdates'] ? 'inline-mod' : '') . '"
		data-type="sc_update"
		data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">

		';
		$__compilerTemp18 = '';
		$__compilerTemp18 .= '
							';
		if ($__vars['canInlineModUpdates']) {
			$__compilerTemp18 .= '
								' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
							';
		}
		$__compilerTemp18 .= '
						';
		if (strlen(trim($__compilerTemp18)) > 0) {
			$__finalCompiled .= '
			<div class="block-outer">
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp18 . '
					</div>
				</div>
			</div>
		';
		}
		$__finalCompiled .= '

		<div class="block-container"
			data-xf-init="lightbox"
			data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
			data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

			<h3 class="block-header"><a href="' . $__templater->func('link', array('showcase/updates', $__vars['item'], ), true) . '">' . 'Latest updates' . '</a></h3>
			<div class="block-body">
			';
		if ($__templater->isTraversable($__vars['latestUpdates'])) {
			foreach ($__vars['latestUpdates'] AS $__vars['update']) {
				$__finalCompiled .= '
				' . $__templater->callMacro('xa_sc_update_macros', 'update', array(
					'update' => $__vars['update'],
					'item' => $__vars['item'],
					'showAttachments' => true,
				), $__vars) . '
			';
			}
		}
		$__finalCompiled .= '
			</div>
			<div class="block-footer">
				<span class="block-footer-controls">' . $__templater->button('Read more' . $__vars['xf']['language']['ellipsis'], array(
			'class' => 'button--link',
			'href' => $__templater->func('link', array('showcase/updates', $__vars['item'], ), false),
		), '', array(
		)) . '</span>
			</div>
		</div>
		
		<div class="block-outer block-outer--after">
			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>		
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['item'], 'canViewReviews', array()) AND !$__templater->test($__vars['latestReviews'], 'empty', array())) {
		$__finalCompiled .= '
	';
		if ($__vars['canInlineModReviews']) {
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

	<div class="block block--messages"
		data-xf-init="' . ($__vars['canInlineModReviews'] ? 'inline-mod' : '') . '"
		data-type="sc_rating"
		data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">

		';
		$__compilerTemp19 = '';
		$__compilerTemp19 .= '
							';
		if ($__vars['canInlineModReviews']) {
			$__compilerTemp19 .= '
								' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
							';
		}
		$__compilerTemp19 .= '
						';
		if (strlen(trim($__compilerTemp19)) > 0) {
			$__finalCompiled .= '
			<div class="block-outer">
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp19 . '
					</div>
				</div>
			</div>
		';
		}
		$__finalCompiled .= '

		<div class="block-container"
			data-xf-init="lightbox"
			data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
			data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

			<h3 class="block-header">' . 'Latest reviews' . '</h3>
			<div class="block-body">
			';
		if ($__templater->isTraversable($__vars['latestReviews'])) {
			foreach ($__vars['latestReviews'] AS $__vars['review']) {
				$__finalCompiled .= '
				' . $__templater->callMacro('xa_sc_review_macros', 'review', array(
					'review' => $__vars['review'],
					'item' => $__vars['item'],
					'showAttachments' => true,
				), $__vars) . '
			';
			}
		}
		$__finalCompiled .= '
			</div>
			<div class="block-footer">
				<span class="block-footer-controls">' . $__templater->button('Read more' . $__vars['xf']['language']['ellipsis'], array(
			'class' => 'button--link',
			'href' => $__templater->func('link', array('showcase/reviews', $__vars['item'], ), false),
		), '', array(
		)) . '</span>
			</div>
		</div>
		
		<div class="block-outer block-outer--after">
			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>		
	</div>
';
	}
	$__finalCompiled .= '

';
	if (($__vars['xf']['options']['xaScMoreInCategoryLocation'] == 'below_item') AND !$__templater->test($__vars['categoryOthers'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header"><a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . 'More in ' . $__templater->escape($__vars['item']['Category']['title']) . '' . '</a></h3>
			<div class="block-body">
				';
		if (($__vars['xf']['options']['xaScMoreInCategoryLayoutType'] == 'item_view') AND $__templater->method($__vars['item'], 'canViewItemAttachments', array())) {
			$__finalCompiled .= '
					';
			if ($__templater->isTraversable($__vars['categoryOthers'])) {
				foreach ($__vars['categoryOthers'] AS $__vars['categoryOther']) {
					$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_item_list_macros', 'item_view_layout', array(
						'allowInlineMod' => false,
						'item' => $__vars['categoryOther'],
					), $__vars) . '
					';
				}
			}
			$__finalCompiled .= '
				';
		} else if (($__vars['xf']['options']['xaScMoreInCategoryLayoutType'] == 'grid_view') AND $__templater->method($__vars['item'], 'canViewItemAttachments', array())) {
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc_grid_view_layout.less');
			$__finalCompiled .= '

					<div class="gridContainerScGridView">		
						<ul class="sc-grid-view">
							';
			if ($__templater->isTraversable($__vars['categoryOthers'])) {
				foreach ($__vars['categoryOthers'] AS $__vars['categoryOther']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'grid_view_layout', array(
						'allowInlineMod' => false,
						'item' => $__vars['categoryOther'],
					), $__vars) . '
							';
				}
			}
			$__finalCompiled .= '
						</ul>
					</div>
				';
		} else if (($__vars['xf']['options']['xaScMoreInCategoryLayoutType'] == 'tile_view') AND $__templater->method($__vars['item'], 'canViewItemAttachments', array())) {
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc_tile_view_layout.less');
			$__finalCompiled .= '

					<div class="gridContainerScTileView">		
						<ul class="sc-tile-view">
							';
			if ($__templater->isTraversable($__vars['categoryOthers'])) {
				foreach ($__vars['categoryOthers'] AS $__vars['categoryOther']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'tile_view_layout', array(
						'allowInlineMod' => false,
						'item' => $__vars['categoryOther'],
					), $__vars) . '
							';
				}
			}
			$__finalCompiled .= '
						</ul>
					</div>							
				';
		} else {
			$__finalCompiled .= '
					<div class="structItemContainer structItemContainerScListView">
						';
			if ($__templater->isTraversable($__vars['categoryOthers'])) {
				foreach ($__vars['categoryOthers'] AS $__vars['categoryOther']) {
					$__finalCompiled .= '
							' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
						'allowInlineMod' => false,
						'item' => $__vars['categoryOther'],
					), $__vars) . '
						';
				}
			}
			$__finalCompiled .= '
					</div>
				';
		}
		$__finalCompiled .= '
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if (($__vars['xf']['options']['xaScMoreFromAuthorLocation'] == 'below_item') AND !$__templater->test($__vars['authorOthers'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header"><a href="' . $__templater->func('link', array('showcase/authors', $__vars['item']['User'], ), true) . '">' . 'More from ' . $__templater->escape($__vars['item']['User']['username']) . '' . '</a></h3>
			<div class="block-body">
				';
		if (($__vars['xf']['options']['xaScMoreFromAuthorLayoutType'] == 'item_view') AND $__templater->method($__vars['item'], 'canViewItemAttachments', array())) {
			$__finalCompiled .= '
					';
			if ($__templater->isTraversable($__vars['authorOthers'])) {
				foreach ($__vars['authorOthers'] AS $__vars['authorOther']) {
					$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_item_list_macros', 'item_view_layout', array(
						'allowInlineMod' => false,
						'item' => $__vars['authorOther'],
					), $__vars) . '
					';
				}
			}
			$__finalCompiled .= '
				';
		} else if (($__vars['xf']['options']['xaScMoreFromAuthorLayoutType'] == 'grid_view') AND $__templater->method($__vars['item'], 'canViewItemAttachments', array())) {
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc_grid_view_layout.less');
			$__finalCompiled .= '

					<div class="gridContainerScGridView">		
						<ul class="sc-grid-view">
							';
			if ($__templater->isTraversable($__vars['authorOthers'])) {
				foreach ($__vars['authorOthers'] AS $__vars['authorOther']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'grid_view_layout', array(
						'allowInlineMod' => false,
						'item' => $__vars['authorOther'],
					), $__vars) . '
							';
				}
			}
			$__finalCompiled .= '
						</ul>
					</div>
				';
		} else if (($__vars['xf']['options']['xaScMoreFromAuthorLayoutType'] == 'tile_view') AND $__templater->method($__vars['item'], 'canViewItemAttachments', array())) {
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc.less');
			$__finalCompiled .= '
					';
			$__templater->includeCss('xa_sc_tile_view_layout.less');
			$__finalCompiled .= '

					<div class="gridContainerScTileView">		
						<ul class="sc-tile-view">
							';
			if ($__templater->isTraversable($__vars['authorOthers'])) {
				foreach ($__vars['authorOthers'] AS $__vars['authorOther']) {
					$__finalCompiled .= '
								' . $__templater->callMacro('xa_sc_item_list_macros', 'tile_view_layout', array(
						'allowInlineMod' => false,
						'item' => $__vars['authorOther'],
					), $__vars) . '
							';
				}
			}
			$__finalCompiled .= '
						</ul>
					</div>							
				';
		} else {
			$__finalCompiled .= '
					<div class="structItemContainer structItemContainerScListView">
						';
			if ($__templater->isTraversable($__vars['authorOthers'])) {
				foreach ($__vars['authorOthers'] AS $__vars['authorOther']) {
					$__finalCompiled .= '
							' . $__templater->callMacro('xa_sc_item_list_macros', 'list_view_layout', array(
						'allowInlineMod' => false,
						'item' => $__vars['authorOther'],
					), $__vars) . '
						';
				}
			}
			$__finalCompiled .= '
					</div>
				';
		}
		$__finalCompiled .= '		
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['item'], 'canViewComments', array())) {
		$__finalCompiled .= '
	<div class="columnContainer"
		data-xf-init="lightbox"
		data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
		data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

		<span class="u-anchorTarget" id="comments"></span>
		
		<div class="columnContainer-comments">
			' . $__templater->callMacro('xa_sc_comment_macros', 'comment_list', array(
			'comments' => $__vars['comments'],
			'attachmentData' => $__vars['attachmentData'],
			'content' => $__vars['item'],
			'linkPrefix' => 'showcase/item-comments',
			'link' => 'showcase',
			'page' => $__vars['page'],
			'perPage' => $__vars['perPage'],
			'totalItems' => $__vars['totalItems'],
			'pageNavHash' => $__vars['pageNavHash'],
			'canInlineMod' => $__vars['canInlineModComments'],
		), $__vars) . '
		</div>
	</div>	
';
	}
	$__finalCompiled .= '

';
	if ($__vars['item']['CoverImage'] AND $__vars['xf']['options']['xaScDisplayCoverImageSidebar']) {
		$__finalCompiled .= '
	';
		$__compilerTemp20 = '';
		if ($__templater->method($__vars['item'], 'canViewItemAttachments', array())) {
			$__compilerTemp20 .= '
					<div class="block-body block-row block-row--minor lbContainer js-itemCIBody"
						data-xf-init="lightbox"
						data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
						data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '">
				
						' . $__templater->callMacro('lightbox_macros', 'setup', array(
				'canViewAttachments' => 'true',
			), $__vars) . '
						<div class="itemCIBody">
							<div class="js-lbContainer">
								<div class="item-container-image js-itemContainerImage">
									<a href="' . $__templater->func('link', array('attachments', $__vars['item']['CoverImage'], ), true) . '" target="_blank" class="js-lbImage">
										<img src="' . $__templater->func('link', array('showcase/cover-image', $__vars['item'], ), true) . '" alt="' . $__templater->escape($__vars['item']['CoverImage']['filename']) . '" class="js-itemImage" />
									</a>
								</div>

								';
			if ($__vars['item']['attach_count'] > 1) {
				$__compilerTemp20 .= '
									';
				$__compilerTemp21 = '';
				$__compilerTemp21 .= '
												';
				if ($__templater->isTraversable($__vars['item']['Attachments'])) {
					foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
						if ($__vars['attachment']['has_thumbnail']) {
							$__compilerTemp21 .= '
													';
							if ($__vars['attachment']['attachment_id'] == $__vars['item']['cover_image_id']) {
								$__compilerTemp21 .= '
													';
							} else {
								$__compilerTemp21 .= '
														' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
									'attachment' => $__vars['attachment'],
									'canView' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
								), $__vars) . '
													';
							}
							$__compilerTemp21 .= '
												';
						}
					}
				}
				$__compilerTemp21 .= '
											';
				if (strlen(trim($__compilerTemp21)) > 0) {
					$__compilerTemp20 .= '
										';
					$__templater->includeCss('attachments.less');
					$__compilerTemp20 .= '
										<ul class="attachmentList itemBody-attachments" style="display:none;">
											' . $__compilerTemp21 . '
										</ul>
									';
				}
				$__compilerTemp20 .= '
								';
			}
			$__compilerTemp20 .= '
							</div>
						</div>
					</div>
				';
		} else {
			$__compilerTemp20 .= '
					<div class="block-body block-row block-row--minor">
						<div style="text-align: center;">
							' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '
						</div>
					</div>
				';
		}
		$__templater->modifySidebarHtml('coverImageSidebar', '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp20 . '		
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ((($__templater->method($__vars['item'], 'hasSections', array()) OR $__templater->method($__vars['item'], 'getExtraFieldTabs', array())) OR !$__templater->test($__vars['itemPages'], 'empty', array())) AND $__templater->method($__vars['item'], 'canViewFullItem', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp22 = '';
		if ($__templater->method($__vars['item'], 'hasSections', array())) {
			$__compilerTemp22 .= ' 
						';
			if ($__vars['item']['message_s2'] AND ($__vars['item']['message_s2'] != '')) {
				$__compilerTemp22 .= '
							';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp22 .= '
								<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 2, ), ), true) . '#section_2" class="blockLink ' . (($__vars['sectionNavId'] == 2) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s2']) . '</a>
							';
				} else {
					$__compilerTemp22 .= '	
								<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 2, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s2']) . '</a>
							';
				}
				$__compilerTemp22 .= '
						';
			}
			$__compilerTemp22 .= '

						';
			if ($__vars['item']['message_s3'] AND ($__vars['item']['message_s3'] != '')) {
				$__compilerTemp22 .= '
							';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp22 .= '
								<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 3, ), ), true) . '#section_3" class="blockLink ' . (($__vars['sectionNavId'] == 3) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s3']) . '</a>
							';
				} else {
					$__compilerTemp22 .= '	
								<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 3, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s3']) . '</a>
							';
				}
				$__compilerTemp22 .= '
						';
			}
			$__compilerTemp22 .= '

						';
			if ($__vars['item']['message_s4'] AND ($__vars['item']['message_s4'] != '')) {
				$__compilerTemp22 .= '
							';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp22 .= '
								<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 4, ), ), true) . '#section_4" class="blockLink ' . (($__vars['sectionNavId'] == 4) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s4']) . '</a>
							';
				} else {
					$__compilerTemp22 .= '	
								<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 4, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s4']) . '</a>
							';
				}
				$__compilerTemp22 .= '
						';
			}
			$__compilerTemp22 .= '

						';
			if ($__vars['item']['message_s5'] AND ($__vars['item']['message_s5'] != '')) {
				$__compilerTemp22 .= '
							';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp22 .= '
								<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 5, ), ), true) . '#section_5" class="blockLink ' . (($__vars['sectionNavId'] == 5) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s5']) . '</a>
							';
				} else {
					$__compilerTemp22 .= '	
								<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 5, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s5']) . '</a>
							';
				}
				$__compilerTemp22 .= '
						';
			}
			$__compilerTemp22 .= '

						';
			if ($__vars['item']['message_s6'] AND ($__vars['item']['message_s6'] != '')) {
				$__compilerTemp22 .= '
							';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp22 .= '
								<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 6, ), ), true) . '#section_6" class="blockLink ' . (($__vars['sectionNavId'] == 6) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['category']['title_s6']) . '</a>
							';
				} else {
					$__compilerTemp22 .= '	
								<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 6, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s6']) . '</a>
							';
				}
				$__compilerTemp22 .= '
						';
			}
			$__compilerTemp22 .= '
					';
		}
		$__compilerTemp23 = '';
		$__compilerTemp24 = $__templater->method($__vars['item'], 'getExtraFieldTabs', array());
		if ($__templater->isTraversable($__compilerTemp24)) {
			foreach ($__compilerTemp24 AS $__vars['_fieldId'] => $__vars['_fieldValue']) {
				$__compilerTemp23 .= '
						<a href="' . $__templater->func('link', array('showcase/field', $__vars['item'], array('field' => $__vars['_fieldId'], ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['_fieldValue']) . '</a>
					';
			}
		}
		$__compilerTemp25 = '';
		if (!$__templater->test($__vars['itemPages'], 'empty', array())) {
			$__compilerTemp25 .= '
						';
			if ($__templater->isTraversable($__vars['itemPages'])) {
				foreach ($__vars['itemPages'] AS $__vars['item_page']) {
					$__compilerTemp25 .= '
							<a href="' . $__templater->func('link', array('showcase/page', $__vars['item_page'], ), true) . '" class="blockLink">
								' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['item_page']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['item_page']['title']) . '</a>
						';
				}
			}
			$__compilerTemp25 .= '

						';
			if ($__vars['xf']['options']['xaScViewFullItem']) {
				$__compilerTemp25 .= '
							<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('full' => 1, ), ), true) . '#section_1" class="blockLink ' . ($__vars['isFullView'] ? 'is-selected' : '') . '">' . 'Full view' . '</a>
						';
			}
			$__compilerTemp25 .= '
					';
		}
		$__templater->modifySidebarHtml('itemTocSidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-header">' . 'Table of contents' . '</h3>
				<div class="block-body">
					<a class="blockLink ' . (($__vars['sectionNavId'] == 1) ? 'is-selected' : '') . '" href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#section_1">' . $__templater->escape($__vars['category']['title_s1']) . '</a>

					' . $__compilerTemp22 . '

					' . $__compilerTemp23 . '

					' . $__compilerTemp25 . '
				</div>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['seriesToc'], 'empty', array()) AND $__templater->method($__vars['item'], 'canViewFullItem', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp26 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp26 .= '
						<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '">' . 'Series' . '</a>
					';
		} else {
			$__compilerTemp26 .= '
						' . 'Series' . '
					';
		}
		$__compilerTemp27 = '';
		if ($__templater->isTraversable($__vars['seriesToc'])) {
			foreach ($__vars['seriesToc'] AS $__vars['seriesToc_part']) {
				$__compilerTemp27 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['seriesToc_part']['Item'], ), true) . '" class="blockLink ' . (($__vars['seriesToc_part']['series_part_id'] == $__vars['item']['series_part_id']) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['seriesToc_part']['Item']['title']) . '</a>
					';
			}
		}
		$__compilerTemp28 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp28 .= '
						<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '" class="blockLink">' . 'View series' . '</a>
					';
		}
		$__templater->modifySidebarHtml('seriesTocSidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-header">
					' . $__compilerTemp26 . '
				</h3>
				<div class="block-body">
					' . $__compilerTemp27 . '

					' . $__compilerTemp28 . '
				</div>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp29 = '';
	if ($__vars['item']['view_count']) {
		$__compilerTemp29 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Views' . '</dt>
						<dd>' . $__templater->filter($__vars['item']['view_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp30 = '';
	if ($__vars['item']['watch_count']) {
		$__compilerTemp30 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Watchers' . '</dt>
						<dd>' . $__templater->filter($__vars['item']['watch_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp31 = '';
	if ($__vars['item']['comment_count']) {
		$__compilerTemp31 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Comments' . '</dt>
						<dd>' . $__templater->filter($__vars['item']['comment_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp32 = '';
	if ($__vars['item']['review_count']) {
		$__compilerTemp32 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Reviews' . '</dt>
						<dd>' . $__templater->filter($__vars['item']['review_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp33 = '';
	if ($__vars['item']['update_count']) {
		$__compilerTemp33 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Updates' . '</dt>
						<dd>' . $__templater->filter($__vars['item']['update_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp34 = '';
	if ($__vars['item']['last_update']) {
		$__compilerTemp34 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Last update' . '</dt>
						<dd>' . $__templater->func('date_dynamic', array($__vars['item']['last_update'], array(
		))) . '</dd>
					</dl>
				';
	}
	$__compilerTemp35 = '';
	if ($__vars['item']['author_rating'] AND $__vars['category']['allow_author_rating']) {
		$__compilerTemp35 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Author rating' . '</dt>
						<dd>
							' . $__templater->callMacro('rating_macros', 'stars', array(
			'rating' => $__vars['item']['author_rating'],
			'class' => 'ratingStars--scAuthorRating',
		), $__vars) . '
						</dd>
					</dl>
				';
	}
	$__compilerTemp36 = '';
	if ($__vars['item']['rating_count'] AND $__vars['item']['rating_avg']) {
		$__compilerTemp36 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Rating' . '</dt>
						<dd>
							' . $__templater->callMacro('rating_macros', 'stars_text', array(
			'rating' => $__vars['item']['rating_avg'],
			'count' => $__vars['item']['rating_count'],
			'rowClass' => 'ratingStarsRow--textBlock',
		), $__vars) . '
						</dd>
					</dl>
				';
	}
	$__compilerTemp37 = '';
	if ($__vars['item']['location'] AND ($__vars['category']['allow_location'] AND (($__vars['xf']['options']['xaScLocationDisplayType'] == 'link') OR (!$__templater->method($__vars['item'], 'canViewItemMap', array()))))) {
		$__compilerTemp37 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Location' . '</dt>
						<dd>
							<a href="' . $__templater->func('link', array('misc/location-info', '', array('location' => $__vars['item']['location'], ), ), true) . '" rel="nofollow" target="_blank" class="">' . $__templater->escape($__vars['item']['location']) . '</a>
						</dd>
					</dl>
				';
	}
	$__templater->modifySidebarHtml('infoSidebar', '
	<div class="block">
		<div class="block-container">
			<h3 class="block-minorHeader">' . ($__vars['item']['Category']['content_term'] ? '' . $__templater->escape($__vars['item']['Category']['content_term']) . ' information' : 'Item information') . '</h3>
			<div class="block-body block-row block-row--minor">
				<dl class="pairs pairs--justified">
					<dt>' . 'Category' . '</dt>
					<dd><a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a></dd>
				</dl>
				<dl class="pairs pairs--justified">
					<dt>' . 'Added by' . '</dt>
					<dd>' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
		'defaultname' => $__vars['item']['username'],
	))) . '</dd>
				</dl>
				' . $__compilerTemp29 . '
				' . $__compilerTemp30 . '				
				' . $__compilerTemp31 . '
				' . $__compilerTemp32 . '
				' . $__compilerTemp33 . '
				' . $__compilerTemp34 . '
				' . $__compilerTemp35 . '
				' . $__compilerTemp36 . '

				' . $__compilerTemp37 . '
			</div>
		</div>
	</div>
', 'replace');
	$__finalCompiled .= '

';
	if ($__vars['item']['business_hours']) {
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('businessHours', '
		' . $__templater->callMacro('xa_sc_item_view_macros', 'business_hours', array(
			'item' => $__vars['item'],
			'category' => $__vars['category'],
		), $__vars) . '
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['item'], 'canViewContributors', array()) AND !$__templater->test($__vars['contributors'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp38 = '';
		$__compilerTemp39 = '';
		$__compilerTemp39 .= '
							';
		if ($__templater->isTraversable($__vars['contributors'])) {
			foreach ($__vars['contributors'] AS $__vars['contributor']) {
				if (!$__vars['contributor']['is_co_owner']) {
					$__compilerTemp39 .= '
								<li>
									<div class="contentRow">
										<div class="contentRow-figure">
											' . $__templater->func('avatar', array($__vars['contributor']['User'], 'xxs', false, array(
					))) . '
										</div>

										<div class="contentRow-main contentRow-main--close">
											' . $__templater->func('username_link', array($__vars['contributor']['User'], true, array(
					))) . '
											<div class="contentRow-minor">
												' . $__templater->func('user_title', array($__vars['contributor']['User'], false, array(
					))) . '
											</div>
										</div>
									</div>
								</li>
							';
				}
			}
		}
		$__compilerTemp39 .= '
						';
		if (strlen(trim($__compilerTemp39)) > 0) {
			$__compilerTemp38 .= '
			<div class="block">
				<div class="block-container">
					<h3 class="block-minorHeader">
						';
			if ($__templater->method($__vars['item'], 'canManageContributors', array())) {
				$__compilerTemp38 .= '
							<a href="' . $__templater->func('link', array('showcase/manage-contributors', $__vars['item'], ), true) . '" data-xf-click="overlay">
								' . 'Contributors' . '
							</a>
						';
			} else {
				$__compilerTemp38 .= '
							' . 'Contributors' . '
						';
			}
			$__compilerTemp38 .= '
					</h3>
					<div class="block-body block-row block-row--minor">
						<ul class="itemSidebarList">
						' . $__compilerTemp39 . '
						</ul>
					</div>	
				</div>
			</div>
		';
		}
		$__templater->modifySidebarHtml('itemContributors', '	
		' . $__compilerTemp38 . '
	', 'replace');
		$__finalCompiled .= '	
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['item'], 'canViewFullItem', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp40 = '';
		$__compilerTemp41 = '';
		$__compilerTemp41 .= '
							' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'sidebar',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => '',
			'valueClass' => 'pairs pairs--justified',
		), $__vars) . '
						';
		if (strlen(trim($__compilerTemp41)) > 0) {
			$__compilerTemp40 .= '
			<div class="block">
				<div class="block-container">
					<h3 class="block-minorHeader">' . 'Additional information' . '</h3>
					<div class="block-body block-row block-row--minor">
						' . $__compilerTemp41 . '
					</div>	
				</div>
			</div>
		';
		}
		$__templater->modifySidebarHtml('additionalInfoSidebar', '
		' . $__compilerTemp40 . '
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['item'], 'canViewFullItem', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp42 = $__templater->method($__vars['item'], 'getExtraFieldSidebarBlocks', array());
		if ($__templater->isTraversable($__compilerTemp42)) {
			foreach ($__compilerTemp42 AS $__vars['fieldId'] => $__vars['fieldValue']) {
				$__finalCompiled .= '
		';
				$__templater->modifySidebarHtml('customFieldOwnBlockSidebar-' . $__templater->escape($__vars['fieldId']), '
			<div class="block">
				<div class="block-container">
					<h3 class="block-minorHeader">' . $__templater->escape($__vars['fieldValue']) . '</h3>
					<div class="block-body block-row block-row--minor">

					' . $__templater->callMacro('custom_fields_macros', 'custom_field_value', array(
					'definition' => $__templater->method($__vars['item']->{'custom_fields'}, 'getDefinition', array($__vars['fieldId'], )),
					'value' => $__templater->method($__vars['item']->{'custom_fields'}, 'getFieldValue', array($__vars['fieldId'], )),
				), $__vars) . '
					</div>	
				</div>
			</div>
		', 'replace');
				$__finalCompiled .= '
	';
			}
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp43 = '';
	$__compilerTemp44 = '';
	$__compilerTemp44 .= '
					';
	if ($__templater->method($__vars['item'], 'hasViewableDiscussion', array())) {
		$__compilerTemp44 .= '
						' . $__templater->button('Join discussion', array(
			'href' => $__templater->func('link', array('threads', $__vars['item']['Discussion'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp44 .= '
				';
	if (strlen(trim($__compilerTemp44)) > 0) {
		$__compilerTemp43 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp44 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('discussionButtonSidebar', '
	' . $__compilerTemp43 . '	
', 'replace');
	$__finalCompiled .= '

';
	if ($__vars['item']['location'] AND ($__templater->method($__vars['item'], 'canViewItemMap', array()) AND ($__vars['category']['allow_location'] AND (($__vars['xf']['options']['xaScLocationDisplayType'] == 'map') AND $__vars['xf']['options']['xaScGoogleMapsEmbedApiKey'])))) {
		$__finalCompiled .= '
	';
		$__templater->modifySidebarHtml('locationSidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader">' . 'Location' . '</h3>
				<div class="block-body block-row contentRow-lesser">
					<p class="mapLocationName"><a href="' . $__templater->func('link', array('misc/location-info', '', array('location' => $__vars['item']['location'], ), ), true) . '" rel="nofollow" target="_blank" class="">' . $__templater->escape($__vars['item']['location']) . '</a></p>
				</div>	
				<div class="block-body block-row">
					<div class="mapContainer">
						<iframe
							width="100%" height="200" frameborder="0" style="border: 0"
							src="https://www.google.com/maps/embed/v1/place?key=' . $__templater->escape($__vars['xf']['options']['xaScGoogleMapsEmbedApiKey']) . '&q=' . $__templater->filter($__vars['item']['location'], array(array('censor', array()),), true) . ($__vars['xf']['options']['xaScLocalizeGoogleMaps'] ? ('&language=' . $__templater->filter($__vars['xf']['language']['language_code'], array(array('substr', array()),), true)) : '') . '">
						</iframe>
					</div>
				</div>	
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if (($__vars['xf']['options']['xaScFilesLocation'] == 'sidebar') AND $__vars['item']['attach_count']) {
		$__finalCompiled .= '
	';
		$__compilerTemp45 = '';
		$__compilerTemp46 = '';
		$__compilerTemp46 .= '
								';
		if ($__templater->isTraversable($__vars['item']['Attachments'])) {
			foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
				$__compilerTemp46 .= '
									';
				if ($__vars['attachment']['has_thumbnail'] OR $__vars['attachment']['is_video']) {
					$__compilerTemp46 .= '
										' . '
									';
				} else {
					$__compilerTemp46 .= '
										' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
					), $__vars) . '
									';
				}
				$__compilerTemp46 .= '
								';
			}
		}
		$__compilerTemp46 .= '
							';
		if (strlen(trim($__compilerTemp46)) > 0) {
			$__compilerTemp45 .= '
			<div class="block">
				<div class="block-container">
					<h3 class="block-minorHeader">' . 'Downloads' . '</h3>
					';
			$__templater->includeCss('attachments.less');
			$__compilerTemp45 .= '
					<div class="block-body block-row">
						<ul class="attachmentList itemBody-attachments">
							' . $__compilerTemp46 . '
						</ul>
					</div>
				</div>
			</div>
		';
		}
		$__templater->modifySidebarHtml('fileAttachmentsSidebar', '
		' . $__compilerTemp45 . '
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if (($__vars['xf']['options']['xaScMoreInCategoryLocation'] == 'sidebar') AND !$__templater->test($__vars['categoryOthers'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp47 = '';
		if ($__templater->isTraversable($__vars['categoryOthers'])) {
			foreach ($__vars['categoryOthers'] AS $__vars['categoryOther']) {
				$__compilerTemp47 .= '
						<li>
							' . $__templater->callMacro('xa_sc_item_list_macros', 'item_simple', array(
					'item' => $__vars['categoryOther'],
					'withMeta' => false,
				), $__vars) . '
						</li>
					';
			}
		}
		$__templater->modifySidebarHtml('moreItemsInCategorySidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader"><a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . 'More in ' . $__templater->escape($__vars['item']['Category']['title']) . '' . '</a></h3>
				<div class="block-body block-row">
					<ul class="itemSidebarList">
					' . $__compilerTemp47 . '
					</ul>
				</div>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if (($__vars['xf']['options']['xaScMoreFromAuthorLocation'] == 'sidebar') AND !$__templater->test($__vars['authorOthers'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp48 = '';
		if ($__templater->isTraversable($__vars['authorOthers'])) {
			foreach ($__vars['authorOthers'] AS $__vars['authorOther']) {
				$__compilerTemp48 .= '
						<li>
							' . $__templater->callMacro('xa_sc_item_list_macros', 'item_simple', array(
					'item' => $__vars['authorOther'],
					'withMeta' => false,
				), $__vars) . '
						</li>
					';
			}
		}
		$__templater->modifySidebarHtml('moreItemsFromAuthorSidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader"><a href="' . $__templater->func('link', array('showcase/authors', $__vars['item']['User'], ), true) . '">' . 'More from ' . $__templater->escape($__vars['item']['User']['username']) . '' . '</a></h3>
				<div class="block-body block-row">
					<ul class="itemSidebarList">
					' . $__compilerTemp48 . '
					</ul>
				</div>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp49 = '';
	$__compilerTemp50 = '';
	$__compilerTemp50 .= '
					<h3 class="block-minorHeader">' . ($__vars['item']['Category']['content_term'] ? 'Share this ' . $__templater->filter($__vars['item']['Category']['content_term'], array(array('to_lower', array()),), true) . '' : 'Share this item') . '</h3>
					';
	$__compilerTemp51 = '';
	$__compilerTemp51 .= '
								' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp51)) > 0) {
		$__compilerTemp50 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp51 . '
						</div>
					';
	}
	$__compilerTemp50 .= '
					';
	$__compilerTemp52 = '';
	$__compilerTemp52 .= '
								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy URL BB code',
		'text' => '[URL="' . $__templater->func('link', array('canonical:showcase', $__vars['item'], ), false) . '"]' . $__vars['item']['title'] . '[/URL]',
	), $__vars) . '

								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy SHOWCASE BB code',
		'text' => '[SHOWCASE=item, ' . $__vars['item']['item_id'] . '][/SHOWCASE]',
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp52)) > 0) {
		$__compilerTemp50 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp52 . '
						</div>
					';
	}
	$__compilerTemp50 .= '
				';
	if (strlen(trim($__compilerTemp50)) > 0) {
		$__compilerTemp49 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp50 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('shareSidebar', '
	' . $__compilerTemp49 . '
', 'replace');
	return $__finalCompiled;
}
);