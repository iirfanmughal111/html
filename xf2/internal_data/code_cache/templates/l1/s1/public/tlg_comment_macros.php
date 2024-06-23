<?php
// FROM HASH: 7ec6592630bc56343c9d95ec7cca4eb6
return array(
'macros' => array('comment_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comments' => '!',
		'content' => '!',
		'afterHtml' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block block--messages">
        <div class="block-container lbContainer"
             data-xf-init="lightbox' . ($__vars['xf']['options']['selectQuotable'] ? ' select-to-quote' : '') . '"
             data-message-selector=".js-post"
             data-lb-id="tlg-content-' . $__templater->escape($__templater->method($__vars['content'], 'getEntityId', array())) . '"
             data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

            <div class="block-body js-replyNewMessageContainer">
                ';
	if ($__templater->isTraversable($__vars['comments'])) {
		foreach ($__vars['comments'] AS $__vars['comment']) {
			$__finalCompiled .= '
                    ' . $__templater->callMacro(null, 'comment_root', array(
				'comment' => $__vars['comment'],
				'content' => $__vars['content'],
			), $__vars) . '
                ';
		}
	}
	$__finalCompiled .= '
            </div>
        </div>

        ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                    ';
	if (!$__templater->test($__vars['afterHtml'], 'empty', array())) {
		$__compilerTemp1 .= $__templater->filter($__vars['afterHtml'], array(array('raw', array()),), true);
	}
	$__compilerTemp1 .= '
                    ' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
                ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
            <div class="block-outer block-outer--after">
                ' . $__compilerTemp1 . '
            </div>
        ';
	}
	$__finalCompiled .= '
    </div>
';
	return $__finalCompiled;
}
),
'comment_form_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'formAction' => '!',
		'attachmentData' => null,
		'comments' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->method($__vars['content'], 'canComment', array())) {
		$__finalCompiled .= '
        ';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__vars['lastPost'] = $__templater->filter($__vars['comments'], array(array('last', array()),), false);
		$__finalCompiled .= $__templater->form('

            ' . '' . '
            ' . '' . '

            <div class="block-container">
                <div class="block-body">
                    ' . $__templater->callMacro('quick_reply_macros', 'body', array(
			'message' => null,
			'attachmentData' => $__vars['attachmentData'],
			'forceHash' => null,
			'messageSelector' => '.js-post',
			'simple' => true,
			'submitText' => 'Post comment',
			'lastDate' => $__vars['comment']['comment_date'],
			'showPreviewButton' => false,
			'lastKnownDate' => null,
		), $__vars) . '
                </div>
            </div>
        ', array(
			'action' => $__vars['formAction'],
			'ajax' => 'true',
			'class' => 'block js-quickReply',
			'data-xf-init' => 'attachment-manager quick-reply' . ($__templater->method($__vars['xf']['visitor'], 'isShownCaptcha', array()) ? ' guest-captcha' : ''),
			'data-message-container' => '.js-replyNewMessageContainer',
		)) . '
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'comment_root' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comment' => '!',
		'content' => '!',
		'showUser' => true,
	); },
'extensions' => array('user' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                ';
	if ($__vars['showUser']) {
		$__finalCompiled .= '
                    <div class="message-cell message-cell--user">
                        ' . $__templater->callMacro('message_macros', 'user_info_simple', array(
			'user' => $__vars['comment']['User'],
			'fallbackName' => $__vars['comment']['username'],
		), $__vars) . '
                    </div>
                ';
	}
	$__finalCompiled .= '
            ';
	return $__finalCompiled;
},
'attribution' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                                ' . $__templater->callMacro(null, 'comment_root_attribution', array(
		'comment' => $__vars['comment'],
	), $__vars) . '
                            ';
	return $__finalCompiled;
},
'notices' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                            ' . $__templater->callMacro(null, 'comment_notices', array(
		'comment' => $__vars['comment'],
	), $__vars) . '
                        ';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_comment_row.less');
	$__finalCompiled .= '

    ';
	$__templater->includeJs(array(
		'src' => 'xf/comment.js',
		'min' => '1',
	));
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/group.js',
		'addon' => 'Truonglv/Groups',
		'min' => '1',
	));
	$__finalCompiled .= '
    <article class="message js-comment message--simple js-inlineModContainer' . ($__templater->method($__vars['comment'], 'isIgnored', array()) ? ' is-ignored' : '') . '"
             id="js-comment-' . $__templater->escape($__vars['comment']['comment_id']) . '"
             data-author="' . ($__templater->escape($__vars['comment']['User']['username']) ?: $__templater->escape($__vars['comment']['username'])) . '"
             data-content="post-' . $__templater->escape($__vars['comment']['comment_id']) . '">
        <span class="u-anchorTarget" id="comment-' . $__templater->escape($__vars['comment']['comment_id']) . '"></span>
        <div class="message-inner">
            ' . $__templater->renderExtension('user', $__vars, $__extensions) . '

            <div class="message-cell message-cell--main">
                <div class="js-comment message-main js-quickEditTarget">
                    <div class="message-content js-messageContent">
                        ';
	if ($__vars['showUser']) {
		$__finalCompiled .= '
                            ' . $__templater->renderExtension('attribution', $__vars, $__extensions) . '
                        ';
	}
	$__finalCompiled .= '

                        ' . $__templater->renderExtension('notices', $__vars, $__extensions) . '

                        ' . $__templater->callMacro(null, 'comment_user_content', array(
		'comment' => $__vars['comment'],
	), $__vars) . '
                    </div>

                    ' . $__templater->callMacro(null, 'comment_root_footer', array(
		'comment' => $__vars['comment'],
		'showLoader' => $__vars['showLoader'],
	), $__vars) . '
                </div>
            </div>
        </div>
    </article>
';
	return $__finalCompiled;
}
),
'comment_root_attribution' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comment' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <header class="message-attribution message-attribution--plain">
        <ul class="listInline listInline--bullet">
            <li class="message-attribution-user">
                ' . $__templater->func('avatar', array($__vars['comment']['User'], 'xxs', false, array(
	))) . '
                <h4 class="attribution">' . $__templater->func('username_link', array($__vars['comment']['User'], true, array(
		'defaultname' => $__vars['comment']['username'],
	))) . '</h4>
            </li>
        </ul>
    </header>
';
	return $__finalCompiled;
}
),
'comment_root_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comment' => '!',
		'showLoader' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <footer class="message-footer">
        <div class="reactionsBar js-reactionsList' . $__templater->escape($__vars['comment']['comment_id']) . ($__vars['comment']['reactions'] ? ' is-active' : '') . '">
            ' . $__templater->func('reactions', array($__vars['comment'], 'group-comments/reactions', array())) . '
        </div>

        ';
	$__vars['dateHtml'] = $__templater->preEscaped('<a href="' . $__templater->func('link', array('group-comments', $__vars['comment'], ), true) . '"
                                   class="u-concealed" rel="nofollow"><small>' . $__templater->fontAwesome('fa-clock', array(
	)) . ' ' . $__templater->func('date_dynamic', array($__vars['comment']['comment_date'], array(
	))) . '</small></a>');
	$__finalCompiled .= '
        ' . $__templater->callMacro('tlg_comment_macros', 'comment_controls', array(
		'comment' => $__vars['comment'],
		'showDate' => true,
		'dateHtml' => $__vars['dateHtml'],
		'showReply' => false,
		'editorTarget' => ($__templater->method($__vars['comment'], 'isFirstComment', array()) ? '' : (('#js-comment-' . $__vars['comment']['comment_id']) . '.js-quickEditTarget')),
	), $__vars) . '

        <section class="message-responses js-messageResponses">
            ';
	if ($__vars['showLoader'] AND $__templater->method($__vars['comment'], 'hasMoreComments', array())) {
		$__finalCompiled .= '
                ';
		$__vars['firstComment'] = $__templater->filter($__vars['comment']['LatestComments'], array(array('first', array()),), false);
		$__finalCompiled .= '
                <a href="' . $__templater->func('link', array('group-comments/loader', $__vars['firstComment'], ), true) . '"
                   data-xf-init="tlg-comment-loader"
                   data-container="< .js-messageResponses | .js-replyNewMessageContainer"
                   data-message-selector=".js-comment"
                   rel="nofollow"
                   class="commentLoader">' . 'View previous comments' . $__vars['xf']['language']['ellipsis'] . '</a>
            ';
	}
	$__finalCompiled .= '
            <div class="js-replyNewMessageContainer">';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['comment']['LatestReplies'])) {
		foreach ($__vars['comment']['LatestReplies'] AS $__vars['comment']) {
			$__compilerTemp1 .= '
                    ' . $__templater->callMacro(null, 'comment', array(
				'comment' => $__vars['comment'],
				'content' => $__vars['content'],
			), $__vars) . '
                ';
		}
	}
	$__finalCompiled .= $__templater->func('trim', array('
                ' . $__compilerTemp1 . '
            '), false) . '</div>

            ';
	if ($__templater->method($__vars['comment'], 'canReply', array())) {
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'comment_form', array(
			'target' => 'js-commentsTarget-' . $__vars['comment']['comment_id'],
			'attachmentData' => $__templater->method($__vars['comment'], 'getAttachmentEditorData', array()),
			'formUrl' => $__templater->func('link', array('group-comments/reply', $__vars['comment'], ), false),
		), $__vars) . '
            ';
	}
	$__finalCompiled .= '
        </section>
    </footer>
';
	return $__finalCompiled;
}
),
'comment_notices' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comment' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__vars['comment']['message_state'] == 'deleted') {
		$__finalCompiled .= '
        <div class="messageNotice messageNotice--deleted">
            ' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['post']['DeletionLog'],
		), $__vars) . '
        </div>
    ';
	} else if ($__vars['comment']['message_state'] == 'moderated') {
		$__finalCompiled .= '
        <div class="messageNotice messageNotice--moderated">
            ' . 'This message is awaiting moderator approval, and is invisible to normal visitors.' . '
        </div>
    ';
	}
	$__finalCompiled .= '
    ';
	if ($__templater->method($__vars['comment'], 'isIgnored', array())) {
		$__finalCompiled .= '
        <div class="messageNotice messageNotice--ignored">
            ' . 'You are ignoring content by this member.' . '
            ' . $__templater->func('show_ignored', array(array(
		))) . '
        </div>
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'comment_user_content' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comment' => '!',
		'expanded' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="message-userContent lbContainer js-lbContainer' . ($__templater->method($__vars['comment'], 'isIgnored', array()) ? ' is-ignored' : '') . '"
         data-lb-id="comment-' . $__templater->escape($__vars['comment']['comment_id']) . '"
         data-lb-caption-desc="' . ($__vars['comment']['User'] ? $__templater->escape($__vars['comment']['User']['username']) : $__templater->escape($__vars['comment']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['comment']['comment_date'], ), true) . '">
        <article class="message-body">
            ' . $__templater->func('bb_code', array($__vars['comment']['message'], 'tl_group_comment', $__vars['comment'], ), true) . '
        </article>
        ';
	if ($__vars['comment']['attach_count']) {
		$__finalCompiled .= '
            ' . $__templater->callMacro('message_macros', 'attachments', array(
			'attachments' => $__vars['comment']['Attachments'],
			'message' => $__vars['comment'],
			'canView' => true,
		), $__vars) . '
        ';
	}
	$__finalCompiled .= '
    </div>
';
	return $__finalCompiled;
}
),
'comment' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comment' => '!',
		'content' => '!',
	); },
'extensions' => array('extra_classes' => function($__templater, array $__vars, $__extensions = null)
{
	return 'commentItem';
},
'user' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                    <span class="comment-avatar">
                        ' . $__templater->func('avatar', array($__vars['comment']['User'], 'xs', false, array(
		'defaultname' => $__vars['comment']['username'],
	))) . '
                    </span>
                ';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_comment_row.less');
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/group.js',
		'addon' => 'Truonglv/Groups',
		'min' => '1',
	));
	$__finalCompiled .= '

    ' . '
    <div class="' . $__templater->escape($__templater->renderExtension('extra_classes', $__vars, $__extensions)) . ' js-comment comment-level--' . $__templater->escape($__vars['comment']['comment_level']) . ($__templater->method($__vars['comment'], 'isIgnored', array()) ? ' is-ignored' : '') . '"
         data-author="' . $__templater->escape($__vars['comment']['User']['username']) . '"
         data-content="comment-' . $__templater->escape($__vars['comment']['comment_id']) . '"
         data-row-id="' . $__templater->escape($__vars['comment']['comment_id']) . '"
         id="js-comment-' . $__templater->escape($__vars['comment']['comment_id']) . '">
        <div class="comment">
            <div class="comment-inner">
                ' . $__templater->renderExtension('user', $__vars, $__extensions) . '
                <div class="comment-main">
                    <span class="u-anchorTarget" id="comment-' . $__templater->escape($__vars['comment']['comment_id']) . '"></span>
                    <div class="js-quickEditTarget' . $__templater->escape($__vars['comment']['comment_id']) . '">
                        <div class="comment-content">
                            ' . $__templater->callMacro(null, 'comment_notices', array(
		'comment' => $__vars['comment'],
	), $__vars) . '

                            <div class="comment-contentWrapper">
                                ' . $__templater->func('username_link', array($__vars['comment']['User'], true, array(
		'defaultname' => $__vars['comment']['username'],
		'class' => 'comment-user',
	))) . '

                                ' . $__templater->callMacro(null, 'comment_user_content', array(
		'comment' => $__vars['comment'],
	), $__vars) . '
                            </div>
                        </div>

                        ' . $__templater->callMacro(null, 'comment_footer', array(
		'comment' => $__vars['comment'],
	), $__vars) . '
                    </div>
                </div>
            </div>
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'comment_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comment' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <footer class="comment-footer comment-footer' . $__templater->escape($__vars['comment']['comment_id']) . '">
        ' . $__templater->callMacro(null, 'comment_controls', array(
		'comment' => $__vars['comment'],
		'editorTarget' => '#js-comment-' . $__vars['comment']['comment_id'] . ' .js-quickEditTarget' . $__vars['comment']['comment_id'],
	), $__vars) . '
        <div class="reactionsBar js-reactionsList' . $__templater->escape($__vars['comment']['comment_id']) . ($__vars['comment']['reactions'] ? ' is-active' : '') . '">' . $__templater->func('trim', array('
            ' . $__templater->func('reactions', array($__vars['comment'], 'group-comments/reactions', array())) . '
        '), false) . '</div>
        ';
	if ($__templater->method($__vars['comment'], 'hasMoreReplies', array())) {
		$__finalCompiled .= '
            ';
		$__vars['firstReply'] = $__templater->filter($__vars['comment']['LatestReplies'], array(array('first', array()),), false);
		$__finalCompiled .= '
            <a href="' . $__templater->func('link', array('group-comments/loader', $__vars['firstReply'], ), true) . '"
               data-xf-init="tlg-comment-loader"
               data-container="< .comment-footer' . $__templater->escape($__vars['comment']['comment_id']) . ' | .js-comment' . $__templater->escape($__vars['comment']['comment_level']) . '--replies"
               data-message-selector=".js-comment"
               rel="nofollow"
               class="commentLoader">' . 'View previous replies' . $__vars['xf']['language']['ellipsis'] . '</a>
        ';
	}
	$__finalCompiled .= '
        <div class="js-comment' . $__templater->escape($__vars['comment']['comment_level']) . '--replies">';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['comment']['LatestReplies'])) {
		foreach ($__vars['comment']['LatestReplies'] AS $__vars['commentReply']) {
			$__compilerTemp1 .= '
                ' . $__templater->callMacro(null, 'comment', array(
				'comment' => $__vars['commentReply'],
				'content' => $__vars['content'],
			), $__vars) . '
            ';
		}
	}
	$__finalCompiled .= $__templater->func('trim', array('
            ' . $__compilerTemp1 . '
        '), false) . '</div>

        ';
	if ($__templater->method($__vars['comment'], 'canReply', array()) AND ($__vars['comment']['comment_level'] < 2)) {
		$__finalCompiled .= '
            ' . $__templater->callMacro(null, 'comment_form', array(
			'formUrl' => $__templater->func('link', array('group-comments/reply', $__vars['comment'], ), false),
			'comment' => $__vars['comment'],
			'target' => 'js-responseForm' . $__vars['comment']['comment_id'],
			'attachmentData' => $__templater->method($__vars['comment'], 'getAttachmentEditorData', array()),
			'deferredEditor' => false,
			'messageContainer' => '< .comment-footer' . $__vars['comment']['comment_id'] . ' | .js-comment' . $__vars['comment']['comment_level'] . '--replies',
		), $__vars) . '
        ';
	}
	$__finalCompiled .= '
    </footer>
';
	return $__finalCompiled;
}
),
'comment_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'target' => null,
		'formUrl' => '!',
		'attachmentData' => null,
		'deferredEditor' => true,
		'messageContainer' => '< .js-messageResponses | .js-replyNewMessageContainer',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'xf/message.js',
		'min' => '1',
	));
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_comment_row.less');
	$__finalCompiled .= '

    <div class="commentItem message-responseForm' . ($__vars['target'] ? (' ' . $__templater->escape($__vars['target'])) : '') . '">
        ';
	$__compilerTemp1 = '';
	if ($__vars['deferredEditor']) {
		$__compilerTemp1 .= '
                            <div class="editorPlaceholder-placeholder">
                                <div class="input"><span class="u-muted"> ' . 'Write a comment' . $__vars['xf']['language']['ellipsis'] . '</span></div>
                            </div>
                        ';
	}
	$__finalCompiled .= $__templater->form('
            <div class="comment-inner">
                <span class="comment-avatar">
                    ' . $__templater->func('avatar', array($__vars['xf']['visitor'], 'xs', false, array(
	))) . '
                </span>
                <div class="comment-main">
                    <div class="editorPlaceholder" data-xf-click="' . ($__vars['deferredEditor'] ? 'editor-placeholder' : '') . '">
                        <div class="editorPlaceholder-editor' . ($__vars['deferredEditor'] ? ' is-hidden' : '') . '">
                            ' . $__templater->callMacro('quick_reply_macros', 'editor', array(
		'attachmentData' => $__vars['attachmentData'],
		'minHeight' => '40',
		'placeholder' => 'Write a comment' . $__vars['xf']['language']['ellipsis'],
		'submitText' => 'Post comment',
		'deferred' => $__vars['deferredEditor'],
		'simpleSubmit' => true,
	), $__vars) . '
                        </div>
                        ' . $__compilerTemp1 . '
                    </div>
                </div>
            </div>
        ', array(
		'action' => $__vars['formUrl'],
		'ajax' => 'true',
		'class' => 'comment-form',
		'data-xf-init' => 'quick-reply' . ($__vars['attachmentData'] ? ' attachment-manager' : ''),
		'data-message-container' => $__vars['messageContainer'],
	)) . '
    </div>
';
	return $__finalCompiled;
}
),
'comment_controls' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'comment' => '!',
		'showDate' => true,
		'dateHtml' => null,
		'showReply' => true,
		'editorTarget' => '!',
		'extraControls' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

        <div class="comment-actionBar actionBar">
            ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                        ';
	if ($__vars['showDate']) {
		$__compilerTemp1 .= '
                            ';
		if (!$__templater->test($__vars['dateHtml'], 'empty', array())) {
			$__compilerTemp1 .= $__templater->escape($__vars['dateHtml']);
		} else {
			$__compilerTemp1 .= '
                                <span class="actionBar-action">' . $__templater->func('date_dynamic', array($__vars['comment']['comment_date'], array(
			))) . '</span>';
		}
		$__compilerTemp1 .= '
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	$__vars['hasActionBarMenu'] = false;
	$__compilerTemp1 .= '
                        ';
	if ($__templater->method($__vars['comment'], 'canEdit', array())) {
		$__compilerTemp1 .= '
                            ';
		$__templater->includeJs(array(
			'src' => 'xf/message.js',
			'min' => '1',
		));
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-comments/edit', $__vars['comment'], ), true) . '"
                               class="actionBar-action actionBar-action--edit actionBar-action--menuItem"
                               data-xf-click="' . ($__vars['editorTarget'] ? 'quick-edit' : '') . '"
                               data-editor-target="' . $__templater->escape($__vars['editorTarget']) . '"
                               data-menu-closer="true">' . 'Edit' . '</a>
                            ';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp1 .= '
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__templater->method($__vars['comment'], 'canDelete', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-comments/delete', $__vars['comment'], ), true) . '"
                               class="actionBar-action actionBar-action--delete actionBar-action--menuItem"
                               data-xf-click="overlay">' . 'Delete' . '</a>
                            ';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp1 .= '
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if ($__templater->method($__vars['comment'], 'canReport', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-comments/report', $__vars['comment'], ), true) . '"
                               class="actionBar-action actionBar-action--report"
                               data-xf-click="overlay">' . 'Report' . '</a>
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if (!$__templater->test($__vars['extraControls'], 'empty', array())) {
		$__compilerTemp1 .= '
                            ' . $__templater->filter($__vars['extraControls'], array(array('raw', array()),), true) . '
                            ';
		$__vars['hasActionBarMenu'] = true;
		$__compilerTemp1 .= '
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if ($__vars['hasActionBarMenu']) {
		$__compilerTemp1 .= '
                            <a class="actionBar-action actionBar-action--menuTrigger"
                               data-xf-click="menu"
                               title="' . $__templater->filter('More options', array(array('for_attr', array()),), true) . '"
                               role="button" tabindex="0" aria-expanded="false"
                               aria-haspopup="true">&#8226;&#8226;&#8226;</a>
                            <div class="menu" data-menu="menu" aria-hidden="true" data-menu-builder="actionBar">
                                <div class="menu-content">
                                    <h4 class="menu-header">' . 'More options' . '</h4>
                                    <div class="js-menuBuilderTarget"></div>
                                </div>
                            </div>
                        ';
	}
	$__compilerTemp1 .= '
                    ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
                <div class="actionBar-set actionBar-set--internal">
                    ' . $__compilerTemp1 . '
                </div>
            ';
	}
	$__finalCompiled .= '

            ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                        ';
	if ($__templater->method($__vars['comment'], 'canReact', array())) {
		$__compilerTemp2 .= '
                            ' . $__templater->func('react', array(array(
			'content' => $__vars['comment'],
			'link' => 'group-comments/react',
			'list' => '< .js-comment | .js-reactionsList' . $__vars['comment']['comment_id'],
		))) . '
                        ';
	}
	$__compilerTemp2 .= '
                        ';
	if ($__vars['showReply'] AND $__templater->method($__vars['comment'], 'canReply', array())) {
		$__compilerTemp2 .= '
                            ';
		$__vars['replyRefId'] = $__templater->preEscaped((($__vars['comment']['comment_level'] > 1) ? $__templater->escape($__vars['comment']['parent_id']) : $__templater->escape($__vars['comment']['comment_id'])));
		$__compilerTemp2 .= '
                            <a data-xf-click="tlg-reply"
                               data-target="< .comment-footer' . $__templater->escape($__vars['replyRefId']) . ' | .js-responseForm' . $__templater->escape($__vars['replyRefId']) . '"
                               data-author="' . ($__vars['comment']['User'] ? $__templater->escape($__vars['comment']['User']['username']) : $__templater->escape($__vars['comment']['username'])) . '"
                               data-level="' . $__templater->escape($__vars['comment']['comment_level']) . '"
                               class="actionBar-action actionBar-action--reply">' . 'Reply' . '</a>
                        ';
	}
	$__compilerTemp2 .= '
                    ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
                <div class="actionBar-set actionBar-set--external">
                    ' . $__compilerTemp2 . '
                </div>
            ';
	}
	$__finalCompiled .= '
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

' . '

' . '

' . '

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);