<?php
// FROM HASH: 1c31f563da90a30410d7384ea4af6558
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
	$__vars['ldJson'] = $__templater->method($__vars['itemPage'], 'getLdStructuredData', array(0, $__templater->renderExtension('structured_data_extra_params', $__vars, $__extensions), ));
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . ($__vars['item']['meta_title'] ? $__templater->escape($__vars['item']['meta_title']) : $__templater->escape($__vars['item']['title'])) . ' | ' . ($__vars['itemPage']['meta_title'] ? $__templater->escape($__vars['itemPage']['meta_title']) : $__templater->escape($__vars['itemPage']['title'])));
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
	if ($__vars['itemPage']['meta_description']) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['itemPage']['meta_description'], 320, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else if ($__vars['itemPage']['description']) {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['itemPage']['description'], 256, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['descSnippet'] = $__templater->func('snippet', array($__vars['itemPage']['message'], 256, array('stripBbCode' => true, ), ), false);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['itemPage']['CoverImage']) {
		$__finalCompiled .= '
	';
		$__vars['imageUrl'] = $__templater->func('link', array('canonical:showcase/page/cover-image', $__vars['itemPage'], ), false);
		$__finalCompiled .= '
';
	} else if ($__vars['item']['CoverImage']) {
		$__finalCompiled .= '
	';
		$__vars['imageUrl'] = $__templater->func('link', array('canonical:showcase/cover-image', $__vars['item'], ), false);
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__vars['imageUrl'] = ($__vars['item']['Category']['content_image_url'] ? $__templater->func('base_url', array($__vars['item']['Category']['content_image_url'], true, ), false) : '');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'title' => ($__vars['itemPage']['og_title'] ? $__vars['itemPage']['og_title'] : ($__vars['itemPage']['meta_title'] ? $__vars['itemPage']['meta_title'] : $__vars['itemPage']['title'])) . ' | ' . ($__vars['item']['og_title'] ? $__vars['item']['og_title'] : ($__vars['item']['meta_title'] ? $__vars['item']['meta_title'] : $__vars['item']['title'])),
		'description' => $__vars['descSnippet'],
		'type' => 'article',
		'shareUrl' => $__templater->func('link', array('canonical:showcase/page', $__vars['itemPage'], ), false),
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/page', $__vars['itemPage'], ), false),
		'imageUrl' => $__vars['imageUrl'],
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
	$__compilerTemp1['pageSelected'] = 'itemPage-{itemPage.page_id}';
	$__templater->wrapTemplate('xa_sc_item_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc_page.less');
	$__finalCompiled .= '

' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => $__templater->method($__vars['itemPage'], 'canViewAttachments', array()),
	), $__vars) . '

<div class="block">
	';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
				' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'action_buttons', array(
		'item' => $__vars['item'],
		'seriesToc' => $__vars['seriesToc'],
		'itemPages' => $__vars['itemPages'],
		'itemPage' => $__vars['itemPage'],
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
	if ($__vars['itemPage']['cover_image_id'] AND $__vars['itemPage']['cover_image_above_page']) {
		$__finalCompiled .= '
		<div class="scCoverImage ' . ($__vars['itemPage']['cover_image_caption'] ? 'has-caption' : '') . '">
			<div class="scCoverImage-container">
				<div class="scCoverImage-container-image js-coverImageContainerImage">
					<img src="' . $__templater->func('link', array('showcase/page/cover-image', $__vars['itemPage'], ), true) . '" class="js-itemCoverImage" />
				</div>
			</div>
		</div>
		
		';
		if ($__vars['itemPage']['cover_image_caption']) {
			$__finalCompiled .= '
			<div class="scCoverImage-caption">
				' . $__templater->func('snippet', array($__vars['itemPage']['cover_image_caption'], 500, array('stripBbCode' => true, ), ), true) . '
			</div>
		';
		}
		$__finalCompiled .= '			
	';
	}
	$__finalCompiled .= '
	
	';
	if ($__vars['pagePoll']) {
		$__finalCompiled .= '
		' . $__templater->callMacro('poll_macros', 'poll_block', array(
			'poll' => $__vars['pagePoll'],
		), $__vars) . '
	';
	}
	$__finalCompiled .= '	

	<div class="block-container">
		<div class="block-body lbContainer js-itemBody"
			data-xf-init="lightbox"
			data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
			data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['item']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['item']['create_date'], ), true) . '"
			id="js-itemBody-' . $__templater->escape($__vars['item']['item_id']) . '">

			<div class="itemBody">
				<article class="itemBody-main js-lbContainer">
					
					' . $__templater->callAdsMacro('sc_item_view_above_item', array(
		'item' => $__vars['item'],
	), $__vars) . '

					<h2>' . $__templater->escape($__vars['itemPage']['title']) . '</h2>

					';
	if ($__vars['itemPage']['display_byline']) {
		$__finalCompiled .= '
						<div class="message-attribution message-attribution-scPageMeta">
							<ul class="listInline listInline--bullet">
								<li>
									' . $__templater->fontAwesome('fa-user', array(
			'title' => $__templater->filter('Author', array(array('for_attr', array()),), false),
		)) . '
									<span class="u-srOnly">' . 'Author' . '</span>
									' . $__templater->func('username_link', array($__vars['itemPage']['User'], false, array(
			'defaultname' => $__vars['itemPage']['username'],
			'class' => 'u-concealed',
		))) . '
								</li>
								<li>
									' . $__templater->fontAwesome('fa-clock', array(
			'title' => $__templater->filter('Create date', array(array('for_attr', array()),), false),
		)) . '
									<span class="u-srOnly">' . 'Create date' . '</span>

									<a href="' . $__templater->func('link', array('showcase/page', $__vars['itemPage'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['itemPage']['create_date'], array(
		))) . '</a>
								</li>

								';
		if ($__vars['itemPage']['edit_date'] > $__vars['itemPage']['create_date']) {
			$__finalCompiled .= '								
									<li>
										' . $__templater->fontAwesome('fa-clock', array(
				'title' => $__templater->filter('Last update', array(array('for_attr', array()),), false),
			)) . '
										<span class="u-concealed">' . 'Updated' . '</span>

										' . $__templater->func('date_dynamic', array($__vars['itemPage']['edit_date'], array(
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
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
								';
	if ($__vars['itemPage']['warning_message']) {
		$__compilerTemp3 .= '
									<dd class="blockStatus-message blockStatus-message--warning">
										' . $__templater->escape($__vars['itemPage']['warning_message']) . '
									</dd>
								';
	}
	$__compilerTemp3 .= '
								';
	if ($__templater->method($__vars['itemPage'], 'isIgnored', array())) {
		$__compilerTemp3 .= '
									<dd class="blockStatus-message blockStatus-message--ignored">
										' . 'You are ignoring content by this member.' . '
									</dd>
								';
	}
	$__compilerTemp3 .= '
							';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
						<dl class="blockStatus blockStatus--standalone">
							<dt>' . 'Status' . '</dt>
							' . $__compilerTemp3 . '
						</dl>
					';
	}
	$__finalCompiled .= '	

					' . $__templater->func('bb_code', array($__vars['itemPage']['message'], 'sc_page', $__vars['itemPage'], ), true) . '
					
					' . $__templater->callAdsMacro('sc_item_view_below_item', array(
		'item' => $__vars['item'],
	), $__vars) . '					

					';
	if (($__vars['item']['page_count'] AND $__vars['itemPages']) OR ($__templater->method($__vars['item'], 'isInSeries', array(true, )) AND $__vars['seriesToc'])) {
		$__finalCompiled .= '
						<div style="padding-top: 10px;">
							';
		if ($__vars['item']['page_count'] AND $__vars['itemPages']) {
			$__finalCompiled .= '
								';
			if ($__vars['itemPage'] AND (!$__vars['previousPage'])) {
				$__finalCompiled .= '
									<dl class="blockStatus blockStatus--info blockStatus--standalone">
										<dt></dt>
										<dd class="blockStatus-message">
											<span class="">' . 'Previous page' . ':</span>
											<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#section_1" class="" title="' . $__templater->escape($__vars['category']['title_s1']) . '">' . $__templater->escape($__vars['category']['title_s1']) . '</a>
										</dd>
									</dl>
								';
			}
			$__finalCompiled .= '

								';
			if ($__vars['previousPage']) {
				$__finalCompiled .= '						
									<dl class="blockStatus blockStatus--info blockStatus--standalone">
										<dt></dt>
										<dd class="blockStatus-message">
											<span class="">' . 'Previous page' . ':</span>
											<a href="' . $__templater->func('link', array('showcase/page', $__vars['previousPage'], ), true) . '" class="" title="' . $__templater->escape($__vars['previousPage']['title']) . '">' . $__templater->escape($__vars['previousPage']['title']) . '</a>
										</dd>
									</dl>
								';
			}
			$__finalCompiled .= '

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
	if ($__vars['itemPage']['attach_count']) {
		$__finalCompiled .= '
						';
		$__compilerTemp4 = '';
		$__compilerTemp4 .= '
									';
		if ($__templater->isTraversable($__vars['itemPage']['Attachments'])) {
			foreach ($__vars['itemPage']['Attachments'] AS $__vars['attachment']) {
				if ($__vars['attachment']['has_thumbnail'] AND (!$__templater->method($__vars['itemPage'], 'isAttachmentEmbedded', array($__vars['attachment'], )))) {
					$__compilerTemp4 .= '
										';
					if ($__vars['itemPage']['cover_image_above_page'] AND ($__vars['attachment']['attachment_id'] == $__vars['itemPage']['cover_image_id'])) {
						$__compilerTemp4 .= '
											' . '
										';
					} else {
						$__compilerTemp4 .= '
											' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
							'attachment' => $__vars['attachment'],
							'canView' => $__templater->method($__vars['itemPage'], 'canViewAttachments', array()),
						), $__vars) . '
										';
					}
					$__compilerTemp4 .= '
									';
				}
			}
		}
		$__compilerTemp4 .= '
								';
		if (strlen(trim($__compilerTemp4)) > 0) {
			$__finalCompiled .= '
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList itemBody-attachments">
								' . $__compilerTemp4 . '
							</ul>
						';
		}
		$__finalCompiled .= '

						';
		$__compilerTemp5 = '';
		$__compilerTemp5 .= '
									';
		if ($__templater->isTraversable($__vars['itemPage']['Attachments'])) {
			foreach ($__vars['itemPage']['Attachments'] AS $__vars['attachment']) {
				$__compilerTemp5 .= '
										';
				if ($__vars['attachment']['has_thumbnail'] OR $__vars['attachment']['is_video']) {
					$__compilerTemp5 .= '
											' . '
										';
				} else {
					$__compilerTemp5 .= '
											' . $__templater->callMacro('attachment_macros', 'attachment_list_item', array(
						'attachment' => $__vars['attachment'],
						'canView' => $__templater->method($__vars['itemPage'], 'canViewAttachments', array()),
					), $__vars) . '
										';
				}
				$__compilerTemp5 .= '
									';
			}
		}
		$__compilerTemp5 .= '
								';
		if (strlen(trim($__compilerTemp5)) > 0) {
			$__finalCompiled .= '
							';
			$__templater->includeCss('attachments.less');
			$__finalCompiled .= '
							<ul class="attachmentList itemBody-attachments">
								' . $__compilerTemp5 . '
							</ul>
						';
		}
		$__finalCompiled .= '
					';
	}
	$__finalCompiled .= '

					';
	$__compilerTemp6 = '';
	$__compilerTemp6 .= '
								';
	$__compilerTemp7 = '';
	$__compilerTemp7 .= '
										' . $__templater->func('react', array(array(
		'content' => $__vars['itemPage'],
		'link' => 'showcase/page/react',
		'list' => '< .js-itemBody | .js-reactionsList',
	))) . '
									';
	if (strlen(trim($__compilerTemp7)) > 0) {
		$__compilerTemp6 .= '
									<div class="actionBar-set actionBar-set--external">
									' . $__compilerTemp7 . '
									</div>
								';
	}
	$__compilerTemp6 .= '

								';
	$__compilerTemp8 = '';
	$__compilerTemp8 .= '
										';
	if ($__templater->method($__vars['item'], 'canReport', array())) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/report', $__vars['itemPage'], ), true) . '"
												class="actionBar-action actionBar-action--report" 
												data-xf-click="overlay">' . 'Report' . '</a>
										';
	}
	$__compilerTemp8 .= '

										';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp8 .= '

										';
	if ($__templater->method($__vars['itemPage'], 'canEdit', array())) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/edit', $__vars['itemPage'], ), true) . '"
												class="actionBar-action actionBar-action--edit actionBar-action--menuItem">' . 'Edit' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp8 .= '
										';
	}
	$__compilerTemp8 .= '

										';
	if ($__vars['itemPage']['edit_count'] AND $__templater->method($__vars['itemPage'], 'canViewHistory', array())) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/history', $__vars['itemPage'], ), true) . '" 
												class="actionBar-action actionBar-action--history actionBar-action--menuItem"
												data-xf-click="toggle"
												data-target="#js-itemBody-' . $__templater->escape($__vars['item']['item_id']) . ' .js-historyTarget"
												data-menu-closer="true">' . 'History' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp8 .= '
										';
	}
	$__compilerTemp8 .= '

										';
	if ($__templater->method($__vars['itemPage'], 'canDelete', array('soft', ))) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/delete', $__vars['itemPage'], ), true) . '"
												class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
												data-xf-click="overlay">' . 'Delete' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp8 .= '
										';
	}
	$__compilerTemp8 .= '

										';
	if ($__templater->method($__vars['itemPage'], 'canReassign', array())) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/reassign', $__vars['itemPage'], ), true) . '"
												class="actionBar-action actionBar-action--report" 
												data-xf-click="overlay">' . 'Reassign' . '</a>
										';
	}
	$__compilerTemp8 .= '

										';
	if ($__templater->method($__vars['itemPage'], 'canSetCoverImage', array())) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/set-cover-image', $__vars['itemPage'], ), true) . '" 
												class="actionBar-action actionBar-action--cover-image actionBar-action--menuItem"
												data-xf-click="overlay">' . 'Set cover image' . '</a>
										';
	}
	$__compilerTemp8 .= '

										';
	if ($__templater->method($__vars['itemPage'], 'canCreatePoll', array())) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/poll-create', $__vars['itemPage'], ), true) . '"
												class="actionBar-action actionBar-action--report" 
												data-xf-click="overlay">' . 'Create poll' . '</a>
										';
	}
	$__compilerTemp8 .= '

										';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['itemPage']['ip_id']) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/ip', $__vars['itemPage'], ), true) . '"
												class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
												data-xf-click="overlay">' . 'IP' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp8 .= '
										';
	}
	$__compilerTemp8 .= '

										';
	if ($__templater->method($__vars['itemPage'], 'canWarn', array())) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('showcase/page/warn', $__vars['itemPage'], ), true) . '"
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp8 .= '
										';
	} else if ($__vars['itemPage']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp8 .= '
											<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['itemPage']['warning_id'], ), ), true) . '"
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
												data-xf-click="overlay">' . 'View warning' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp8 .= '
										';
	}
	$__compilerTemp8 .= '

										';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp8 .= '
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
	$__compilerTemp8 .= '
									';
	if (strlen(trim($__compilerTemp8)) > 0) {
		$__compilerTemp6 .= '
									<div class="actionBar-set actionBar-set--internal">
									' . $__compilerTemp8 . '
									</div>
								';
	}
	$__compilerTemp6 .= '
							';
	if (strlen(trim($__compilerTemp6)) > 0) {
		$__finalCompiled .= '
						<div class="actionBar">
							' . $__compilerTemp6 . '
						</div>
					';
	}
	$__finalCompiled .= '

					<div class="reactionsBar js-reactionsList ' . ($__vars['itemPage']['reactions'] ? 'is-active' : '') . '">
						' . $__templater->func('reactions', array($__vars['itemPage'], 'showcase/page/reactions', array())) . '
					</div>

					<div class="js-historyTarget toggleTarget" data-href="trigger-href"></div>
				</article>
			</div>
		</div>
	</div>
</div>

';
	$__compilerTemp9 = '';
	$__compilerTemp9 .= '
				';
	if ($__vars['xf']['options']['xaScDisplayShareBelowItem']) {
		$__compilerTemp9 .= '
					' . $__templater->callMacro('share_page_macros', 'buttons', array(
			'iconic' => true,
			'label' => 'Share' . ':',
		), $__vars) . '
				';
	}
	$__compilerTemp9 .= '
			';
	if (strlen(trim($__compilerTemp9)) > 0) {
		$__finalCompiled .= '
	<div class="block">
		<div class="blockMessage blockMessage--none">
			' . $__compilerTemp9 . '
		</div>
	</div>	
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp10 = '';
	if ($__templater->method($__vars['item'], 'hasSections', array())) {
		$__compilerTemp10 .= ' 
					';
		if ($__vars['item']['message_s2'] AND ($__vars['item']['message_s2'] != '')) {
			$__compilerTemp10 .= '
						';
			if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
				$__compilerTemp10 .= '
							<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 2, ), ), true) . '#section_2" class="blockLink">' . $__templater->escape($__vars['category']['title_s2']) . '</a>
						';
			} else {
				$__compilerTemp10 .= '	
							<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 2, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s2']) . '</a>
						';
			}
			$__compilerTemp10 .= '
					';
		}
		$__compilerTemp10 .= '

					';
		if ($__vars['item']['message_s3'] AND ($__vars['item']['message_s3'] != '')) {
			$__compilerTemp10 .= '
						';
			if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
				$__compilerTemp10 .= '
							<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 3, ), ), true) . '#section_3" class="blockLink">' . $__templater->escape($__vars['category']['title_s3']) . '</a>
						';
			} else {
				$__compilerTemp10 .= '	
							<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 3, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s3']) . '</a>
						';
			}
			$__compilerTemp10 .= '
					';
		}
		$__compilerTemp10 .= '

					';
		if ($__vars['item']['message_s4'] AND ($__vars['item']['message_s4'] != '')) {
			$__compilerTemp10 .= '
						';
			if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
				$__compilerTemp10 .= '
							<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 4, ), ), true) . '#section_4" class="blockLink">' . $__templater->escape($__vars['category']['title_s4']) . '</a>
						';
			} else {
				$__compilerTemp10 .= '	
							<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 4, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s4']) . '</a>
						';
			}
			$__compilerTemp10 .= '
					';
		}
		$__compilerTemp10 .= '

					';
		if ($__vars['item']['message_s5'] AND ($__vars['item']['message_s5'] != '')) {
			$__compilerTemp10 .= '
						';
			if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
				$__compilerTemp10 .= '
							<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 5, ), ), true) . '#section_5" class="blockLink">' . $__templater->escape($__vars['category']['title_s5']) . '</a>
						';
			} else {
				$__compilerTemp10 .= '	
							<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 5, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s5']) . '</a>
						';
			}
			$__compilerTemp10 .= '
					';
		}
		$__compilerTemp10 .= '

					';
		if ($__vars['item']['message_s6'] AND ($__vars['item']['message_s6'] != '')) {
			$__compilerTemp10 .= '
						';
			if ($__vars['xf']['options']['xaScSectionsDisplayType'] == 'stacked') {
				$__compilerTemp10 .= '
							<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('section' => 6, ), ), true) . '#section_6" class="blockLink">' . $__templater->escape($__vars['category']['title_s6']) . '</a>
						';
			} else {
				$__compilerTemp10 .= '	
							<a href="' . $__templater->func('link', array('showcase/section', $__vars['item'], array('section' => 6, ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['category']['title_s6']) . '</a>
						';
			}
			$__compilerTemp10 .= '
					';
		}
		$__compilerTemp10 .= '
				';
	}
	$__compilerTemp11 = '';
	$__compilerTemp12 = $__templater->method($__vars['item'], 'getExtraFieldTabs', array());
	if ($__templater->isTraversable($__compilerTemp12)) {
		foreach ($__compilerTemp12 AS $__vars['_fieldId'] => $__vars['_fieldValue']) {
			$__compilerTemp11 .= '
					<a href="' . $__templater->func('link', array('showcase/field', $__vars['item'], array('field' => $__vars['_fieldId'], ), ), true) . '" class="blockLink">' . $__templater->escape($__vars['_fieldValue']) . '</a>
				';
		}
	}
	$__compilerTemp13 = '';
	if ($__templater->isTraversable($__vars['itemPages'])) {
		foreach ($__vars['itemPages'] AS $__vars['item_page']) {
			$__compilerTemp13 .= '
					<a href="' . $__templater->func('link', array('showcase/page', $__vars['item_page'], ), true) . '" class="blockLink ' . (($__vars['itemPage']['page_id'] == $__vars['item_page']['page_id']) ? 'is-selected' : '') . '">
						' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['item_page']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['item_page']['title']) . '</a>
				';
		}
	}
	$__compilerTemp14 = '';
	if ($__vars['xf']['options']['xaScViewFullItem']) {
		$__compilerTemp14 .= '
					<a href="' . $__templater->func('link', array('showcase', $__vars['item'], array('full' => 1, ), ), true) . '#section_1" class="blockLink ' . ($__vars['isFullView'] ? 'is-selected' : '') . '">' . 'Full view' . '</a>
				';
	}
	$__templater->modifySidebarHtml('itemTocSidebar', '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Table of contents' . '</h3>
			<div class="block-body">
				<a class="blockLink" href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#section_1">' . $__templater->escape($__vars['category']['title_s1']) . '</a>

				' . $__compilerTemp10 . '

				' . $__compilerTemp11 . '

				' . $__compilerTemp13 . '

				' . $__compilerTemp14 . '
			</div>
		</div>
	</div>
', 'replace');
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['seriesToc'], 'empty', array())) {
		$__finalCompiled .= '
	';
		$__compilerTemp15 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp15 .= '
						<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '">' . 'Series' . '</a>
					';
		} else {
			$__compilerTemp15 .= '
						' . 'Series' . '
					';
		}
		$__compilerTemp16 = '';
		if ($__templater->isTraversable($__vars['seriesToc'])) {
			foreach ($__vars['seriesToc'] AS $__vars['seriesToc_part']) {
				$__compilerTemp16 .= '
						<a href="' . $__templater->func('link', array('showcase', $__vars['seriesToc_part']['Item'], ), true) . '" class="blockLink ' . (($__vars['seriesToc_part']['series_part_id'] == $__vars['item']['series_part_id']) ? 'is-selected' : '') . '">' . $__templater->escape($__vars['seriesToc_part']['Item']['title']) . '</a>
					';
			}
		}
		$__compilerTemp17 = '';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewShowcaseSeries', array())) {
			$__compilerTemp17 .= '
						<a href="' . $__templater->func('link', array('showcase/series', $__vars['item']['SeriesPart']['Series'], ), true) . '" class="blockLink">' . 'View series' . '</a>
					';
		}
		$__templater->modifySidebarHtml('seriesTocSidebar', '
		<div class="block">
			<div class="block-container">
				<h3 class="block-header">
					' . $__compilerTemp15 . '
				</h3>
				<div class="block-body">
					' . $__compilerTemp16 . '

					' . $__compilerTemp17 . '
				</div>
			</div>
		</div>
	', 'replace');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp18 = '';
	$__compilerTemp19 = '';
	$__compilerTemp19 .= '
					';
	if ($__templater->method($__vars['item'], 'hasViewableDiscussion', array())) {
		$__compilerTemp19 .= '
						' . $__templater->button('Join discussion', array(
			'href' => $__templater->func('link', array('threads', $__vars['item']['Discussion'], ), false),
			'class' => 'button--fullWidth',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp19 .= '
				';
	if (strlen(trim($__compilerTemp19)) > 0) {
		$__compilerTemp18 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp19 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('discussionButtonSidebar', '
	' . $__compilerTemp18 . '	
', 'replace');
	$__finalCompiled .= '

';
	$__compilerTemp20 = '';
	$__compilerTemp21 = '';
	$__compilerTemp21 .= '
					<h3 class="block-minorHeader">' . ($__vars['item']['Category']['content_term'] ? 'Share this ' . $__templater->filter($__vars['item']['Category']['content_term'], array(array('to_lower', array()),), true) . ' page' : 'Share this item page') . '</h3>
					';
	$__compilerTemp22 = '';
	$__compilerTemp22 .= '
								' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp22)) > 0) {
		$__compilerTemp21 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp22 . '
						</div>
					';
	}
	$__compilerTemp21 .= '
					';
	$__compilerTemp23 = '';
	$__compilerTemp23 .= '
								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy URL BB code',
		'text' => '[URL="' . $__templater->func('link', array('canonical:showcase/page', $__vars['itemPage'], ), false) . '"]' . $__vars['itemPage']['title'] . '[/URL]',
	), $__vars) . '

								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy SHOWCASE BB code',
		'text' => '[SHOWCASE=page, ' . $__vars['itemPage']['page_id'] . '][/SHOWCASE]',
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp23)) > 0) {
		$__compilerTemp21 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp23 . '
						</div>
					';
	}
	$__compilerTemp21 .= '
				';
	if (strlen(trim($__compilerTemp21)) > 0) {
		$__compilerTemp20 .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp21 . '
			</div>
		</div>
	';
	}
	$__templater->modifySidebarHtml('shareSidebar', '
	' . $__compilerTemp20 . '
', 'replace');
	return $__finalCompiled;
}
);