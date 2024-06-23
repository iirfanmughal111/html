<?php
// FROM HASH: 843816026fc78d6488bded281722050a
return array(
'macros' => array('review' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'review' => '!',
		'item' => '!',
		'showItem' => false,
		'showAttachments' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('xa_sc_review.less');
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'xf/comment.js',
		'min' => '1',
	));
	$__finalCompiled .= '


	<div class="sc-review message message--simple' . ($__templater->method($__vars['review'], 'isIgnored', array()) ? ' is-ignored' : '') . ' js-review js-inlineModContainer"
		data-author="' . (($__vars['review']['is_anonymous'] ? 'Anonymous' : $__templater->escape($__vars['review']['User']['username'])) ?: $__templater->escape($__vars['review']['username'])) . '"
		data-content="item-review-' . $__templater->escape($__vars['review']['rating_id']) . '"
		id="js-review-' . $__templater->escape($__vars['review']['rating_id']) . '">

		<span class="u-anchorTarget" id="review-' . $__templater->escape($__vars['review']['rating_id']) . '"></span>
		<div class="message-inner">
			<span class="message-cell message-cell--user">
				';
	if ($__vars['review']['is_anonymous']) {
		$__finalCompiled .= '
					' . $__templater->callMacro('message_macros', 'user_info_simple', array(
			'user' => null,
			'fallbackName' => '',
		), $__vars) . '
				';
	} else {
		$__finalCompiled .= '
					' . $__templater->callMacro('message_macros', 'user_info_simple', array(
			'user' => $__vars['review']['User'],
			'fallbackName' => 'Deleted member',
		), $__vars) . '
				';
	}
	$__finalCompiled .= '
			</span>
			<div class="message-cell message-cell--main">
				<div class="js-quickEditTarget">
					<div class="message-content js-messageContent">
						<div class="message-attribution message-attribution--plain">
							';
	if ($__vars['showItem']) {
		$__finalCompiled .= '
								<div class="message-attribution-source">
									' . 'For ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true)) . $__templater->escape($__vars['item']['title'])) . '</a>') . ' in ' . (((('<a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true)) . '">') . $__templater->escape($__vars['item']['Category']['title'])) . '</a>') . '' . '
								</div>
							';
	}
	$__finalCompiled .= '

							<ul class="listInline listInline--bullet">
								<li class="message-attribution-user">
									';
	if ($__vars['review']['is_anonymous']) {
		$__finalCompiled .= '
										' . $__templater->func('username_link', array(null, false, array(
			'defaultname' => 'Anonymous',
		))) . '
										';
		if ($__templater->method($__vars['review'], 'canViewAnonymousAuthor', array())) {
			$__finalCompiled .= '
											(' . $__templater->func('username_link', array($__vars['review']['User'], false, array(
				'defaultname' => 'Deleted member',
			))) . ')
										';
		}
		$__finalCompiled .= '
									';
	} else {
		$__finalCompiled .= '
										' . $__templater->func('username_link', array($__vars['review']['User'], false, array(
			'defaultname' => 'Deleted member',
		))) . '
									';
	}
	$__finalCompiled .= '
								</li>
								<li>
									' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['review']['rating'],
		'class' => 'ratingStars--smaller',
	), $__vars) . '
								</li>
								<li><a href="' . $__templater->func('link', array('showcase/review', $__vars['review'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['review']['rating_date'], array(
	))) . '</a></li>
							</ul>
						</div>

						';
	if ($__vars['review']['rating_state'] == 'deleted') {
		$__finalCompiled .= '
							<div class="messageNotice messageNotice--deleted">
								' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['review']['DeletionLog'],
		), $__vars) . '
							</div>
						';
	} else if ($__vars['review']['rating_state'] == 'moderated') {
		$__finalCompiled .= '
							<div class="messageNotice messageNotice--moderated">
								' . 'This message is awaiting moderator approval, and is invisible to normal visitors.' . '
							</div>
						';
	}
	$__finalCompiled .= '
						';
	if ($__vars['review']['warning_message']) {
		$__finalCompiled .= '
							<div class="messageNotice messageNotice--warning">
								' . $__templater->escape($__vars['review']['warning_message']) . '
							</div>
						';
	}
	$__finalCompiled .= '
						';
	if ($__templater->method($__vars['review'], 'isIgnored', array())) {
		$__finalCompiled .= '
							<div class="messageNotice messageNotice--ignored">
								' . 'You are ignoring content by this member.' . '
							</div>
						';
	}
	$__finalCompiled .= '

						<div class="message-userContent lbContainer js-lbContainer"
							data-lb-id="sc_review-' . $__templater->escape($__vars['review']['rating_id']) . '"
							data-lb-caption-desc="' . (($__vars['review']['is_anonymous'] ? 'Anonymous' : $__templater->escape($__vars['review']['User']['username'])) ?: $__templater->escape($__vars['review']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['review']['rating_date'], ), true) . '">

							';
	if ($__vars['review']['title'] != '') {
		$__finalCompiled .= '
								<article class="message-body">
									<span class="review-title">' . $__templater->func('snippet', array($__vars['review']['title'], 100, array('stripBbCode' => true, ), ), true) . '</span>
								</article>
							';
	}
	$__finalCompiled .= '

							' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'sc_reviews',
		'group' => 'top',
		'onlyInclude' => $__vars['category']['review_field_cache'],
		'set' => $__vars['review']['custom_fields'],
		'wrapperClass' => 'sc-review-fields sc-review-fields--top',
	), $__vars) . '

							';
	if ($__vars['review']['pros']) {
		$__finalCompiled .= '
								<div class="message-body sc-pros-container">
									<span class="pros-header">' . 'Pros' . '</span>: <span class="pros-text">' . $__templater->func('structured_text', array($__vars['review']['pros'], ), true) . '</span>
								</div>
							';
	}
	$__finalCompiled .= '

							';
	if ($__vars['review']['cons']) {
		$__finalCompiled .= '
								<div class="message-body sc-cons-container">
									<span class="cons-header">' . 'Cons' . '</span>: <span class="cons-text">' . $__templater->func('structured_text', array($__vars['review']['cons'], ), true) . '</span>
								</div>
							';
	}
	$__finalCompiled .= '

							' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'sc_reviews',
		'group' => 'middle',
		'onlyInclude' => $__vars['category']['review_field_cache'],
		'set' => $__vars['review']['custom_fields'],
		'wrapperClass' => 'sc-review-fields sc-review-fields--middle',
	), $__vars) . '

							<article class="message-body">
								' . $__templater->func('bb_code', array($__vars['review']['message'], 'sc_rating', $__vars['review'], ), true) . '
							</article>

							' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'sc_reviews',
		'group' => 'bottom',
		'onlyInclude' => $__vars['category']['review_field_cache'],
		'set' => $__vars['review']['custom_fields'],
		'wrapperClass' => 'sc-review-fields sc-review-fields--bottom',
	), $__vars) . '

							';
	if ($__vars['review']['attach_count'] AND $__vars['showAttachments']) {
		$__finalCompiled .= '
								' . $__templater->callMacro('message_macros', 'attachments', array(
			'attachments' => $__vars['review']['Attachments'],
			'message' => $__vars['review'],
			'canView' => $__templater->method($__vars['review'], 'canViewReviewImages', array()),
		), $__vars) . '
							';
	}
	$__finalCompiled .= '
						</div>

						';
	if ($__vars['review']['last_edit_date']) {
		$__finalCompiled .= '
							<div class="message-lastEdit">
								';
		if ($__vars['review']['user_id'] == $__vars['review']['last_edit_user_id']) {
			$__finalCompiled .= '
									' . 'Last edited' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['review']['last_edit_date'], array(
			))) . '
								';
		} else {
			$__finalCompiled .= '
									' . 'Last edited by a moderator' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['review']['last_edit_date'], array(
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
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
								';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
										' . $__templater->func('react', array(array(
		'content' => $__vars['review'],
		'link' => 'showcase/review/react',
		'list' => '< .js-review | .js-reactionsList',
	))) . '
									';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
									<div class="actionBar-set actionBar-set--external">
									' . $__compilerTemp2 . '
									</div>
								';
	}
	$__compilerTemp1 .= '

								';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
										';
	if ($__templater->method($__vars['review'], 'canUseInlineModeration', array())) {
		$__compilerTemp3 .= '
											<span class="actionBar-action actionBar-action--inlineMod">
												' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['review']['rating_id'],
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
	$__compilerTemp3 .= '
										';
	if ($__templater->method($__vars['review'], 'canReport', array())) {
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('showcase/review/report', $__vars['review'], ), true) . '" 
												class="actionBar-action actionBar-action--report" 
												data-xf-click="overlay">' . 'Report' . '</a>
										';
	}
	$__compilerTemp3 .= '

										';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp3 .= '
										';
	if ($__templater->method($__vars['review'], 'canEdit', array())) {
		$__compilerTemp3 .= '
											';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('showcase/review/edit', $__vars['review'], ), true) . '"
												class="actionBar-action actionBar-action--edit actionBar-action--menuItem"
												data-xf-click="quick-edit"
												data-editor-target="#js-review-' . $__templater->escape($__vars['review']['rating_id']) . ' .js-quickEditTarget"
												data-menu-closer="true">' . 'Edit' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	}
	$__compilerTemp3 .= '
										';
	if ($__vars['review']['edit_count'] AND $__templater->method($__vars['review'], 'canViewHistory', array())) {
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('showcase/review/history', $__vars['review'], ), true) . '" 
												class="actionBar-action actionBar-action--history actionBar-action--menuItem"
												data-xf-click="toggle"
												data-target="#js-review-' . $__templater->escape($__vars['review']['rating_id']) . ' .js-historyTarget"
												data-menu-closer="true">' . 'History' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	}
	$__compilerTemp3 .= '
										';
	if ($__templater->method($__vars['review'], 'canDelete', array('soft', ))) {
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('showcase/review/delete', $__vars['review'], ), true) . '" 
												class="actionBar-action actionBar-action--delete actionBar-action--menuItem" 
												data-xf-click="overlay">' . 'Delete' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	}
	$__compilerTemp3 .= '
										';
	if (($__vars['review']['rating_state'] == 'deleted') AND $__templater->method($__vars['review'], 'canUndelete', array())) {
		$__compilerTemp3 .= ' 
											<a href="' . $__templater->func('link', array('showcase/review/undelete', $__vars['review'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '" 
												class="actionBar-action actionBar-action--undelete actionBar-action--menuItem">' . 'Undelete' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	}
	$__compilerTemp3 .= '
										';
	if ($__templater->method($__vars['review'], 'canReassign', array())) {
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('showcase/review/reassign', $__vars['review'], ), true) . '" 
												class="actionBar-action actionBar-action--reassign actionBar-action--menuItem" 
												data-xf-click="overlay">' . 'Reassign' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	}
	$__compilerTemp3 .= '
										';
	if ($__templater->method($__vars['review'], 'canChangeDate', array())) {
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('showcase/review/change-date', $__vars['review'], ), true) . '" 
												class="actionBar-action actionBar-action--date actionBar-action--menuItem" 
												data-xf-click="overlay">' . 'Change date' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	}
	$__compilerTemp3 .= '										
										';
	if ($__templater->method($__vars['review'], 'canCleanSpam', array())) {
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('spam-cleaner', $__vars['review'], ), true) . '" 
												class="actionBar-action actionBar-action--spam actionBar-action--menuItem" 
												data-xf-click="overlay">' . 'Spam' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	}
	$__compilerTemp3 .= '
										';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewIps', array()) AND $__vars['review']['ip_id']) {
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('showcase/review/ip', $__vars['review'], ), true) . '" 
												class="actionBar-action actionBar-action--ip actionBar-action--menuItem" 
												data-xf-click="overlay">' . 'IP' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	}
	$__compilerTemp3 .= '
										';
	if ($__templater->method($__vars['review'], 'canWarn', array())) {
		$__compilerTemp3 .= '
											<a href="' . $__templater->func('link', array('showcase/review/warn', $__vars['review'], ), true) . '" 
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem">' . 'Warn' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '
										';
	} else if ($__vars['review']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp3 .= ' 
											<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['review']['warning_id'], ), ), true) . '" 
												class="actionBar-action actionBar-action--warn actionBar-action--menuItem" 
												data-xf-click="overlay">' . 'View warning' . '</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp3 .= '							
										';
	}
	$__compilerTemp3 .= '

										';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp3 .= '
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
	$__compilerTemp3 .= '
									';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp1 .= '
									<div class="actionBar-set actionBar-set--internal">
									' . $__compilerTemp3 . '
									</div>
								';
	}
	$__compilerTemp1 .= '
							';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
						<div class="message-actionBar actionBar">
							' . $__compilerTemp1 . '
						</div>
					';
	}
	$__finalCompiled .= '

					<div class="js-historyTarget toggleTarget" data-href="trigger-href"></div>

					<section class="message-responses js-messageResponses">
						<div class="message-responseRow message-responseRow--reactions js-reactionsList ' . ($__vars['review']['reactions'] ? 'is-active' : '') . '">';
	if ($__vars['review']['reactions']) {
		$__finalCompiled .= '
							' . $__templater->func('reactions', array($__vars['review'], 'showcase/review/reactions', array())) . '
						';
	}
	$__finalCompiled .= '</div>

						';
	if (!$__templater->test($__vars['review']['LatestReplies'], 'empty', array())) {
		$__finalCompiled .= '
							';
		if ($__templater->method($__vars['review'], 'hasMoreReplies', array())) {
			$__finalCompiled .= '
								<div class="message-responseRow u-jsOnly js-commentLoader">
									<a href="' . $__templater->func('link', array('showcase/review/load-previous', $__vars['review'], array('before' => $__templater->arrayKey($__templater->method($__vars['review']['LatestReplies'], 'first', array()), 'reply_date'), ), ), true) . '"
										data-xf-click="comment-loader"
										data-container=".js-commentLoader"
										rel="nofollow">' . 'View previous replies' . $__vars['xf']['language']['ellipsis'] . '</a>
								</div>
							';
		}
		$__finalCompiled .= '
							<div class="js-replyNewMessageContainer">
								';
		if ($__templater->isTraversable($__vars['review']['LatestReplies'])) {
			foreach ($__vars['review']['LatestReplies'] AS $__vars['reply']) {
				$__finalCompiled .= '
									' . $__templater->callMacro(null, (($__vars['reply']['reply_state'] == 'deleted') ? 'reply_deleted' : 'reply'), array(
					'reply' => $__vars['reply'],
					'review' => $__vars['review'],
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
	if ($__templater->method($__vars['review'], 'canReply', array())) {
		$__finalCompiled .= '
							';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__finalCompiled .= '
							<div class="message-responseRow js-commentsTarget-' . $__templater->escape($__vars['review']['rating_id']) . '">
								';
		$__vars['lastItemRatingReply'] = $__templater->filter($__vars['review']['LatestReplies'], array(array('last', array()),), false);
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
									' . $__templater->formHiddenVal('last_date', $__vars['lastItemRatingReply']['reply_date'], array(
		)) . '
								', array(
			'action' => $__templater->func('link', array('showcase/review/add-reply', $__vars['review'], ), false),
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

			';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
							';
	if ($__templater->method($__vars['review'], 'isContentVotingSupported', array())) {
		$__compilerTemp4 .= '
								' . $__templater->callMacro('content_vote_macros', 'vote_control', array(
			'link' => 'showcase/review/vote',
			'content' => $__vars['review'],
		), $__vars) . '
							';
	}
	$__compilerTemp4 .= '
						';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__finalCompiled .= '
				<div class="message-cell message-cell--vote">
					<div class="message-column">
						' . $__compilerTemp4 . '
					</div>
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'review_simple' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'review' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('avatar', array(($__vars['review']['is_anonymous'] ? null : $__vars['review']['User']), 'xxs', false, array(
	))) . '
		</div>
		
		<div class="contentRow-main contentRow-main--close">
			<a href="' . $__templater->func('link', array('showcase/review', $__vars['review'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['review']['Item'], ), true) . $__templater->escape($__vars['review']['Item']['title']) . '</a>

			<div class="contentRow-snippet contentRow-lesser">
				' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['review']['rating'],
	), $__vars) . '
			</div>
			
			';
	if ($__vars['review']['title'] != '') {
		$__finalCompiled .= '
				<div class="contentRow-snippet contentRow-lesser">
					<span class="review-title">' . $__templater->func('snippet', array($__vars['review']['title'], 100, array('stripBbCode' => true, ), ), true) . '</span>
				</div>	
			';
	}
	$__finalCompiled .= '

			<div class="contentRow-snippet">
				' . $__templater->func('smilie', array($__templater->func('snippet', array($__vars['review']['message'], 150, array('stripBbCode' => true, 'stripQuote' => true, ), ), false), ), true) . '
			</div>			
			
			<div class="contentRow-minor contentRow-minor--smaller">
				<ul class="listInline listInline--bullet">
					<li>
						';
	if ($__vars['review']['is_anonymous']) {
		$__finalCompiled .= '
							' . 'Anonymous' . '
						';
	} else {
		$__finalCompiled .= '
							' . ($__templater->escape($__vars['review']['User']['username']) ?: $__templater->escape($__vars['review']['username'])) . '
						';
	}
	$__finalCompiled .= '
					</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['review']['rating_date'], array(
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
		'review' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="message-responseRow ' . ($__templater->method($__vars['reply'], 'isIgnored', array()) ? 'is-ignored' : '') . '">
		<div class="comment"
			data-author="' . $__templater->escape($__vars['reply']['User']['username']) . '"
			data-content="item-rating-reply-' . $__templater->escape($__vars['reply']['reply_id']) . '"
			id="js-itemRatingReply-' . $__templater->escape($__vars['reply']['reply_id']) . '">

			<div class="comment-inner">
				<span class="comment-avatar">
					' . $__templater->func('avatar', array($__vars['reply']['User'], 'xxs', false, array(
		'defaultname' => $__vars['reply']['username'],
	))) . '
				</span>
				<div class="comment-main">
					<span class="u-anchorTarget" id="item-rating-reply-' . $__templater->escape($__vars['reply']['reply_id']) . '"></span>
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
								<article class="comment-body">' . $__templater->func('bb_code', array($__vars['reply']['message'], 'sc_rating_reply', $__vars['reply'], ), true) . '</article>
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
										<a href="' . $__templater->func('link', array('showcase/review-reply/report', $__vars['reply'], ), true) . '"
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
										<a href="' . $__templater->func('link', array('showcase/review-reply/edit', $__vars['reply'], ), true) . '"
											class="actionBar-action actionBar-action--edit actionBar-action--menuItem"
											data-xf-click="quick-edit"
											data-editor-target="#js-itemRatingReply-' . $__templater->escape($__vars['reply']['reply_id']) . ' .js-quickEditTargetComment"
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
										<a href="' . $__templater->func('link', array('showcase/review-reply/delete', $__vars['reply'], ), true) . '"
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
										<a href="' . $__templater->func('link', array('showcase/review-reply/undelete', $__vars['reply'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
											class="actionBar-action actionBar-action--undelete actionBar-action--menuItem">' . 'Undelete' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '
									';
	if ($__templater->method($__vars['reply'], 'canReassign', array())) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('showcase/review-reply/reassign', $__vars['reply'], ), true) . '" 
											class="actionBar-action actionBar-action--reassign actionBar-action--menuItem" 
											data-xf-click="overlay">' . 'Reassign' . '</a>
										';
		$__vars['hasActionBarMenu'] = true;
		$__finalCompiled .= '
									';
	}
	$__finalCompiled .= '
									';
	if ($__templater->method($__vars['reply'], 'canChangeDate', array())) {
		$__finalCompiled .= '
										<a href="' . $__templater->func('link', array('showcase/review-reply/change-date', $__vars['reply'], ), true) . '" 
											class="actionBar-action actionBar-action--date actionBar-action--menuItem" 
											data-xf-click="overlay">' . 'Change date' . '</a>
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
										<a href="' . $__templater->func('link', array('showcase/review-reply/ip', $__vars['reply'], ), true) . '"
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
										<a href="' . $__templater->func('link', array('showcase/review-reply/warn', $__vars['reply'], ), true) . '"
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
											<a href="' . $__templater->func('link', array('showcase/review-reply/approve', $__vars['reply'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
												class="actionBar-action actionBar-action--approve actionBar-action--menuItem">' . 'Approve' . '</a>
											';
			$__vars['hasActionBarMenu'] = true;
			$__finalCompiled .= '
										';
		} else if ($__vars['reply']['reply_state'] == 'visible') {
			$__finalCompiled .= '
											<a href="' . $__templater->func('link', array('showcase/review-reply/unapprove', $__vars['reply'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
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
		'link' => 'showcase/review-reply/react',
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
								' . $__templater->func('reactions', array($__vars['reply'], 'showcase/review-reply/reactions', array())) . '
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
		'review' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="message-responseRow">
		<div class="comment' . ($__templater->method($__vars['reply'], 'isIgnored', array()) ? ' is-ignored' : '') . '"
			data-author="' . $__templater->escape($__vars['reply']['User']['username']) . '"
			data-content="item-rating-reply-' . $__templater->escape($__vars['reply']['reply_id']) . '">

			<div class="comment-inner">
				<span class="comment-avatar">
					' . $__templater->func('avatar', array($__vars['reply']['User'], 'xxs', false, array(
		'defaultname' => $__vars['reply']['username'],
	))) . '
				</span>
				<div class="comment-main">
					<span class="u-anchorTarget" id="item-rating-reply-' . $__templater->escape($__vars['reply']['reply_id']) . '"></span>
					<div class="comment-content">
						<div class="messageNotice messageNotice--deleted">
							' . $__templater->callMacro('deletion_macros', 'notice', array(
		'log' => $__vars['reply']['DeletionLog'],
	), $__vars) . '

							<a href="' . $__templater->func('link', array('showcase/review-reply/show', $__vars['reply'], ), true) . '" class="u-jsOnly"
								data-xf-click="inserter"
								data-replace="[data-content=item-rating-reply-' . $__templater->escape($__vars['reply']['reply_id']) . ']">' . 'Show' . $__vars['xf']['language']['ellipsis'] . '</a>
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
),
'rating' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'rating' => '!',
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('xa_sc_review.less');
	$__finalCompiled .= '

	<div class="sc-review message message--simple' . ($__templater->method($__vars['rating'], 'isIgnored', array()) ? ' is-ignored' : '') . '"
		data-author="' . ($__templater->escape($__vars['rating']['User']['username']) ?: $__templater->escape($__vars['rating']['username'])) . '"
		data-content="item-review-' . $__templater->escape($__vars['rating']['rating_id']) . '"
		id="js-review-' . $__templater->escape($__vars['rating']['rating_id']) . '">

		<span class="u-anchorTarget" id="item-review-' . $__templater->escape($__vars['rating']['rating_id']) . '"></span>

		<div class="message-inner">
			<span class="message-cell message-cell--user">
				' . $__templater->callMacro('message_macros', 'user_info_simple', array(
		'user' => $__vars['rating']['User'],
		'fallbackName' => 'Deleted member',
	), $__vars) . '
			</span>
			<div class="message-cell message-cell--main">
				<div class="message-content js-messageContent">
					<div class="message-attribution message-attribution--plain">
						<ul class="listInline listInline--bullet">
							<li class="message-attribution-user">
								' . $__templater->func('username_link', array($__vars['rating']['User'], false, array(
		'defaultname' => 'Deleted member',
	))) . '
							</li>
							<li>
								' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['rating']['rating'],
		'class' => 'ratingStars--smaller',
	), $__vars) . '
							</li>
							<li><a href="" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['rating']['rating_date'], array(
	))) . '</a></li>
						</ul>
					</div>
				</div>

				';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
							';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
									';
	if ($__templater->method($__vars['rating'], 'canDelete', array('hard', ))) {
		$__compilerTemp2 .= '
										<a href="' . $__templater->func('link', array('showcase/review/delete-rating', $__vars['rating'], ), true) . '" 
											class="actionBar-action actionBar-action--delete" 
											data-xf-click="overlay">' . 'Delete' . '</a>
									';
	}
	$__compilerTemp2 .= '
								';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
								<div class="actionBar-set actionBar-set--internal">
								' . $__compilerTemp2 . '
								</div>
							';
	}
	$__compilerTemp1 .= '
						';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
					<div class="message-actionBar actionBar">
						' . $__compilerTemp1 . '
					</div>
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'reviews_carousel' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'reviews' => '!',
		'viewAllLink' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['reviews'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('carousel.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('lightslider.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('xa_sc.less');
		$__finalCompiled .= '
		
		';
		$__templater->includeJs(array(
			'prod' => 'xf/carousel-compiled.js',
			'dev' => 'vendor/lightslider/lightslider.min.js, xf/carousel.js',
		));
		$__finalCompiled .= '

		<div class="carousel carousel--withFooter carousel--scFeaturedItems">
			<ul class="carousel-body carousel-body--show1" data-xf-init="carousel">
				';
		if ($__templater->isTraversable($__vars['reviews'])) {
			foreach ($__vars['reviews'] AS $__vars['review']) {
				$__finalCompiled .= '
					<li>
						<div class="carousel-item">
							<div class="contentRow">
								<div class="contentRow-main">
									';
				if ((!$__vars['category']) OR $__templater->method($__vars['category'], 'hasChildren', array())) {
					$__finalCompiled .= '
										<div class="contentRow-scCategory">
											<a href="' . $__templater->func('link', array('showcase/categories', $__vars['review']['Item']['Category'], ), true) . '">' . $__templater->escape($__vars['review']['Item']['Category']['title']) . '</a>
										</div>
									';
				}
				$__finalCompiled .= '

									';
				if ($__vars['review']['Item']['CoverImage']) {
					$__finalCompiled .= '
										<div class="contentRow-figure">
											<a href="' . $__templater->func('link', array('showcase', $__vars['review']['Item'], ), true) . '">
												' . $__templater->func('sc_item_thumbnail', array($__vars['review']['Item'], ), true) . '
											</a>											
										</div>
									';
				} else if ($__vars['review']['Item']['Category']['content_image_url']) {
					$__finalCompiled .= '
										<div class="contentRow-figure">
											<a href="' . $__templater->func('link', array('showcase', $__vars['review']['Item'], ), true) . '">
												' . $__templater->func('sc_category_icon', array($__vars['review']['Item'], ), true) . '
											</a>											
										</div>
									';
				}
				$__finalCompiled .= '
									
									<h4 class="contentRow-title"><a href="' . $__templater->func('link', array('showcase/review', $__vars['review'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['review']['Item'], ), true) . $__templater->escape($__vars['review']['Item']['title']) . '</a></h4>

									';
				if ($__vars['review']['title'] != '') {
					$__finalCompiled .= '
										<div class="contentRow-lesser">
											<span class="review-title">' . $__templater->func('snippet', array($__vars['review']['title'], 100, array('stripBbCode' => true, ), ), true) . '</span>
										</div>	
									';
				}
				$__finalCompiled .= '

									<div class="contentRow-lesser">
										' . $__templater->func('snippet', array($__vars['review']['message'], 300, array('stripQuote' => true, ), ), true) . '
									</div>
									
									<div class="contentRow-minor contentRow-minor--smaller">
										<ul class="listInline listInline--bullet">
											<li>
												';
				if ($__vars['review']['is_anonymous']) {
					$__finalCompiled .= '
													' . 'Anonymous' . '
													';
					if ($__templater->method($__vars['review'], 'canViewAnonymousAuthor', array())) {
						$__finalCompiled .= '
														(' . ($__templater->escape($__vars['review']['User']['username']) ?: $__templater->escape($__vars['review']['username'])) . ')
													';
					}
					$__finalCompiled .= '
												';
				} else {
					$__finalCompiled .= '
													' . ($__templater->escape($__vars['review']['User']['username']) ?: $__templater->escape($__vars['review']['username'])) . '
												';
				}
				$__finalCompiled .= '
											</li>

											<li><a href="' . $__templater->func('link', array('showcase/review', $__vars['review'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['review']['rating_date'], array(
				))) . '</a></li>
											<li>
												' . $__templater->callMacro('rating_macros', 'stars', array(
					'rating' => $__vars['review']['rating'],
				), $__vars) . '
											</li>
											';
				if ($__vars['review']['reaction_score']) {
					$__finalCompiled .= '
												<li>' . 'Reaction score' . ': ' . $__templater->filter($__vars['review']['reaction_score'], array(array('number_short', array()),), true) . '</li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['review']['last_edit_date']) {
					$__finalCompiled .= '
												<li>' . 'Updated' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['review']['last_edit_date'], array(
					))) . '</li>
											';
				}
				$__finalCompiled .= '
										</ul>
									</div>
								</div>
							</div>
						</div>
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ul>
			<div class="carousel-footer">
				<a href="' . $__templater->escape($__vars['viewAllLink']) . '">' . 'View more reviews' . '</a>
			</div>
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

' . '

' . '

';
	return $__finalCompiled;
}
);