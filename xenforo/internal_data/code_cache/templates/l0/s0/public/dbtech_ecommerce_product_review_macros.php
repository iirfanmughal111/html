<?php
// FROM HASH: 24c1082e0d99cf632d6d7a8b0a5bcb5c
return array(
'macros' => array('review' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'review' => '!',
		'product' => '!',
		'showProduct' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'xf/comment.js',
		'min' => '1',
	));
	$__finalCompiled .= '

	<div class="message message--simple' . ($__templater->method($__vars['review'], 'isIgnored', array()) ? ' is-ignored' : '') . '">
		<span class="u-anchorTarget" id="product-review-' . $__templater->escape($__vars['review']['product_rating_id']) . '"></span>
		<div class="message-inner">
			<span class="message-cell message-cell--user">
				' . $__templater->callMacro('message_macros', 'user_info_simple', array(
		'user' => $__vars['review']['User'],
		'fallbackName' => 'Deleted member',
	), $__vars) . '
			</span>
			<div class="message-cell message-cell--main">
				<div class="message-content js-messageContent">
					<div class="message-attribution message-attribution--plain">
						';
	if ($__vars['showProduct']) {
		$__finalCompiled .= '
							<div class="message-attribution-source">
								' . 'For ' . ((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ), true)) . '">') . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true)) . $__templater->escape($__vars['product']['title'])) . '</a>') . ' in ' . (((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/categories', $__vars['product']['Category'], ), true)) . '">') . $__templater->escape($__vars['product']['Category']['title'])) . '</a>') . '' . '
							</div>
						';
	}
	$__finalCompiled .= '

						<ul class="listInline listInline--bullet">
							<li class="message-attribution-user">
								' . $__templater->func('username_link', array($__vars['review']['User'], false, array(
		'defaultname' => 'Deleted member',
	))) . '
							</li>
							<li>
								' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['review']['rating'],
		'class' => 'ratingStars--smaller',
	), $__vars) . '
							</li>
							<li><a href="' . $__templater->func('link', array('dbtech-ecommerce/review', $__vars['review'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['review']['rating_date'], array(
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
	}
	$__finalCompiled .= '

					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceReviews',
		'group' => 'above_review',
		'onlyInclude' => $__vars['product']['Category']['review_field_cache'],
		'set' => $__vars['review']['custom_fields'],
		'wrapperClass' => 'message-fields message-fields--before',
	), $__vars) . '

					<div class="message-body">
						' . $__templater->func('structured_text', array($__vars['review']['message'], ), true) . '
					</div>

					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceReviews',
		'group' => 'below_review',
		'onlyInclude' => $__vars['product']['Category']['review_field_cache'],
		'set' => $__vars['review']['custom_fields'],
		'wrapperClass' => 'message-fields message-fields--after',
	), $__vars) . '
				</div>

				';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
							';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['review'], 'canReport', array())) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('dbtech-ecommerce/review/report', $__vars['review'], ), true) . '" class="actionBar-action actionBar-action--report" data-xf-click="overlay">
												' . 'Report' . '
											</a>
										';
	}
	$__compilerTemp2 .= '

										';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['review'], 'canDelete', array('soft', ))) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('dbtech-ecommerce/review/delete', $__vars['review'], ), true) . '"
											   class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
											   data-xf-click="overlay">
												' . 'Delete' . '
											</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '
										';
	if (($__vars['review']['rating_state'] == 'deleted') AND $__templater->method($__vars['review'], 'canUndelete', array())) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('dbtech-ecommerce/review/undelete', $__vars['review'], array('t' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
											   class="actionBar-action actionBar-action--undelete actionBar-action--menuItem">
												' . 'Undelete' . '
											</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '
										';
	if ($__templater->method($__vars['review'], 'canWarn', array())) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('dbtech-ecommerce/review/warn', $__vars['review'], ), true) . '"
											   class="actionBar-action actionBar-action--warn actionBar-action--menuItem">
												' . 'Warn' . '
											</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
											';
	} else if ($__vars['review']['warning_id'] AND $__templater->method($__vars['xf']['visitor'], 'canViewWarnings', array())) {
		$__compilerTemp2 .= '
											<a href="' . $__templater->func('link', array('warnings', array('warning_id' => $__vars['review']['warning_id'], ), ), true) . '"
											   class="actionBar-action actionBar-action--warn actionBar-action--menuItem"
											   data-xf-click="overlay">
												' . 'View warning' . '
											</a>
											';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp2 .= '
										';
	}
	$__compilerTemp2 .= '

										';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp2 .= '
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

			';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
							';
	if ($__templater->method($__vars['review'], 'isContentVotingSupported', array())) {
		$__compilerTemp3 .= '
								' . $__templater->callMacro('content_vote_macros', 'vote_control', array(
			'link' => 'dbtech-ecommerce/review/vote',
			'content' => $__vars['review'],
		), $__vars) . '
							';
	}
	$__compilerTemp3 .= '
						';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
				<div class="message-cell message-cell--vote">
					<div class="message-column">
						' . $__compilerTemp3 . '
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
'author_reply_row' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'review' => '!',
		'product' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="message-responseRow">
		<div class="comment">
			<div class="comment-inner">
				<span class="comment-avatar">
					' . $__templater->func('avatar', array($__vars['product']['User'], 'xxs', false, array(
		'defaultname' => $__vars['product']['username'],
	))) . '
				</span>
				<div class="comment-main">
					<div class="comment-content">
						<div class="comment-contentWrapper">
							' . $__templater->func('username_link', array($__vars['product']['User'], true, array(
		'defaultname' => $__vars['product']['username'],
		'class' => 'comment-user',
	))) . '
							<div class="comment-body">' . $__templater->func('structured_text', array($__vars['review']['author_response'], ), true) . '</div>
						</div>
					</div>

					<div class="comment-actionBar actionBar">
						<div class="actionBar-set actionBar-set--internal">
							';
	if ($__templater->method($__vars['review'], 'canDeleteAuthorResponse', array())) {
		$__finalCompiled .= '
								<a href="' . $__templater->func('link', array('dbtech-ecommerce/review/reply-delete', $__vars['review'], ), true) . '"
									class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
									data-xf-click="overlay">
									' . 'Delete' . '
								</a>
							';
	}
	$__finalCompiled .= '
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
			<a href="' . $__templater->func('link', array('dbtech-ecommerce/review', $__vars['review'], ), true) . '">' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['review']['Product'], ), true) . $__templater->escape($__vars['review']['Product']['title']) . '</a>
			<div class="contentRow-lesser">
				' . $__templater->callMacro('rating_macros', 'stars', array(
		'rating' => $__vars['review']['rating'],
	), $__vars) . '
			</div>
			<div class="contentRow-lesser">' . $__templater->func('snippet', array($__vars['review']['message'], 100, ), true) . '</div>
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
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);