<?php
// FROM HASH: eb10faa32cc5287214dac272186306ff
return array(
'macros' => array('update' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'update' => '!',
		'item' => '!',
		'showItem' => false,
		'showAttachments' => true,
		'allowInlineModeration' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('xa_sc_update.less');
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'xf/comment.js',
		'min' => '1',
	));
	$__finalCompiled .= '

	<div class="sc-update message message--simple js-itemUpdate js-inlineModContainer"
		data-author="' . ($__templater->escape($__vars['update']['User']['username']) ?: $__templater->escape($__vars['update']['username'])) . '"
		data-content="item-update-' . $__templater->escape($__vars['update']['item_update_id']) . '"
		id="js-itemUpdate-' . $__templater->escape($__vars['update']['item_update_id']) . '">

		<span class="u-anchorTarget" id="item-update-' . $__templater->escape($__vars['update']['item_update_id']) . '"></span>
		<div class="message-inner">
			<div class="message-cell message-cell--main">
				<div class="js-quickEditTarget">
					<div class="message-content js-messageContent">
						';
	if ($__vars['showItem']) {
		$__finalCompiled .= '
							<div class="message-attribution message-attribution--plain">
								<div class="message-attribution-source">
									' . 'Update by ' . $__templater->func('username_link', array($__vars['item']['User'], false, array('defaultname' => $__vars['item']['username'], ), ), true) . ' for ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true)) . $__templater->escape($__vars['item']['title'])) . '</a>') . ' in ' . (((('<a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true)) . '">') . $__templater->escape($__vars['item']['Category']['title'])) . '</a>') . '' . '
								</div>
							</div>
						';
	}
	$__finalCompiled .= '
						<div class="message-attribution message-attribution--split">
							<h2 class="message-attribution-main block-textHeader block-textHeaderSc" style="margin: 0">
								<a href="' . $__templater->func('link', array('showcase/update', $__vars['update'], ), true) . '" rel="nofollow">' . $__templater->escape($__vars['update']['title']) . '</a>
							</h2>
							<ul class="message-attribution-opposite message-attribution-opposite--list">
								';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
											' . $__templater->callMacro('bookmark_macros', 'link', array(
		'content' => $__vars['update'],
		'confirmUrl' => $__templater->func('link', array('showcase/update/bookmark', $__vars['update'], ), false),
		'class' => 'bookmarkLink--highlightable',
		'showText' => false,
	), $__vars) . '
										';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
									<li>
										' . $__compilerTemp1 . '
									</li>
								';
	}
	$__finalCompiled .= '
								<li>
									<a href="' . $__templater->func('link', array('showcase/update', $__vars['update'], ), true) . '" rel="nofollow">
										' . $__templater->func('date_dynamic', array($__vars['update']['update_date'], array(
	))) . '
									</a>
								</li>
							</ul>
						</div>

						';
	if ($__vars['update']['update_state'] == 'deleted') {
		$__finalCompiled .= '
							<div class="messageNotice messageNotice--deleted">
								' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['update']['DeletionLog'],
		), $__vars) . '
							</div>
						';
	} else if ($__vars['update']['update_state'] == 'moderated') {
		$__finalCompiled .= '
							<div class="messageNotice messageNotice--moderated">
								' . 'This message is awaiting moderator approval, and is invisible to normal visitors.' . '
							</div>
						';
	}
	$__finalCompiled .= '
						';
	if ($__vars['update']['warning_message']) {
		$__finalCompiled .= '
							<div class="messageNotice messageNotice--warning">
								' . $__templater->escape($__vars['update']['warning_message']) . '
							</div>
						';
	}
	$__finalCompiled .= '
						';
	if ($__templater->method($__vars['update'], 'isIgnored', array())) {
		$__finalCompiled .= '
							<div class="messageNotice messageNotice--ignored">
								' . 'You are ignoring content by this member.' . '
							</div>
						';
	}
	$__finalCompiled .= '

						<div class="message-userContent lbContainer js-lbContainer"
							data-lb-id="item_update-' . $__templater->escape($__vars['update']['item_update_id']) . '"
							data-lb-caption-desc="' . ($__vars['item']['User'] ? $__templater->escape($__vars['item']['User']['username']) : $__templater->escape($__vars['update']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['update']['update_date'], ), true) . '">

							' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'sc_updates',
		'group' => 'above',
		'onlyInclude' => $__vars['category']['update_field_cache'],
		'set' => $__vars['update']['custom_fields'],
		'wrapperClass' => 'sc-update-fields sc-update-fields--above',
	), $__vars) . '

							<blockquote class="message-body">
								' . $__templater->func('bb_code', array($__vars['update']['message'], 'pm_update', $__vars['update'], ), true) . '
							</blockquote>

							' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'sc_updates',
		'group' => 'below',
		'onlyInclude' => $__vars['category']['update_field_cache'],
		'set' => $__vars['update']['custom_fields'],
		'wrapperClass' => 'sc-update-fields sc-update-fields--below',
	), $__vars) . '

							';
	if ($__vars['update']['attach_count']) {
		$__finalCompiled .= '
								' . $__templater->callMacro('message_macros', 'attachments', array(
			'attachments' => $__vars['update']['Attachments'],
			'message' => $__vars['update'],
			'canView' => $__templater->method($__vars['item'], 'canViewUpdateImages', array()),
		), $__vars) . '
							';
	}
	$__finalCompiled .= '

							';
	if ($__templater->method($__vars['item'], 'isContributor', array()) AND ($__vars['update']['user_id'] AND ($__vars['update']['user_id'] != $__vars['item']['user_id']))) {
		$__finalCompiled .= '
								<div class="message-lastEdit">
									' . 'Posted by' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('username_link', array($__vars['update']['User'], false, array(
			'defaultname' => $__vars['update']['username'],
		))) . '
								</div>
							';
	}
	$__finalCompiled .= '

							';
	if ($__vars['update']['last_edit_date']) {
		$__finalCompiled .= '
								<div class="message-lastEdit">
									';
		if ($__vars['update']['user_id'] == $__vars['update']['last_edit_user_id']) {
			$__finalCompiled .= '
										' . 'Last edited' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['update']['last_edit_date'], array(
			))) . '
									';
		} else {
			$__finalCompiled .= '
										' . 'Last edited by a moderator' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['update']['last_edit_date'], array(
			))) . '
									';
		}
		$__finalCompiled .= '
								</div>
							';
	}
	$__finalCompiled .= '
						</div>

						';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
									';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
											' . $__templater->func('react', array(array(
		'content' => $__vars['update'],
		'link' => 'showcase/update/react',
		'list' => '< .js-itemUpdate | .js-reactionsList',
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
	if ($__templater->method($__vars['update'], 'canUseInlineModeration', array()) AND $__vars['allowInlineModeration']) {
		$__compilerTemp4 .= '
												<span class="actionBar-action actionBar-action--inlineMod">
													' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['update']['item_update_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => 'Select for moderation',
			'label' => 'Select for moderation',
			'hiddenlabel' => 'true',
			'_type' => 'option',
		))) . '
												</span>
											';
	}
	$__compilerTemp4 .= '
											';
	if ($__templater->method($__vars['update'], 'canReport', array())) {
		$__compilerTemp4 .= '
												<a href="' . $__templater->func('link', array('showcase/update/report', $__vars['update'], ), true) . '"
													class="actionBar-action actionBar-action--report"
													data-xf-click="overlay">' . 'Report' . '</a>
											';
	}
	$__compilerTemp4 .= '

											';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp4 .= '
											';
	if ($__templater->method($__vars['update'], 'canEdit', array())) {
		$__compilerTemp4 .= '
												';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__compilerTemp4 .= '
												<a href="' . $__templater->func('link', array('showcase/update/edit', $__vars['update'], ), true) . '"
													class="actionBar-action actionBar-action--edit actionBar-action--menuItem"
													data-xf-click="quick-edit"
													data-editor-target="#js-itemUpdate-' . $__templater->escape($__vars['update']['item_update_id']) . ' .js-quickEditTarget"
													data-menu-closer="true">' . 'Edit' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
											';
	}
	$__compilerTemp4 .= '
											';
	if ($__vars['update']['edit_count'] AND $__templater->method($__vars['update'], 'canViewHistory', array())) {
		$__compilerTemp4 .= '
												<a href="' . $__templater->func('link', array('showcase/update/history', $__vars['update'], ), true) . '"
													class="actionBar-action actionBar-action--history actionBar-action--menuItem"
													data-xf-click="toggle"
													data-target="#js-itemUpdate-' . $__templater->escape($__vars['update']['item_update_id']) . ' .js-historyTarget"
													data-menu-closer="true">' . 'History' . '</a>

												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
											';
	}
	$__compilerTemp4 .= '
											';
	if ($__templater->method($__vars['update'], 'canDelete', array('soft', ))) {
		$__compilerTemp4 .= '
												<a href="' . $__templater->func('link', array('showcase/update/delete', $__vars['update'], ), true) . '"
													class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
													data-xf-click="overlay">' . 'Delete' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
											';
	}
	$__compilerTemp4 .= '
											';
	if (($__vars['update']['update_state'] == 'deleted') AND $__templater->method($__vars['update'], 'canUndelete', array())) {
		$__compilerTemp4 .= '
												<a href="' . $__templater->func('link', array('showcase/update/undelete', $__vars['update'], ), true) . '" data-xf-click="overlay"
													class="actionBar-action actionBar-action--undelete actionBar-action--menuItem">' . 'Undelete' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
											';
	}
	$__compilerTemp4 .= '
											';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['update']['ip_id']) {
		$__compilerTemp4 .= '
												<a href="' . $__templater->func('link', array('showcase/update/ip', $__vars['update'], ), true) . '"
													class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
													data-xf-click="overlay">' . 'IP' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
											';
	}
	$__compilerTemp4 .= '
											';
	if ($__templater->method($__vars['update'], 'canApproveUnapprove', array())) {
		$__compilerTemp4 .= '
												';
		if ($__vars['update']['update_state'] == 'moderated') {
			$__compilerTemp4 .= '
													<a href="' . $__templater->func('link', array('showcase/update/approve', $__vars['update'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
														class="actionBar-action actionBar-action--approve actionBar-action--menuItem">' . 'Approve' . '</a>
													';
			$__vars['hasActionBarMenu'] = true;
			$__compilerTemp4 .= '
												';
		} else if ($__vars['update']['update_state'] == 'visible') {
			$__compilerTemp4 .= '
													<a href="' . $__templater->func('link', array('showcase/update/unapprove', $__vars['update'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
														class="actionBar-action actionBar-action--unapprove actionBar-action--menuItem">' . 'Unapprove' . '</a>
													';
			$__vars['hasActionBarMenu'] = true;
			$__compilerTemp4 .= '
												';
		}
		$__compilerTemp4 .= '
											';
	}
	$__compilerTemp4 .= '
											';
	if ($__templater->method($__vars['update'], 'canWarn', array())) {
		$__compilerTemp4 .= '
												<a href="' . $__templater->func('link', array('showcase/update/warn', $__vars['update'], ), true) . '" 
													class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
												';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp4 .= '
											';
	} else if ($__vars['update']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp4 .= ' 
												<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['update']['warning_id'], ), ), true) . '" 
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
							<div class="message-actionBar actionBar">
								' . $__compilerTemp2 . '
							</div>
						';
	}
	$__finalCompiled .= '

						';
	if ($__vars['update']['edit_count'] AND $__templater->method($__vars['update'], 'canViewHistory', array())) {
		$__finalCompiled .= '
							<div class="js-historyTarget toggleTarget" data-href="trigger-href"></div>
						';
	}
	$__finalCompiled .= '

						<section class="message-responses js-messageResponses">
							<div class="message-responseRow message-responseRow--reactions js-reactionsList ' . ($__vars['update']['reactions'] ? 'is-active' : '') . '">';
	if ($__vars['update']['reactions']) {
		$__finalCompiled .= '
								' . $__templater->func('reactions', array($__vars['update'], 'showcase/update/reactions', array())) . '
							';
	}
	$__finalCompiled .= '</div>

							';
	if (!$__templater->test($__vars['update']['LatestReplies'], 'empty', array())) {
		$__finalCompiled .= '
								';
		if ($__templater->method($__vars['update'], 'hasMoreReplies', array())) {
			$__finalCompiled .= '
									<div class="message-responseRow u-jsOnly js-commentLoader">
										<a href="' . $__templater->func('link', array('showcase/update/load-previous', $__vars['update'], array('before' => $__templater->arrayKey($__templater->method($__vars['update']['LatestReplies'], 'first', array()), 'reply_date'), ), ), true) . '"
											data-xf-click="comment-loader"
											data-container=".js-commentLoader"
											rel="nofollow">' . 'View previous replies' . $__vars['xf']['language']['ellipsis'] . '</a>
									</div>
								';
		}
		$__finalCompiled .= '
								<div class="js-replyNewMessageContainer">
									';
		if ($__templater->isTraversable($__vars['update']['LatestReplies'])) {
			foreach ($__vars['update']['LatestReplies'] AS $__vars['reply']) {
				$__finalCompiled .= '
										' . $__templater->callMacro(null, (($__vars['reply']['reply_state'] == 'deleted') ? 'reply_deleted' : 'reply'), array(
					'reply' => $__vars['reply'],
					'update' => $__vars['update'],
				), $__vars) . '
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
	if ($__templater->method($__vars['update'], 'canReply', array())) {
		$__finalCompiled .= '
								';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__finalCompiled .= '
								<div class="message-responseRow js-commentsTarget-' . $__templater->escape($__vars['update']['item_update_id']) . '">
									';
		$__vars['lastItemUpdateReply'] = $__templater->filter($__vars['update']['LatestReplies'], array(array('last', array()),), false);
		$__finalCompiled .= $__templater->form('
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
		), $__vars) . '
													</div>
													<div class="editorPlaceholder-placeholder">
														<div class="input"><span class="u-muted"> ' . 'Write a comment' . $__vars['xf']['language']['ellipsis'] . '</span></div>
													</div>
												</div>
											</div>
										</div>
										' . '' . '
										' . $__templater->formHiddenVal('last_date', $__vars['lastItemUpdateReply']['reply_date'], array(
		)) . '
									', array(
			'action' => $__templater->func('link', array('showcase/update/add-reply', $__vars['update'], ), false),
			'ajax' => 'true',
			'class' => 'comment',
			'data-xf-init' => 'quick-reply',
			'data-message-container' => '< .js-messageResponses | .js-replyNewMessageContainer',
		)) . '
								</div>
							';
	}
	$__finalCompiled .= '
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'update_simple' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'update' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['update']['User'], 'xxs', false, array(
	))) . '
		</div>
		
		<div class="contentRow-main contentRow-main--close">
			<a href="' . $__templater->func('link', array('showcase/update', $__vars['update'], ), true) . '">' . $__templater->escape($__vars['update']['Item']['title']) . ': ' . $__templater->escape($__vars['update']['title']) . '</a>

			<div class="contentRow-snippet">
				' . $__templater->func('smilie', array($__templater->func('snippet', array($__vars['update']['message'], 150, array('stripBbCode' => true, 'stripQuote' => true, ), ), false), ), true) . '
			</div>
			
			<div class="contentRow-minor contentRow-minor--smaller">
				<ul class="listInline listInline--bullet">
					<li>
						' . ($__templater->escape($__vars['update']['User']['username']) ?: $__templater->escape($__vars['update']['username'])) . '
					</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['update']['update_date'], array(
	))) . '</li>
				</ul>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'reply' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reply' => '!',
		'update' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="message-responseRow ' . ($__templater->method($__vars['reply'], 'isIgnored', array()) ? 'is-ignored' : '') . '">
		<div class="comment"
			data-author="' . $__templater->escape($__vars['reply']['User']['username']) . '"
			data-content="item-update-reply-' . $__templater->escape($__vars['reply']['reply_id']) . '"
			id="js-itemUpdateReply-' . $__templater->escape($__vars['reply']['reply_id']) . '">

			<div class="comment-inner">
				<span class="comment-avatar">
					' . $__templater->func('avatar', array($__vars['reply']['User'], 'xxs', false, array(
		'defaultname' => $__vars['reply']['username'],
	))) . '
				</span>
				<div class="comment-main">
					<span class="u-anchorTarget" id="item-update-reply-' . $__templater->escape($__vars['reply']['reply_id']) . '"></span>
					<div class="js-quickEditTargetComment">
						<div class="comment-content">
							';
	if ($__vars['reply']['reply_state'] == 'deleted') {
		$__finalCompiled .= '
								<div class="messageNotice messageNotice--deleted">
									' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['reply']['DeletionLog'],
		), $__vars) . '
								</div>
							';
	} else if ($__vars['reply']['reply_state'] == 'moderated') {
		$__finalCompiled .= '
								<div class="messageNotice messageNotice--moderated">
									' . 'This message is awaiting moderator approval, and is invisible to normal visitors.' . '
								</div>
							';
	}
	$__finalCompiled .= '
							';
	if ($__vars['reply']['warning_message']) {
		$__finalCompiled .= '
								<div class="messageNotice messageNotice--warning">
									' . $__templater->escape($__vars['reply']['warning_message']) . '
								</div>
							';
	}
	$__finalCompiled .= '
							';
	if ($__templater->method($__vars['reply'], 'isIgnored', array())) {
		$__finalCompiled .= '
								<div class="messageNotice messageNotice--ignored">
									' . 'You are ignoring content by this member.' . '
								</div>
							';
	}
	$__finalCompiled .= '

							<div class="comment-contentWrapper">
								' . $__templater->func('username_link', array($__vars['reply']['User'], true, array(
		'defaultname' => $__vars['reply']['username'],
		'class' => 'comment-user',
	))) . '
								<article class="comment-body">' . $__templater->func('bb_code', array($__vars['reply']['message'], 'sc_update_reply', $__vars['reply'], ), true) . '</article>
							</div>
						</div>

						<footer class="comment-footer">
							<div class="comment-actionBar actionBar">
								<div class="actionBar-set actionBar-set--internal">
									<span class="actionBar-action">' . $__templater->func('date_dynamic', array($__vars['reply']['reply_date'], array(
	))) . '</span>
									';
	if ($__templater->method($__vars['reply'], 'canReport', array())) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('showcase/update-reply/report', $__vars['reply'], ), true) . '"
											class="actionBar-action actionBar-action--report"
											data-xf-click="overlay">' . 'Report' . '</a>
									';
	}
	$__finalCompiled .= '

									';
	$__vars['hasActionBarMenu'] = false;
	$__finalCompiled .= '
									';
	if ($__templater->method($__vars['reply'], 'canEdit', array())) {
		$__finalCompiled .= '
										';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('showcase/update-reply/edit', $__vars['reply'], ), true) . '"
											class="actionBar-action actionBar-action--edit actionBar-action--menuItem"
											data-xf-click="quick-edit"
											data-editor-target="#js-itemUpdateReply-' . $__templater->escape($__vars['reply']['reply_id']) . ' .js-quickEditTargetComment"
											data-menu-closer="true">' . 'Edit' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '
									';
	if ($__templater->method($__vars['reply'], 'canDelete', array('soft', ))) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('showcase/update-reply/delete', $__vars['reply'], ), true) . '"
											class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
											data-xf-click="overlay">' . 'Delete' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '
									';
	if (($__vars['reply']['reply_state'] == 'deleted') AND $__templater->method($__vars['reply'], 'canUndelete', array())) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('showcase/update-reply/undelete', $__vars['reply'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
											class="actionBar-action actionBar-action--undelete actionBar-action--menuItem">' . 'Undelete' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '
									';
	if ($__templater->method($__vars['reply'], 'canCleanSpam', array())) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('spam-cleaner', $__vars['reply'], ), true) . '"
											class="actionBar-action actionBar-action--spam actionBar-action--menuItem"
											data-xf-click="overlay">' . 'Spam' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '
									';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['reply']['ip_id']) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('showcase/update-reply/ip', $__vars['reply'], ), true) . '"
											class="actionBar-action actionBar-action--ip actionBar-action--menuItem"
											data-xf-click="overlay">' . 'IP' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '
									';
	if ($__templater->method($__vars['reply'], 'canWarn', array())) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('showcase/update-reply/warn', $__vars['reply'], ), true) . '"
											class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	} else if ($__vars['reply']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['reply']['warning_id'], ), ), true) . '"
											class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
											data-xf-click="overlay">' . 'View warning' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '
									';
	if ($__templater->method($__vars['reply'], 'canApproveUnapprove', array())) {
		$__finalCompiled .= '
										';
		if ($__vars['reply']['reply_state'] == 'moderated') {
			$__finalCompiled .= '
											<a href="' . $__templater->func('link', array('showcase/update-reply/approve', $__vars['reply'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
												class="actionBar-action actionBar-action--approve actionBar-action--menuItem">' . 'Approve' . '</a>
											';
			$__vars['hasActionBarMenu'] = true;
			$__finalCompiled .= '
										';
		} else if ($__vars['reply']['reply_state'] == 'visible') {
			$__finalCompiled .= '
											<a href="' . $__templater->func('link', array('showcase/update-reply/unapprove', $__vars['reply'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
												class="actionBar-action actionBar-action--unapprove actionBar-action--menuItem">' . 'Unapprove' . '</a>
											';
			$__vars['hasActionBarMenu'] = true;
			$__finalCompiled .= '
										';
		}
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '

									';
	if ($__vars['hasActionBarMenu']) {
		$__finalCompiled .= '
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
	$__finalCompiled .= '
								</div>
								';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
										' . $__templater->func('react', array(array(
		'content' => $__vars['reply'],
		'link' => 'showcase/update-reply/react',
		'list' => '< .comment | .js-replyReactionsList',
	))) . '
									';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
									<div class="actionBar-set actionBar-set--external">
									' . $__compilerTemp1 . '
									</div>
								';
	}
	$__finalCompiled .= '
							</div>

							<div class="comment-reactions js-replyReactionsList ' . ($__vars['reply']['reactions'] ? 'is-active' : '') . '">';
	if ($__vars['reply']['reactions']) {
		$__finalCompiled .= '
								' . $__templater->func('reactions', array($__vars['reply'], 'showcase/update-reply/reactions', array())) . '
							';
	}
	$__finalCompiled .= '</div>
						</footer>

					</div>
				</div>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'reply_deleted' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reply' => '!',
		'update' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="message-responseRow">
		<div class="comment' . ($__templater->method($__vars['reply'], 'isIgnored', array()) ? ' is-ignored' : '') . '"
			data-author="' . $__templater->escape($__vars['reply']['User']['username']) . '"
			data-content="item-update-reply-' . $__templater->escape($__vars['reply']['reply_id']) . '">

			<div class="comment-inner">
				<span class="comment-avatar">
					' . $__templater->func('avatar', array($__vars['reply']['User'], 'xxs', false, array(
		'defaultname' => $__vars['reply']['username'],
	))) . '
				</span>
				<div class="comment-main">
					<span class="u-anchorTarget" id="item-update-reply-' . $__templater->escape($__vars['reply']['reply_id']) . '"></span>
					<div class="comment-content">
						<div class="messageNotice messageNotice--deleted">
							' . $__templater->callMacro('deletion_macros', 'notice', array(
		'log' => $__vars['reply']['DeletionLog'],
	), $__vars) . '

							<a href="' . $__templater->func('link', array('showcase/update-reply/show', $__vars['reply'], ), true) . '" class="u-jsOnly"
								data-xf-click="inserter"
								data-replace="[data-content=item-update-reply-' . $__templater->escape($__vars['reply']['reply_id']) . ']">' . 'Show' . $__vars['xf']['language']['ellipsis'] . '</a>
						</div>
					</div>

					<div class="comment-actionBar actionBar">
						<div class="actionBar-set actionBar-set--internal">
							<span class="actionBar-action">
								' . $__templater->func('date_dynamic', array($__vars['reply']['reply_date'], array(
	))) . '
								<span role="presentation" aria-hidden="true">&middot;</span>
								' . $__templater->func('username_link', array($__vars['reply']['User'], false, array(
		'defaultname' => $__vars['reply']['username'],
		'class' => 'u-concealed',
	))) . '
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
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