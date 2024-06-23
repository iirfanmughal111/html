<?php
// FROM HASH: 390c8943ce859bbf50947092d572c4b7
return array(
'macros' => array('header' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'titleHtml' => null,
		'showMeta' => true,
		'metaHtml' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	if ($__vars['item']['CoverImage'] AND $__vars['xf']['options']['xaScDisplayCoverImageHeader']) {
		$__compilerTemp1 .= '
			<div class="scCoverImage scDisplayAboveItemNarrow">
				<div class="scCoverImage-container">
					<div class="scCoverImage-container-image js-coverImageContainerImage">
						';
		if ($__templater->method($__vars['item'], 'canViewItemAttachments', array())) {
			$__compilerTemp1 .= '
							<img src="' . $__templater->func('link', array('showcase/cover-image', $__vars['item'], ), true) . '" alt="' . $__templater->escape($__vars['item']['CoverImage']['filename']) . '" class="js-itemCoverImage" />
						';
		} else {
			$__compilerTemp1 .= '
							<div style="text-align: center;">
								' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '
							</div>
						';
		}
		$__compilerTemp1 .= '
					</div>
				</div>
			</div>
		';
	}
	$__compilerTemp2 = '';
	if ($__vars['xf']['options']['xaScDisplayCoverImageHeader'] AND ((($__vars['item']['CoverImage'] OR $__vars['item']['Category']['content_image_url'])) AND $__templater->method($__vars['item'], 'canViewItemAttachments', array()))) {
		$__compilerTemp2 .= '
				<span class="contentRow-figure scHeader-figure">
					';
		if ($__vars['item']['CoverImage']) {
			$__compilerTemp2 .= '
						<div class="block-body block-row block-row--minor lbContainer js-itemCIBody"
							data-xf-init="lightbox"
							data-message-selector=".js-item-header"
							data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
							data-lb-universal="' . false . '"
							data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '">
				
							' . $__templater->callMacro('lightbox_macros', 'setup', array(
				'canViewAttachments' => 'true',
			), $__vars) . '
							<div class="itemCIBody js-item-header"		
								data-author="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . '"
								data-content="item-header-' . $__templater->escape($__vars['item']['item_id']) . '"
								id="js-item-header-' . $__templater->escape($__vars['item']['item_id']) . '">

								<div class="lbContainer js-lbContainer"
									data-lb-id="item-header-' . $__templater->escape($__vars['item']['item_id']) . '"
									data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '">

									<div class="item-container-image js-itemContainerImage">
										<a href="' . $__templater->func('link', array('attachments', $__vars['item']['CoverImage'], ), true) . '" target="_blank" class="js-lbImage">
											<img src="' . $__templater->func('link', array('showcase/cover-image', $__vars['item'], ), true) . '" alt="' . $__templater->escape($__vars['item']['CoverImage']['filename']) . '" class="js-itemImage" />
										</a>
									</div>

									';
			if ($__vars['item']['attach_count'] > 1) {
				$__compilerTemp2 .= '
										';
				$__compilerTemp3 = '';
				$__compilerTemp3 .= '
													';
				if ($__templater->isTraversable($__vars['item']['Attachments'])) {
					foreach ($__vars['item']['Attachments'] AS $__vars['attachment']) {
						if ($__vars['attachment']['has_thumbnail']) {
							$__compilerTemp3 .= '
														';
							if ($__vars['attachment']['attachment_id'] == $__vars['item']['cover_image_id']) {
								$__compilerTemp3 .= '
															' . '
														';
							} else {
								$__compilerTemp3 .= '
															' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
									'attachment' => $__vars['attachment'],
									'canView' => $__templater->method($__vars['item'], 'canViewItemAttachments', array()),
								), $__vars) . '
														';
							}
							$__compilerTemp3 .= '
													';
						}
					}
				}
				$__compilerTemp3 .= '
												';
				if (strlen(trim($__compilerTemp3)) > 0) {
					$__compilerTemp2 .= '
											';
					$__templater->includeCss('attachments.less');
					$__compilerTemp2 .= '
											<ul class="attachmentList itemBody-attachments" style="display:none;">
												' . $__compilerTemp3 . '
											</ul>
										';
				}
				$__compilerTemp2 .= '
									';
			}
			$__compilerTemp2 .= '
								</div>
							</div>
						</div>
					';
		} else if ($__vars['item']['Category']['content_image_url']) {
			$__compilerTemp2 .= '
						' . $__templater->func('sc_category_icon', array($__vars['item'], ), true) . '
					';
		}
		$__compilerTemp2 .= '
				</span>
			';
	} else if ($__vars['xf']['options']['xaScDisplayCoverImageHeader'] AND (($__vars['item']['CoverImage'] OR $__vars['item']['Category']['content_image_url']))) {
		$__compilerTemp2 .= '
				<span class="contentRow-figure scHeader-figure">
					';
		if ($__vars['item']['CoverImage']) {
			$__compilerTemp2 .= '
						' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '
					';
		} else if ($__vars['item']['Category']['content_image_url']) {
			$__compilerTemp2 .= '
						' . $__templater->func('sc_category_icon', array($__vars['item'], ), true) . '
					';
		}
		$__compilerTemp2 .= '
				</span>
			';
	}
	$__compilerTemp4 = '';
	if ($__vars['titleHtml'] !== null) {
		$__compilerTemp4 .= '
							' . $__templater->filter($__vars['titleHtml'], array(array('raw', array()),), true) . '
						';
	} else {
		$__compilerTemp4 .= '
							';
		if ($__vars['item']['item_state'] == 'draft') {
			$__compilerTemp4 .= '  
								<span style="color: red;">[' . 'Draft' . ']</span> 
							';
		} else if ($__vars['item']['item_state'] == 'awaiting') {
			$__compilerTemp4 .= ' 
								<span style="color: orange;">[' . 'Awaiting' . ']</span> 
							';
		}
		$__compilerTemp4 .= '
							' . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true) . $__templater->escape($__vars['item']['title']) . '
						';
	}
	$__compilerTemp5 = '';
	if ($__vars['showMeta']) {
		$__compilerTemp5 .= '
					<div class="p-description">
						';
		if ($__vars['metaHtml'] !== null) {
			$__compilerTemp5 .= '
							' . $__templater->filter($__vars['metaHtml'], array(array('raw', array()),), true) . '
						';
		} else {
			$__compilerTemp5 .= '
							<ul class="listInline listInline--bullet">
								<li>
									' . $__templater->fontAwesome('fa-user', array(
				'aria-hidden' => 'true',
				'title' => $__templater->filter('Added by', array(array('for_attr', array()),), false),
			)) . '
									<span class="u-srOnly">' . 'Added by' . '</span>

									' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
				'defaultname' => $__vars['item']['username'],
				'class' => 'u-concealed',
			))) . '
								</li>
								';
			if (!$__templater->test($__vars['item']['Contributors'], 'empty', array())) {
				$__compilerTemp5 .= '
									';
				if ($__templater->isTraversable($__vars['item']['Contributors'])) {
					foreach ($__vars['item']['Contributors'] AS $__vars['coOwner']) {
						if ($__vars['coOwner']['is_co_owner']) {
							$__compilerTemp5 .= '
										<li>
											' . $__templater->fontAwesome('fa-user', array(
								'title' => $__templater->filter('Co-owner', array(array('for_attr', array()),), false),
							)) . '
											<span class="u-srOnly">' . 'Co-owner' . '</span>
											' . $__templater->func('username_link', array($__vars['coOwner']['User'], false, array(
								'defaultname' => $__vars['coOwner']['User']['username'],
								'class' => 'u-concealed',
							))) . '
										</li>
									';
						}
					}
				}
				$__compilerTemp5 .= '
								';
			}
			$__compilerTemp5 .= '
								<li>
									' . $__templater->fontAwesome('fa-clock', array(
				'title' => $__templater->filter('Create date', array(array('for_attr', array()),), false),
			)) . '
									<span class="u-srOnly">' . 'Create date' . '</span>

									<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
			))) . '</a>
								</li>
								';
			if ($__vars['item']['last_update'] > $__vars['item']['create_date']) {
				$__compilerTemp5 .= '								
									<li>
										' . $__templater->fontAwesome('fa-clock', array(
					'title' => $__templater->filter('Last update', array(array('for_attr', array()),), false),
				)) . '
										<span class="u-concealed">' . 'Updated' . '</span>

										' . $__templater->func('date_dynamic', array($__vars['item']['last_update'], array(
				))) . '
									</li>
								';
			}
			$__compilerTemp5 .= '
								';
			if ($__vars['xf']['options']['enableTagging'] AND (($__templater->method($__vars['item'], 'canEditTags', array()) OR $__vars['item']['tags']))) {
				$__compilerTemp5 .= '
									<li>
										' . $__templater->callMacro('tag_macros', 'list', array(
					'tags' => $__vars['item']['tags'],
					'tagList' => 'tagList--item-' . $__vars['item']['item_id'],
					'editLink' => ($__templater->method($__vars['item'], 'canEditTags', array()) ? $__templater->func('link', array('showcase/tags', $__vars['item'], ), false) : ''),
				), $__vars) . '
									</li>
								';
			}
			$__compilerTemp5 .= '									
								';
			if ($__vars['item']['Featured']) {
				$__compilerTemp5 .= '
									<li><span class="label label--accent">' . 'Featured' . '</span></li>
								';
			}
			$__compilerTemp5 .= '
							</ul>
						';
		}
		$__compilerTemp5 .= '
					</div>
				';
	}
	$__compilerTemp6 = '';
	if (($__vars['item']['description'] != '') AND $__vars['xf']['options']['xaScDisplayDescriptionHeader']) {
		$__compilerTemp6 .= '
					<div class="p-description">
						' . $__templater->func('snippet', array($__vars['item']['description'], 255, array('stripBbCode' => true, ), ), true) . '
					</div>
				';
	}
	$__compilerTemp7 = '';
	if ($__templater->method($__vars['item'], 'canViewFullItem', array())) {
		$__compilerTemp7 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
			'type' => 'sc_items',
			'group' => 'header',
			'onlyInclude' => $__vars['category']['field_cache'],
			'set' => $__vars['item']['custom_fields'],
			'wrapperClass' => 'itemBody-fields itemBody-fields--header',
		), $__vars) . '
				';
	}
	$__templater->setPageParam('headerHtml', '
		' . $__compilerTemp1 . '

		<div class="contentRow contentRow--hideFigureNarrow">
			' . $__compilerTemp2 . '

			<div class="contentRow-main">
				<div class="p-title">
					<h1 class="p-title-value">
						' . $__compilerTemp4 . '
					</h1>
				</div>
				' . $__compilerTemp5 . '

				' . $__compilerTemp6 . '

				' . $__compilerTemp7 . '
			</div>
		</div>
	');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'status' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				';
	if ($__vars['item']['item_state'] == 'deleted') {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--deleted">
						' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['item']['DeletionLog'],
		), $__vars) . '
					</dd>
				';
	} else if ($__vars['item']['item_state'] == 'moderated') {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--moderated">
						' . 'Awaiting approval before being displayed publicly.' . '
					</dd>
				';
	} else if ($__vars['item']['item_state'] == 'draft') {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--warning">
						' . 'Draft item awaiting publishing before being displayed publicly! ' . '
					</dd>
				';
	} else if ($__vars['item']['item_state'] == 'awaiting') {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--warning">
						' . 'Scheduled for publishing' . ' ' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
		))) . '
					</dd>
				';
	}
	$__compilerTemp1 .= '
				';
	if ($__vars['item']['warning_message']) {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--warning">
						' . $__templater->escape($__vars['item']['warning_message']) . '
					</dd>
				';
	}
	$__compilerTemp1 .= '
				';
	if ($__templater->method($__vars['item'], 'isIgnored', array())) {
		$__compilerTemp1 .= '
					<dd class="blockStatus-message blockStatus-message--ignored">
						' . 'You are ignoring content by this member.' . '
					</dd>
				';
	}
	$__compilerTemp1 .= '
			';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<dl class="blockStatus blockStatus--standalone">
			<dt>' . 'Status' . '</dt>
			' . $__compilerTemp1 . '
		</dl>
	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->method($__vars['item'], 'isInSeries', array(true, )) AND $__vars['item']['SeriesPart']) {
		$__finalCompiled .= '
		<dl class="blockStatus blockStatus--info blockStatus--standalone">
			<dt></dt>
			<dd class="blockStatus-message">
				';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__finalCompiled .= '
					' . 'This item is in the series ' . (((('<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['item']['SeriesPart']['Series']['title'])) . '</a>') . '' . '
				';
		} else {
			$__finalCompiled .= '
					' . 'This item is in the series ' . (('"' . $__templater->escape($__vars['item']['SeriesPart']['Series']['title'])) . '"') . '' . '
				';
		}
		$__finalCompiled .= '
			</dd>
		</dl>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'tabs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'selected' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						<!--[SC:item_tabs:after_overivew]-->						
						';
	if (($__vars['xf']['options']['xaScSectionsDisplayType'] == 'tabbed') AND $__templater->method($__vars['item'], 'canViewFullItem', array())) {
		$__compilerTemp1 .= '
							';
		if ($__templater->method($__vars['item'], 'canViewSection', array('s2', ))) {
			$__compilerTemp1 .= '
								<a class="tabs-tab ' . (($__vars['selected'] == 'section_2') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 2, ), ), true) . '">' . $__templater->escape($__vars['item']['Category']['title_s2']) . '</a>
							';
		}
		$__compilerTemp1 .= '
							';
		if ($__templater->method($__vars['item'], 'canViewSection', array('s3', ))) {
			$__compilerTemp1 .= '
								<a class="tabs-tab ' . (($__vars['selected'] == 'section_3') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 3, ), ), true) . '">' . $__templater->escape($__vars['item']['Category']['title_s3']) . '</a>
							';
		}
		$__compilerTemp1 .= '
							';
		if ($__templater->method($__vars['item'], 'canViewSection', array('s4', ))) {
			$__compilerTemp1 .= '
								<a class="tabs-tab ' . (($__vars['selected'] == 'section_4') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 4, ), ), true) . '">' . $__templater->escape($__vars['item']['Category']['title_s4']) . '</a>
							';
		}
		$__compilerTemp1 .= '
							';
		if ($__templater->method($__vars['item'], 'canViewSection', array('s5', ))) {
			$__compilerTemp1 .= '
								<a class="tabs-tab ' . (($__vars['selected'] == 'section_5') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 5, ), ), true) . '">' . $__templater->escape($__vars['item']['Category']['title_s5']) . '</a>
							';
		}
		$__compilerTemp1 .= '
							';
		if ($__templater->method($__vars['item'], 'canViewSection', array('s6', ))) {
			$__compilerTemp1 .= '
								<a class="tabs-tab ' . (($__vars['selected'] == 'section_6') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 6, ), ), true) . '">' . $__templater->escape($__vars['item']['Category']['title_s6']) . '</a>
							';
		}
		$__compilerTemp1 .= '
						';
	}
	$__compilerTemp1 .= '
						<!--[SC:item_tabs:after_sections]-->
						';
	if ($__templater->method($__vars['item'], 'canViewFullItem', array())) {
		$__compilerTemp1 .= '
							';
		$__compilerTemp2 = $__templater->method($__vars['item'], 'getExtraFieldTabs', array());
		if ($__templater->isTraversable($__compilerTemp2)) {
			foreach ($__compilerTemp2 AS $__vars['fieldId'] => $__vars['fieldValue']) {
				$__compilerTemp1 .= '
								<a class="tabs-tab ' . (($__vars['selected'] == (('field_' . $__vars['fieldId']))) ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase/field', $__vars['item'], array('field' => $__vars['fieldId'], ), ), true) . '">' . $__templater->escape($__vars['fieldValue']) . '</a>
							';
			}
		}
		$__compilerTemp1 .= '
						';
	}
	$__compilerTemp1 .= '
						<!--[SC:item_tabs:after_custom_fields]-->						
						';
	if ($__vars['item']['real_update_count'] AND $__templater->method($__vars['item'], 'canViewUpdates', array())) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'updates') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase/updates', $__vars['item'], ), true) . '">' . 'Updates' . ' ' . $__templater->filter($__vars['item']['real_update_count'], array(array('parens', array()),), true) . '</a>
						';
	}
	$__compilerTemp1 .= '
						<!--[SC:item_tabs:after_updates]-->						
						';
	if ($__vars['item']['real_review_count'] AND $__templater->method($__vars['item'], 'canViewReviews', array())) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'reviews') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], ), true) . '">' . 'Reviews' . ' ' . $__templater->filter($__vars['item']['real_review_count'], array(array('parens', array()),), true) . '</a>
						';
	}
	$__compilerTemp1 .= '
						<!--[SC:item_tabs:after_reviews]-->						
						';
	if ($__vars['item']['location'] AND ($__templater->method($__vars['item'], 'canViewItemMap', array()) AND ($__vars['item']['Category']['allow_location'] AND (($__vars['xf']['options']['xaScLocationDisplayType'] == 'map_own_tab') AND $__vars['xf']['options']['xaScGoogleMapsEmbedApiKey'])))) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'map') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase/map', $__vars['item'], ), true) . '">' . 'Map' . '</a>
						';
	}
	$__compilerTemp1 .= '
						<!--[SC:item_tabs:after_map]-->						
						';
	if (($__vars['xf']['options']['xaScGalleryLocation'] == 'own_tab') AND $__templater->method($__vars['item'], 'hasImageAttachments', array($__vars['item'], ))) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'gallery') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase/gallery', $__vars['item'], ), true) . '">' . 'Gallery' . '</a>
						';
	}
	$__compilerTemp1 .= '
						<!--[SC:item_tabs:after_gallery]-->
						';
	if ($__templater->method($__vars['item'], 'hasViewableDiscussion', array())) {
		$__compilerTemp1 .= '
							<a class="tabs-tab ' . (($__vars['selected'] == 'discussion') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('threads', $__vars['item']['Discussion'], ), true) . '">' . 'Discussion' . ' ' . '</a>
						';
	}
	$__compilerTemp1 .= '
						<!--[SC:item_tabs:end]-->						
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="tabs tabs--standalone">
			<div class="hScroller" data-xf-init="h-scroller">
				<span class="hScroller-scroll">
					<a class="tabs-tab ' . (($__vars['selected'] == 'overview') ? 'is-active' : '') . '" href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">' . 'Overview' . '</a>
					' . $__compilerTemp1 . '
				</span>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'action_buttons' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'section' => null,
		'sectionTitle' => null,
		'itemField' => null,
		'itemPages' => null,
		'itemPage' => null,
		'isFullView' => false,
		'seriesToc' => null,
		'showTableOfContents' => false,
		'showPostAnUpdateButton' => false,
		'showRateButton' => false,
		'showAddPageButton' => false,
		'showAddToSeriesButton' => false,
		'canInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__vars['showPostAnUpdateButton'] AND $__templater->method($__vars['item'], 'canAddUpdate', array())) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Post an update' . '
		', array(
			'href' => $__templater->func('link', array('showcase/add-update', $__vars['item'], ), false),
			'class' => 'button--scAddUpdate',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['showRateButton'] AND (($__templater->method($__vars['item'], 'canRate', array()) OR $__templater->method($__vars['item'], 'canRatePreReg', array())))) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Leave a rating' . '
		', array(
			'href' => $__templater->func('link', array('showcase/rate', $__vars['item'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->method($__vars['item'], 'canAddPage', array()) AND $__vars['showAddPageButton']) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Add page' . '
		', array(
			'href' => $__templater->func('link', array('showcase/add-page', $__vars['item'], ), false),
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->method($__vars['item'], 'canAddItemToSeries', array()) AND $__vars['showAddToSeriesButton']) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Add to series' . '
		', array(
			'href' => $__templater->func('link', array('showcase/add-to-series', $__vars['item'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '
	
	';
	if ($__templater->method($__vars['item'], 'canJoinContributorsTeam', array())) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Contribute' . '
		', array(
			'href' => $__templater->func('link', array('showcase/join-contributors-team', $__vars['item'], ), false),
			'class' => 'button--cta',
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '	

	';
	if ($__templater->method($__vars['item'], 'canPublishDraft', array()) AND ((($__vars['item']['item_state'] == 'draft') OR ($__vars['item']['item_state'] == 'awaiting')))) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Publish now' . '
		', array(
			'href' => $__templater->func('link', array('showcase/publish-draft', $__vars['item'], ), false),
			'class' => 'button--cta',
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '

	';
	if ($__templater->method($__vars['item'], 'canPublishDraftScheduled', array()) AND ($__vars['item']['item_state'] == 'draft')) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Publish scheduled' . '
		', array(
			'href' => $__templater->func('link', array('showcase/publish-draft-scheduled', $__vars['item'], ), false),
			'class' => 'button--cta',
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '
	
	';
	if ($__templater->method($__vars['item'], 'canChangeScheduledPublishDate', array()) AND ($__vars['item']['item_state'] == 'awaiting')) {
		$__finalCompiled .= '
		' . $__templater->button('
			' . 'Change scheduled date' . '
		', array(
			'href' => $__templater->func('link', array('showcase/change-scheduled-publish-date', $__vars['item'], ), false),
			'class' => 'button--cta',
			'overlay' => 'true',
		), '', array(
		)) . '
	';
	}
	$__finalCompiled .= '	

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			';
	if ($__vars['canInlineMod']) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
			';
	}
	$__compilerTemp1 .= '

			';
	if ($__templater->method($__vars['item'], 'canUndelete', array()) AND ($__vars['item']['item_state'] == 'deleted')) {
		$__compilerTemp1 .= '
				' . $__templater->button('
					' . 'Undelete' . '
				', array(
			'href' => $__templater->func('link', array('showcase/undelete', $__vars['item'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
			';
	}
	$__compilerTemp1 .= '
			';
	if ($__templater->method($__vars['item'], 'canApproveUnapprove', array()) AND ($__vars['item']['item_state'] == 'moderated')) {
		$__compilerTemp1 .= '
				' . $__templater->button('
					' . 'Approve' . '
				', array(
			'href' => $__templater->func('link', array('showcase/approve', $__vars['item'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
			';
	}
	$__compilerTemp1 .= '
			';
	if ($__templater->method($__vars['item'], 'canWatch', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = '';
		if ($__vars['item']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp2 .= '
						' . 'Unwatch' . '
					';
		} else {
			$__compilerTemp2 .= '
						' . 'Watch' . '
					';
		}
		$__compilerTemp1 .= $__templater->button('

					' . $__compilerTemp2 . '
				', array(
			'href' => $__templater->func('link', array('showcase/watch', $__vars['item'], ), false),
			'class' => 'button--link',
			'data-xf-click' => 'switch-overlay',
			'data-sk-watch' => 'Watch',
			'data-sk-unwatch' => 'Unwatch',
		), '', array(
		)) . '
			';
	}
	$__compilerTemp1 .= '
			' . $__templater->callMacro('bookmark_macros', 'button', array(
		'content' => $__vars['item'],
		'confirmUrl' => $__templater->func('link', array('showcase/bookmark', $__vars['item'], ), false),
	), $__vars) . '
			
			';
	if ($__vars['showTableOfContents'] AND ((($__templater->method($__vars['item'], 'hasSections', array()) OR $__templater->method($__vars['item'], 'getExtraFieldTabs', array())) OR !$__templater->test($__vars['itemPages'], 'empty', array())))) {
		$__compilerTemp1 .= '
				<div class="buttonGroup-buttonWrapper">
					' . $__templater->button('
						' . 'Table of contents' . '
					', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'title' => $__templater->filter('Table of contents', array(array('for_attr', array()),), false),
		), '', array(
		)) . '
					<div class="menu" data-menu="menu" aria-hidden="true">
						<div class="menu-content">
							<h4 class="menu-header">' . 'Table of contents' . '</h4>

							<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#section_1" class="menu-linkRow ' . ((((($__vars['section'] == 'section_1')) OR (((!$__vars['section']) AND ((!$__vars['itemField']) AND ((!$__vars['itemPage']) AND (!$__vars['isFullView']))))))) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s1']) . '</a>

							';
		if ($__templater->method($__vars['item'], 'hasSections', array())) {
			$__compilerTemp1 .= ' 
								';
			if ($__vars['item']['message_s2'] AND ($__vars['item']['message_s2'] != '')) {
				$__compilerTemp1 .= '
									';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 2, ), ), true) . '#section_2" class="menu-linkRow ' . (($__vars['section'] == 'section_2') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s2']) . '</a>
									';
				} else {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 2, ), ), true) . '" class="menu-linkRow ' . (($__vars['section'] == 'section_2') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s2']) . '</a>
									';
				}
				$__compilerTemp1 .= '
								';
			}
			$__compilerTemp1 .= '

								';
			if ($__vars['item']['message_s3'] AND ($__vars['item']['message_s3'] != '')) {
				$__compilerTemp1 .= '
									';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 3, ), ), true) . '#section_3" class="menu-linkRow ' . (($__vars['section'] == 'section_3') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s3']) . '</a>
									';
				} else {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 3, ), ), true) . '" class="menu-linkRow ' . (($__vars['section'] == 'section_3') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s3']) . '</a>
									';
				}
				$__compilerTemp1 .= '
								';
			}
			$__compilerTemp1 .= '

								';
			if ($__vars['item']['message_s4'] AND ($__vars['item']['message_s4'] != '')) {
				$__compilerTemp1 .= '
									';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 4, ), ), true) . '#section_4" class="menu-linkRow ' . (($__vars['section'] == 'section_4') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s4']) . '</a>
									';
				} else {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 4, ), ), true) . '" class="menu-linkRow ' . (($__vars['section'] == 'section_4') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s4']) . '</a>
									';
				}
				$__compilerTemp1 .= '
								';
			}
			$__compilerTemp1 .= '

								';
			if ($__vars['item']['message_s5'] AND ($__vars['item']['message_s5'] != '')) {
				$__compilerTemp1 .= '
									';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 5, ), ), true) . '#section_5" class="menu-linkRow ' . (($__vars['section'] == 'section_5') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s5']) . '</a>
									';
				} else {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 5, ), ), true) . '" class="menu-linkRow ' . (($__vars['section'] == 'section_5') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s5']) . '</a>
									';
				}
				$__compilerTemp1 .= '
								';
			}
			$__compilerTemp1 .= '

								';
			if ($__vars['item']['message_s6'] AND ($__vars['item']['message_s6'] != '')) {
				$__compilerTemp1 .= '
									';
				if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 6, ), ), true) . '#section_6" class="menu-linkRow ' . (($__vars['section'] == 'section_6') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s6']) . '</a>
									';
				} else {
					$__compilerTemp1 .= '
										<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 6, ), ), true) . '" class="menu-linkRow ' . (($__vars['section'] == 'section_6') ? 'is-selected' : '') . '">' . $__templater->escape($__vars['item']['Category']['title_s6']) . '</a>
									';
				}
				$__compilerTemp1 .= '
								';
			}
			$__compilerTemp1 .= '
							';
		}
		$__compilerTemp1 .= '

							';
		$__compilerTemp3 = $__templater->method($__vars['item'], 'getExtraFieldTabs', array());
		if ($__templater->isTraversable($__compilerTemp3)) {
			foreach ($__compilerTemp3 AS $__vars['fieldId'] => $__vars['fieldValue']) {
				$__compilerTemp1 .= '
								<a href="' . $__templater->func('link', array('showcase/field', $__vars['item'], array('field' => $__vars['fieldId'], ), ), true) . '" class="menu-linkRow ' . (($__vars['itemField'] == $__vars['fieldId']) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['fieldValue']) . '</a>
							';
			}
		}
		$__compilerTemp1 .= '

							';
		if (!$__templater->test($__vars['itemPages'], 'empty', array())) {
			$__compilerTemp1 .= ' 
								';
			if ($__templater->isTraversable($__vars['itemPages'])) {
				foreach ($__vars['itemPages'] AS $__vars['item_page']) {
					$__compilerTemp1 .= '
									<a href="' . $__templater->func('link', array('showcase/page', $__vars['item_page'], ), true) . '" class="menu-linkRow ' . (($__vars['itemPage']['page_id'] == $__vars['item_page']['page_id']) ? 'is-selected' : '') . '">
										' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['item_page']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['item_page']['title']) . '</a>
								';
				}
			}
			$__compilerTemp1 .= '

								';
			if ($__vars['xf']['options']['xaScViewFullItem']) {
				$__compilerTemp1 .= '
									<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('full' => 1, ), ), true) . '#section_1" class="menu-linkRow ' . ($__vars['isFullView'] ? 'is-selected' : '') . '">' . 'Full view' . '</a>
								';
			}
			$__compilerTemp1 .= '

							';
		}
		$__compilerTemp1 .= '
						</div>
					</div>
				</div>
			';
	}
	$__compilerTemp1 .= '

			';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
								';
	if ($__templater->isTraversable($__vars['seriesToc'])) {
		foreach ($__vars['seriesToc'] AS $__vars['seriesTocItem']) {
			$__compilerTemp4 .= '
									<a href="' . $__templater->func('link', array('showcase', $__vars['seriesTocItem']['Item'], ), true) . '" class="menu-linkRow ' . (($__vars['item']['series_part_id'] == $__vars['seriesTocItem']['series_part_id']) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['seriesTocItem']['Item']['title']) . '</a>
								';
		}
	}
	$__compilerTemp4 .= '
							';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__compilerTemp1 .= '
				<div class="buttonGroup-buttonWrapper">
					' . $__templater->button('
						' . 'Series', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'title' => $__templater->filter('Series navigation', array(array('for_attr', array()),), false),
		), '', array(
		)) . '
					<div class="menu" data-menu="menu" aria-hidden="true">
						<div class="menu-content">
							<h4 class="menu-header">' . 'Select item' . '</h4>
							' . $__compilerTemp4 . '
							';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp1 .= '
								<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '" class="menu-linkRow">' . 'View series' . '</a>
							';
		}
		$__compilerTemp1 .= '
						</div>
					</div>
				</div>
			';
	}
	$__compilerTemp1 .= '

			';
	$__compilerTemp5 = '';
	$__compilerTemp5 .= '
								' . '
								';
	if ($__templater->method($__vars['item'], 'canSetCoverImage', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/set-cover-image', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Set cover image' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canSetBusinessHours', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/set-business-hours', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Set business hours' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canFeatureUnfeature', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/quick-feature', $__vars['item'], ), true) . '"
										class="menu-linkRow"
										data-xf-click="switch"
										data-menu-closer="true">

										';
		if ($__vars['item']['Featured']) {
			$__compilerTemp5 .= '
											' . 'Unfeature item' . '
										';
		} else {
			$__compilerTemp5 .= '
											' . 'Feature item' . '
										';
		}
		$__compilerTemp5 .= '
									</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canStickUnstick', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/quick-stick', $__vars['item'], ), true) . '"
										class="menu-linkRow"
										data-xf-click="switch"
										data-menu-closer="true">

										';
		if ($__vars['item']['sticky']) {
			$__compilerTemp5 .= '
											' . 'Unstick item' . '
										';
		} else {
			$__compilerTemp5 .= '
											' . 'Stick item' . '
										';
		}
		$__compilerTemp5 .= '
									</a>
								';
	}
	$__compilerTemp5 .= '								
								';
	if ($__templater->method($__vars['item'], 'canLockUnlockComments', array()) AND $__vars['item']['Category']['allow_comments']) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/lock-unlock-comments', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">
										';
		if ($__vars['item']['comments_open']) {
			$__compilerTemp5 .= '
											' . 'Lock comments' . '
										';
		} else {
			$__compilerTemp5 .= '
											' . 'Unlock comments' . '
										';
		}
		$__compilerTemp5 .= '
									</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canLockUnlockRatings', array()) AND $__vars['item']['Category']['allow_ratings']) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/lock-unlock-ratings', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">
										';
		if ($__vars['item']['ratings_open']) {
			$__compilerTemp5 .= '
											' . 'Lock ratings' . '
										';
		} else {
			$__compilerTemp5 .= '
											' . 'Unlock ratings' . '
										';
		}
		$__compilerTemp5 .= '
									</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canLeaveContributorsTeam', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/leave-contributors-team', $__vars['item'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">
										' . 'Leave item contributors team' . '
									</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canManageContributors', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/manage-contributors', $__vars['item'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">
										' . 'Manage contributors/co-owners' . '
									</a>
								';
	}
	$__compilerTemp5 .= '								
								';
	if ($__templater->method($__vars['item'], 'canEdit', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/edit', $__vars['item'], ), true) . '" class="menu-linkRow">' . 'Edit item' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canDelete', array('soft', ))) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/delete', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Delete item' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canMove', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/move', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Move item' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canReassign', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/reassign', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Reassign item' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canChangeDates', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/change-dates', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Change item dates' . '</a>
								';
	}
	$__compilerTemp5 .= '								
								';
	if ($__templater->method($__vars['item'], 'canCreatePoll', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/poll/create', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Create poll' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'isInSeries', array(false, )) AND $__templater->method($__vars['item']['SeriesPart'], 'canRemove', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/remove-from-series', $__vars['item'], ), true) . '" class="menu-linkRow" data-xf-click="overlay">' . 'Remove item from series' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canManagePages', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/pages', $__vars['item'], ), true) . '" class="menu-linkRow">' . 'Manage pages' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canManageRatings', array()) AND ((($__vars['item']['rating_count'] > 0) AND ($__vars['item']['rating_count'] > $__vars['item']['review_count'])))) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/ratings', $__vars['item'], ), true) . '" class="menu-linkRow">' . 'Manage ratings' . '</a>
								';
	}
	$__compilerTemp5 .= '								
								';
	if ($__templater->method($__vars['item'], 'canReplyBan', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/reply-bans', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Manage reply bans' . '</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canChangeDiscussionThread', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/change-thread', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">
										';
		if ($__vars['item']['discussion_thread_id']) {
			$__compilerTemp5 .= '			
											' . 'Change discussion thread' . '
										';
		} else {
			$__compilerTemp5 .= '
											' . 'Add discussion thread' . '
										';
		}
		$__compilerTemp5 .= '
									</a>
								';
	}
	$__compilerTemp5 .= '
								';
	if ($__templater->method($__vars['item'], 'canConvertToThread', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/convert-to-thread', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Convert item to thread' . '</a>
								';
	}
	$__compilerTemp5 .= '									
								';
	if ($__templater->method($__vars['item'], 'canViewModeratorLogs', array())) {
		$__compilerTemp5 .= '
									<a href="' . $__templater->func('link', array('showcase/moderator-actions', $__vars['item'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Moderator actions' . '</a>
								';
	}
	$__compilerTemp5 .= '
								' . '
								';
	if ($__templater->method($__vars['item'], 'canUseInlineModeration', array())) {
		$__compilerTemp5 .= '
									<div class="menu-footer"
										data-xf-init="inline-mod"
										data-type="sc_item"
										data-href="' . $__templater->func('link', array('inline-mod', ), true) . '"
										data-toggle=".js-itemInlineModToggle">
										' . $__templater->formCheckBox(array(
		), array(array(
			'class' => 'js-itemInlineModToggle',
			'value' => $__vars['item']['item_id'],
			'label' => 'Select for moderation',
			'_type' => 'option',
		))) . '
									</div>
									';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__compilerTemp5 .= '
								';
	}
	$__compilerTemp5 .= '
								' . '
							';
	if (strlen(trim($__compilerTemp5)) > 0) {
		$__compilerTemp1 .= '
				<div class="buttonGroup-buttonWrapper">
					' . $__templater->button('&#8226;&#8226;&#8226;', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'title' => $__templater->filter('More options', array(array('for_attr', array()),), false),
		), '', array(
		)) . '
					<div class="menu" data-menu="menu" aria-hidden="true">
						<div class="menu-content">
							<h4 class="menu-header">' . 'More options' . '</h4>
							' . $__compilerTemp5 . '
						</div>
					</div>
				</div>
			';
	}
	$__compilerTemp1 .= '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="buttonGroup">
		' . $__compilerTemp1 . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);