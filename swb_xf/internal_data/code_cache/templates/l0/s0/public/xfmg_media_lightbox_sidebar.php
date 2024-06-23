<?php
// FROM HASH: 6925dcc807566281b800ee64b93d2346
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['mediaItem']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['noH1'] = true;
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'xf/comment.js',
		'min' => '1',
	));
	$__finalCompiled .= '
';
	$__templater->includeCss('xfmg_media_view.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('xfmg_comment.less');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container block-container--none">
		<div class="block-body block-row">
			<div class="xfmgInfoBlock xfmgInfoBlock--lightbox">

				';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
							' . $__templater->callMacro('xfmg_media_view_macros', 'media_status', array(
		'mediaItem' => $__vars['mediaItem'],
		'block' => false,
	), $__vars) . '
						';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
					<div class="xfmgInfoBlock-status">
						' . $__compilerTemp1 . '
					</div>
				';
	}
	$__finalCompiled .= '

				<div class="xfmgInfoBlock-title">
					<div class="contentRow contentRow--alignMiddle">
					<span class="contentRow-figure">
						' . $__templater->func('avatar', array($__vars['mediaItem']['User'], 's', false, array(
		'defaultname' => $__vars['mediaItem']['username'],
	))) . '
					</span>
						<div class="contentRow-main">
							<h2 class="contentRow-title p-title-value">' . $__templater->func('page_h1', array('')) . '</h2>
							<div class="contentRow-lesser p-description">
								<ul class="listInline listInline--bullet">
									<li>' . $__templater->fontAwesome('fa-user', array(
		'title' => $__templater->filter('Media owner', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('username_link', array($__vars['mediaItem']['User'], false, array(
		'defaultname' => $__vars['mediaItem']['username'],
		'class' => 'u-concealed',
	))) . '</li>
									<li>' . $__templater->fontAwesome('fa-clock', array(
		'title' => $__templater->filter('Date added', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('date_dynamic', array($__vars['mediaItem']['media_date'], array(
	))) . '</li>
									';
	if ($__vars['xf']['options']['enableTagging'] AND (($__templater->method($__vars['mediaItem'], 'canEditTags', array()) OR $__vars['mediaItem']['tags']))) {
		$__finalCompiled .= '
										<li>
											' . $__templater->callMacro('tag_macros', 'list', array(
			'tags' => $__vars['mediaItem']['tags'],
			'tagList' => 'tagList--mediaItem-' . $__vars['mediaItem']['media_id'],
			'editLink' => ($__templater->method($__vars['mediaItem'], 'canEditTags', array()) ? $__templater->func('link', array('media/tags', $__vars['mediaItem'], ), false) : ''),
		), $__vars) . '
										</li>
									';
	}
	$__finalCompiled .= '
								</ul>
							</div>
						</div>
					</div>
				</div>

				';
	if ($__vars['mirrorContainer']) {
		$__finalCompiled .= '
					<div class="xfmgInfoBlock-originallyFrom">
						' . 'Originally posted in: <a href="' . $__templater->escape($__vars['mirrorContainer']['link']) . '">' . $__templater->escape($__vars['mirrorContainer']['title']) . '</a>' . '
					</div>
				';
	}
	$__finalCompiled .= '

				';
	if ($__vars['mediaItem']['description']) {
		$__finalCompiled .= '
					<div class="xfmgInfoBlock-description">
						<div class="bbCodeBlock bbCodeBlock--expandable js-expandWatch">
							<div class="bbCodeBlock-content">
								<div class="bbCodeBlock-expandContent js-expandContent">
									' . $__templater->func('structured_text', array($__vars['mediaItem']['description'], ), true) . '
								</div>
								<div class="bbCodeBlock-expandLink js-expandLink"><a role="button" tabindex="0">' . 'Click to expand...' . '</a></div>
							</div>
						</div>
					</div>
				';
	}
	$__finalCompiled .= '

				' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'xfmgMediaFields',
		'group' => 'below_media',
		'onlyInclude' => ($__vars['mediaItem']['category_id'] ? $__vars['mediaItem']['Category']['field_cache'] : $__vars['mediaItem']['Album']['field_cache']),
		'set' => $__vars['mediaItem']['custom_fields'],
	), $__vars) . '

				';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
							';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
										' . $__templater->func('react', array(array(
		'content' => $__vars['mediaItem'],
		'link' => 'media/react',
		'list' => '< .js-mediaInfoBlock | .js-reactionsList',
	))) . '
									';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '
								<div class="actionBar-set actionBar-set--external">
									' . $__compilerTemp3 . '
								</div>
							';
	}
	$__compilerTemp2 .= '

							';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
										';
	if ($__templater->method($__vars['mediaItem'], 'canReport', array())) {
		$__compilerTemp4 .= '
											<a href="' . $__templater->func('link', array('media/report', $__vars['mediaItem'], ), true) . '"
												class="actionBar-action actionBar-action--report"
												data-xf-click="overlay">' . 'Report' . '</a>
										';
	}
	$__compilerTemp4 .= '

										';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp4 .= '
										';
	if ($__templater->method($__vars['mediaItem'], 'canEdit', array())) {
		$__compilerTemp4 .= '
											<a href="' . $__templater->func('link', array('media/edit', $__vars['mediaItem'], ), true) . '"
												class="actionBar-action actionBar-action--edit actionBar-action--menuItem"
												data-xf-click="overlay">' . 'Edit' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
										';
	}
	$__compilerTemp4 .= '
										';
	if ($__templater->method($__vars['mediaItem'], 'canDelete', array())) {
		$__compilerTemp4 .= '
											<a href="' . $__templater->func('link', array('media/delete', $__vars['mediaItem'], ), true) . '"
												class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
												data-xf-click="overlay">' . 'Delete' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
										';
	}
	$__compilerTemp4 .= '
										';
	if ($__templater->method($__vars['mediaItem'], 'canCleanSpam', array())) {
		$__compilerTemp4 .= '
											<a href="' . $__templater->func('link', array('spam-cleaner', $__vars['mediaItem'], ), true) . '"
												class="actionBar-action actionBar-action--spam actionBar-action--menuItem"
												data-xf-click="overlay">' . 'Spam' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
										';
	}
	$__compilerTemp4 .= '
										';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['mediaItem']['ip_id']) {
		$__compilerTemp4 .= '
											<a href="' . $__templater->func('link', array('media/ip', $__vars['mediaItem'], ), true) . '"
												class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
												data-xf-click="overlay">' . 'IP' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
										';
	}
	$__compilerTemp4 .= '
										';
	if ($__templater->method($__vars['mediaItem'], 'canWarn', array())) {
		$__compilerTemp4 .= '
											<a href="' . $__templater->func('link', array('media/warn', $__vars['mediaItem'], ), true) . '"
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
										';
	} else if ($__vars['mediaItem']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp4 .= '
											<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['mediaItem']['warning_id'], ), ), true) . '"
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
												data-xf-click="overlay">' . 'View warning' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
										';
	}
	$__compilerTemp4 .= '

										';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp4 .= '
											<a class="actionBar-action actionBar-action--menuTrigger"
												data-xf-click="menu"
												title="' . $__templater->filter('More options', array(array('for_attr', array()),), true) . '"
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
	$__compilerTemp4 .= '
									';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__compilerTemp2 .= '
								<div class="actionBar-set actionBar-set--internal">
									' . $__compilerTemp4 . '
								</div>
							';
	}
	$__compilerTemp2 .= '
						';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
					<div class="actionBar">
						' . $__compilerTemp2 . '
					</div>
				';
	}
	$__finalCompiled .= '

				<div class="reactionsBar js-reactionsList ' . ($__vars['mediaItem']['reactions'] ? 'is-active' : '') . '">
					' . $__templater->func('reactions', array($__vars['mediaItem'], 'media/reactions', array())) . '
				</div>
			</div>
		</div>
	</div>

	<div class="block-outer block-outer--after block-outer--padded">
		';
	$__compilerTemp5 = '';
	$__compilerTemp5 .= '
					';
	if ($__templater->method($__vars['mediaItem'], 'canRate', array())) {
		$__compilerTemp5 .= '
						' . $__templater->button('
							' . 'Leave a rating' . '
						', array(
			'href' => $__templater->func('link', array('media/media-ratings/rate', $__vars['mediaItem'], ), false),
			'overlay' => 'true',
		), '', array(
		)) . '
					';
	}
	$__compilerTemp5 .= '

					';
	$__compilerTemp6 = '';
	$__compilerTemp6 .= '
								';
	if ($__templater->method($__vars['mediaItem'], 'canUndelete', array()) AND ($__vars['mediaItem']['media_state'] == 'deleted')) {
		$__compilerTemp6 .= '
									' . $__templater->button('
										' . 'Undelete' . '
									', array(
			'href' => $__templater->func('link', array('media/undelete', $__vars['mediaItem'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp6 .= '
								';
	if ($__templater->method($__vars['mediaItem'], 'canApproveUnapprove', array()) AND ($__vars['mediaItem']['media_state'] == 'moderated')) {
		$__compilerTemp6 .= '
									' . $__templater->button('
										' . 'Approve' . '
									', array(
			'href' => $__templater->func('link', array('media/approve', $__vars['mediaItem'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp6 .= '
								';
	if ($__templater->method($__vars['mediaItem'], 'canWatch', array())) {
		$__compilerTemp6 .= '
									';
		$__compilerTemp7 = '';
		if ($__vars['mediaItem']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp7 .= '
											' . 'Unwatch' . '
										';
		} else {
			$__compilerTemp7 .= '
											' . 'Watch' . '
										';
		}
		$__compilerTemp6 .= $__templater->button('

										' . $__compilerTemp7 . '
									', array(
			'href' => $__templater->func('link', array('media/watch', $__vars['mediaItem'], ), false),
			'class' => 'button--link',
			'data-xf-click' => 'switch-overlay',
			'data-sk-watch' => 'Watch',
			'data-sk-unwatch' => 'Unwatch',
		), '', array(
		)) . '
								';
	}
	$__compilerTemp6 .= '
								' . $__templater->callMacro('bookmark_macros', 'button', array(
		'content' => $__vars['mediaItem'],
		'class' => 'button--link',
		'confirmUrl' => $__templater->func('link', array('media/bookmark', $__vars['mediaItem'], ), false),
	), $__vars) . '

								';
	$__compilerTemp8 = '';
	$__compilerTemp8 .= '
													' . '
													';
	if ($__templater->method($__vars['mediaItem'], 'canSetAsAvatar', array())) {
		$__compilerTemp8 .= '
														<a href="' . $__templater->func('link', array('media/set-as-avatar', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">
															' . 'Set as avatar' . '
														</a>
														';
		if ($__vars['avatarUpdated']) {
			$__compilerTemp8 .= '
															<a href="' . $__templater->func('link', array('account/avatar', ), true) . '" data-xf-click="overlay" data-load-auto-click="true" style="display: none"></a>
														';
		}
		$__compilerTemp8 .= '
													';
	}
	$__compilerTemp8 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canEdit', array())) {
		$__compilerTemp8 .= '
														<a href="' . $__templater->func('link', array('media/edit', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Edit media item' . '</a>
													';
	}
	$__compilerTemp8 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canEditImage', array())) {
		$__compilerTemp8 .= '
														<a href="' . $__templater->func('link', array('media/edit-image', $__vars['mediaItem'], ), true) . '" class="menu-linkRow">' . 'Edit image' . '</a>
													';
	}
	$__compilerTemp8 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canChangeThumbnail', array())) {
		$__compilerTemp8 .= '
														<a href="' . $__templater->func('link', array('media/change-thumbnail', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Change thumbnail' . '</a>
													';
	}
	$__compilerTemp8 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canMove', array())) {
		$__compilerTemp8 .= '
														<a href="' . $__templater->func('link', array('media/move', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Move media item' . '</a>
													';
	}
	$__compilerTemp8 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canDelete', array('soft', ))) {
		$__compilerTemp8 .= '
														<a href="' . $__templater->func('link', array('media/delete', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Delete media item' . '</a>
													';
	}
	$__compilerTemp8 .= '
													';
	if ($__templater->method($__vars['mediaItem'], 'canViewModeratorLogs', array())) {
		$__compilerTemp8 .= '
														<a href="' . $__templater->func('link', array('media/moderator-actions', $__vars['mediaItem'], ), true) . '" data-xf-click="overlay" class="menu-linkRow">' . 'Moderator actions' . '</a>
													';
	}
	$__compilerTemp8 .= '
													' . '
												';
	if (strlen(trim($__compilerTemp8)) > 0) {
		$__compilerTemp6 .= '
									<div class="buttonGroup-buttonWrapper">
										' . $__templater->button('&#8226;&#8226;&#8226;', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'title' => 'More options',
		), '', array(
		)) . '
										<div class="menu" data-menu="menu" aria-hidden="true">
											<div class="menu-content">
												<h4 class="menu-header">' . 'More options' . '</h4>
												' . $__compilerTemp8 . '
											</div>
										</div>
									</div>
								';
	}
	$__compilerTemp6 .= '
							';
	if (strlen(trim($__compilerTemp6)) > 0) {
		$__compilerTemp5 .= '
						<div class="buttonGroup">
							' . $__compilerTemp6 . '
						</div>
					';
	}
	$__compilerTemp5 .= '
				';
	if (strlen(trim($__compilerTemp5)) > 0) {
		$__finalCompiled .= '
			<div class="block-outer-opposite">
				' . $__compilerTemp5 . '
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>

<div class="block">

	<div class="block-container block-container--none">
		<h3 class="block-formSectionHeader block-formSectionHeader--small">
			<span class="collapseTrigger collapseTrigger--block ' . ($__templater->func('is_toggled', array('xfmg_lightbox_media_information', ), false) ? 'is-active' : '') . '" data-xf-click="toggle" data-target="< :up:next" data-xf-init="toggle-storage" data-storage-key="xfmg_lightbox_media_information" data-storage-type="cookie">
				<span>' . 'Media information' . '</span>
			</span>
		</h3>
		<div class="block-body block-row block-body--collapsible">
			' . $__templater->callMacro(null, 'xfmg_media_view_macros::info_sidebar', array(
		'mediaItem' => $__vars['mediaItem'],
		'row' => false,
	), $__vars) . '
		</div>

		';
	$__compilerTemp9 = '';
	$__compilerTemp9 .= $__templater->callMacro(null, 'xfmg_media_view_macros::extra_info_sidebar', array(
		'mediaItem' => $__vars['mediaItem'],
		'row' => false,
	), $__vars);
	if (strlen(trim($__compilerTemp9)) > 0) {
		$__finalCompiled .= '
			<h3 class="block-formSectionHeader block-formSectionHeader--small">
				<span class="collapseTrigger collapseTrigger--block ' . ($__templater->func('is_toggled', array('xfmg_lightbox_extra_information', ), false) ? 'is-active' : '') . '" data-xf-click="toggle" data-target="< :up:next" data-xf-init="toggle-storage" data-storage-key="xfmg_lightbox_extra_information" data-storage-type="cookie">
					<span>' . 'Extra information' . '</span>
				</span>
			</h3>
			<div class="block-body block-row block-body--collapsible">
				' . $__compilerTemp9 . '
			</div>
		';
	}
	$__finalCompiled .= '

		';
	$__compilerTemp10 = '';
	$__compilerTemp10 .= '
				';
	$__compilerTemp11 = $__templater->method($__vars['mediaItem'], 'getExtraFieldBlocks', array());
	if ($__templater->isTraversable($__compilerTemp11)) {
		foreach ($__compilerTemp11 AS $__vars['fieldId'] => $__vars['definition']) {
			$__compilerTemp10 .= '
					';
			if ($__templater->method($__vars['definition'], 'hasValue', array($__vars['mediaItem']['custom_fields'][$__vars['fieldId']], ))) {
				$__compilerTemp10 .= '
						<h3 class="block-formSectionHeader block-formSectionHeader--small">
							<span class="collapseTrigger collapseTrigger--block ' . ($__templater->func('is_toggled', array('xfmg_lightbox_sidebar_' . $__vars['fieldId'], ), false) ? 'is-active' : '') . '" data-xf-click="toggle" data-target="< :up:next" data-xf-init="toggle-storage" data-storage-key="xfmg_lightbox_sidebar_' . $__templater->escape($__vars['fieldId']) . '" data-storage-type="cookie">
								<span>' . $__templater->escape($__vars['definition']['title']) . '</span>
							</span>
						</h3>
						<div class="block-body block-row block-body--collapsible">
							' . $__templater->callMacro('custom_fields_macros', 'custom_field_value', array(
					'definition' => $__vars['definition'],
					'value' => $__vars['mediaItem']['custom_fields'][$__vars['fieldId']],
				), $__vars) . '
						</div>
					';
			}
			$__compilerTemp10 .= '
				';
		}
	}
	$__compilerTemp10 .= '
			';
	if (strlen(trim($__compilerTemp10)) > 0) {
		$__finalCompiled .= '
			' . $__compilerTemp10 . '
		';
	}
	$__finalCompiled .= '

		';
	$__compilerTemp12 = '';
	$__compilerTemp12 .= $__templater->callMacro(null, 'xfmg_media_view_macros::exif_sidebar', array(
		'mediaItem' => $__vars['mediaItem'],
		'row' => false,
	), $__vars);
	if (strlen(trim($__compilerTemp12)) > 0) {
		$__finalCompiled .= '
			<h3 class="block-formSectionHeader block-formSectionHeader--small">
				<span class="collapseTrigger collapseTrigger--block ' . ($__templater->func('is_toggled', array('xfmg_lightbox_exif_sidebar', ), false) ? 'is-active' : '') . '" data-xf-click="toggle" data-target="< :up:next" data-xf-init="toggle-storage" data-storage-key="xfmg_lightbox_exif_sidebar" data-storage-type="cookie">
					<span>' . 'Image metadata' . '</span>
				</span>
			</h3>
			<div class="block-body block-row block-body--collapsible">
				' . $__compilerTemp12 . '
			</div>
		';
	}
	$__finalCompiled .= '

		';
	if ($__templater->method($__vars['mediaItem'], 'canViewComments', array())) {
		$__finalCompiled .= '
			<h3 class="block-formSectionHeader block-formSectionHeader--small">
				<span>' . 'Comments' . '</span>
			</h3>
			<div class="block-body block-row">
				<div class="message-responses js-messageResponses">
					';
		$__vars['firstComment'] = $__templater->filter($__vars['comments'], array(array('first', array()),), false);
		$__finalCompiled .= '
					';
		$__vars['lastComment'] = $__templater->filter($__vars['comments'], array(array('last', array()),), false);
		$__finalCompiled .= '
					';
		if (!$__templater->test($__vars['comments'], 'empty', array())) {
			$__finalCompiled .= '
						<div class="js-replyNewMessageContainer">
							';
			if ($__vars['loadMore']) {
				$__finalCompiled .= '
								<div class="message-responseRow js-commentLoader">
									<a href="' . $__templater->func('link', array('media/load-previous-comments', $__vars['mediaItem'], array('before' => $__vars['firstComment']['comment_date'], ), ), true) . '"
										data-xf-click="comment-loader"
										data-container=".js-commentLoader"
										rel="nofollow">' . 'View previous comments' . $__vars['xf']['language']['ellipsis'] . '</a>
								</div>
							';
			}
			$__finalCompiled .= '
							';
			if ($__templater->isTraversable($__vars['comments'])) {
				foreach ($__vars['comments'] AS $__vars['comment']) {
					$__finalCompiled .= '
								';
					if ($__vars['comment']['comment_state'] == 'deleted') {
						$__finalCompiled .= '
									' . $__templater->callMacro(null, 'xfmg_comment_macros::comment_deleted_lightbox', array(
							'comment' => $__vars['comment'],
							'content' => $__vars['mediaItem'],
							'linkPrefix' => 'media/media-comments',
						), $__vars) . '
								';
					} else {
						$__finalCompiled .= '
									' . $__templater->callMacro(null, 'xfmg_comment_macros::comment_lightbox', array(
							'comment' => $__vars['comment'],
							'content' => $__vars['mediaItem'],
							'linkPrefix' => 'media/media-comments',
						), $__vars) . '
								';
					}
					$__finalCompiled .= '
							';
				}
			}
			$__finalCompiled .= '
						</div>
					';
		} else {
			$__finalCompiled .= '
						<div class="js-replyNewMessageContainer"></div>
					';
		}
		$__finalCompiled .= '
					';
		$__vars['isPreRegComment'] = $__templater->method($__vars['mediaItem'], 'canAddCommentPreReg', array());
		$__finalCompiled .= '
					';
		if ($__templater->method($__vars['mediaItem'], 'canAddComment', array()) OR $__vars['isPreRegComment']) {
			$__finalCompiled .= '
						';
			$__templater->includeJs(array(
				'src' => 'xf/message.js',
				'min' => '1',
			));
			$__finalCompiled .= '
						<div class="message-responseRow">
							' . $__templater->form('

								<div class="comment-inner">
									<span class="comment-avatar">
										' . $__templater->func('avatar', array($__vars['xf']['visitor'], 'xxs', false, array(
			))) . '
									</span>
									<div class="comment-main">
										<div class="editorPlaceholder" data-xf-click="editor-placeholder">
											<div class="editorPlaceholder-editor is-hidden">
												' . $__templater->callMacro('quick_reply_macros', 'editor', array(
				'minHeight' => '40',
				'placeholder' => 'Write a comment' . $__vars['xf']['language']['ellipsis'],
				'submitText' => 'Post comment',
				'deferred' => true,
				'simpleSubmit' => true,
				'lastDate' => $__vars['lastComment']['comment_date'],
				'showGuestControls' => (!$__vars['isPreRegComment']),
			), $__vars) . '
											</div>
											<div class="editorPlaceholder-placeholder">
												<div class="input"><span class="u-muted"> ' . 'Write a comment' . $__vars['xf']['language']['ellipsis'] . '</span></div>
											</div>
										</div>
									</div>
								</div>
							', array(
				'action' => $__templater->func('link', array('media/media-comments/add-comment', $__vars['mediaItem'], array('lightbox' => true, ), ), false),
				'ajax' => 'true',
				'class' => 'comment',
				'data-xf-init' => 'quick-reply',
				'data-message-container' => '< .js-messageResponses | .js-replyNewMessageContainer',
			)) . '
						</div>
					';
		}
		$__finalCompiled .= '
				</div>
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>

';
	$__compilerTemp13 = '';
	$__compilerTemp13 .= '
				' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
		'pageUrl' => $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), false),
		'pageTitle' => $__vars['mediaItem']['title'],
		'pageDesc' => $__vars['mediaItem']['description'],
		'pageImage' => $__templater->method($__vars['mediaItem'], 'getCurrentThumbnailUrl', array(true, )),
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp13)) > 0) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container block-container--none">
			<div class="block-body block-row">
				' . $__compilerTemp13 . '
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
);