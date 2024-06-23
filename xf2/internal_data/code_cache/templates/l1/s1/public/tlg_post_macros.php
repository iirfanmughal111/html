<?php
// FROM HASH: a5889f273e68519a2b9c8fa3bc368a74
return array(
'macros' => array('post' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
		'showGroup' => false,
		'showFull' => false,
		'showLoader' => false,
	); },
'extensions' => array('extra_classes' => function($__templater, array $__vars, $__extensions = null)
{
	return 'postItem';
},
'user' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                        <h4 class="attribution">
                            ' . $__templater->func('username_link', array($__vars['post']['User'], false, array(
		'defaultname' => $__vars['post']['username'],
	))) . '
                        </h4>
                    ';
	return $__finalCompiled;
},
'attribution' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                        ' . $__templater->callMacro(null, 'post_attribution', array(
		'post' => $__vars['post'],
	), $__vars) . '
                    ';
	return $__finalCompiled;
},
'actions' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                    ' . $__templater->callMacro(null, 'post_actions', array(
		'post' => $__vars['post'],
	), $__vars) . '
                ';
	return $__finalCompiled;
},
'notices' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                    ' . $__templater->callMacro(null, 'post_notices', array(
		'post' => $__vars['post'],
	), $__vars) . '
                ';
	return $__finalCompiled;
},
'user_content' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                    ' . $__templater->callMacro(null, 'post_user_content', array(
		'post' => $__vars['post'],
		'showFull' => $__vars['showFull'],
	), $__vars) . '
                ';
	return $__finalCompiled;
},
'footer' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'post_footer', array(
		'post' => $__vars['post'],
		'showLoader' => $__vars['showLoader'],
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
	$__templater->includeCss('tlg_post_item.less');
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
    ' . '
    <div class="message ' . $__templater->escape($__templater->renderExtension('extra_classes', $__vars, $__extensions)) . ' js-post js-inlineModContainer' . ($__templater->method($__vars['post'], 'isIgnored', array()) ? ' is-ignored' : '') . '"
         data-author="' . ($__templater->escape($__vars['post']['User']['username']) ?: $__templater->escape($__vars['post']['username'])) . '"
         data-content="post-' . $__templater->escape($__vars['post']['post_id']) . '"
         id="js-post-' . $__templater->escape($__vars['post']['post_id']) . '">
        <span class="u-anchorTarget" id="post-' . $__templater->escape($__vars['post']['post_id']) . '"></span>

        <div class="postItem-inner js-quickEditTarget">
            <header class="postItem-header">
                ' . $__templater->func('avatar', array($__vars['post']['User'], 's', false, array(
		'defaultname' => $__vars['post']['username'],
	))) . '
                <div class="postItem-header--user">
                    ' . $__templater->renderExtension('user', $__vars, $__extensions) . '

                    ' . $__templater->renderExtension('attribution', $__vars, $__extensions) . '
                </div>

                ' . $__templater->renderExtension('actions', $__vars, $__extensions) . '
            </header>

            <div class="postItem-body">
                ' . $__templater->renderExtension('notices', $__vars, $__extensions) . '

                ' . $__templater->renderExtension('user_content', $__vars, $__extensions) . '
            </div>

            ' . $__templater->renderExtension('footer', $__vars, $__extensions) . '
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'post_user_content' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
		'showFull' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="message-userContent lbContainer js-lbContainer' . ($__templater->method($__vars['post'], 'isIgnored', array()) ? ' is-ignored' : '') . '"
         data-lb-id="post-' . $__templater->escape($__vars['post']['post_id']) . '"
         data-lb-caption-desc="' . ($__vars['post']['User'] ? $__templater->escape($__vars['post']['User']['username']) : $__templater->escape($__vars['post']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['post']['post_date'], ), true) . '">
        <article class="message-body">
            ';
	if ($__vars['showFull'] OR ($__vars['post']['FirstComment']['message'] == $__vars['post']['message_preview'])) {
		$__finalCompiled .= '
                ' . $__templater->func('bb_code', array($__vars['post']['FirstComment']['message'], 'tl_group_comment', $__vars['post']['FirstComment'], ), true) . '
            ';
	} else {
		$__finalCompiled .= '
                ' . $__templater->func('bb_code', array($__vars['post']['message_preview'], 'tl_group_comment', $__vars['post']['FirstComment'], ), true) . '
                <a href="' . $__templater->func('link', array('group-posts', $__vars['post'], ), true) . '" class="postItem--readMore">' . 'Read more' . $__vars['xf']['language']['ellipsis'] . '</a>
            ';
	}
	$__finalCompiled .= '
        </article>

        ';
	if ($__vars['post']['FirstComment']['attach_count']) {
		$__finalCompiled .= '
            ' . $__templater->callMacro('message_macros', 'attachments', array(
			'attachments' => $__vars['post']['FirstComment']['Attachments'],
			'message' => $__vars['post']['FirstComment'],
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
'post_footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
		'showLoader' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <footer class="postItem-footer message-footer">
        <div class="reactionsBar js-reactionsList' . $__templater->escape($__vars['post']['post_id']) . ($__vars['post']['FirstComment']['reactions'] ? ' is-active' : '') . '">
            ' . $__templater->func('reactions', array($__vars['post']['FirstComment'], 'group-comments/reactions', array())) . '
        </div>

        ' . $__templater->callMacro(null, 'post_footer_actions', array(
		'post' => $__vars['post'],
	), $__vars) . '

        <div class="postItem--comments js-messageResponses' . (((!$__templater->method($__vars['post'], 'canComment', array())) AND ($__vars['post']['comment_count'] == 0)) ? ' is-hidden' : '') . '">
            ';
	if ($__vars['showLoader'] AND $__templater->method($__vars['post'], 'hasMoreComments', array())) {
		$__finalCompiled .= '
                ';
		$__vars['firstComment'] = $__templater->filter($__vars['post']['LatestComments'], array(array('first', array()),), false);
		$__finalCompiled .= '
                <a href="' . $__templater->func('link', array('group-comments/loader', $__vars['firstComment'], ), true) . '"
                   data-xf-init="tlg-comment-loader"
                   data-container="< .js-messageResponses | .js-replyNewMessageContainer"
                   data-message-selector=".js-comment"
                   rel="nofollow"
                   data-loaded="' . $__templater->filter(array($__vars['post']['first_comment_id'], ), array(array('json', array()),), true) . '"
                   class="commentLoader">' . 'View previous comments' . $__vars['xf']['language']['ellipsis'] . '</a>
            ';
	}
	$__finalCompiled .= '
            <div class="js-replyNewMessageContainer">';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['post']['LatestComments'])) {
		foreach ($__vars['post']['LatestComments'] AS $__vars['comment']) {
			$__compilerTemp1 .= '
                    ' . $__templater->callMacro('tlg_comment_macros', 'comment', array(
				'comment' => $__vars['comment'],
				'content' => $__vars['post'],
			), $__vars) . '
                ';
		}
	}
	$__finalCompiled .= $__templater->func('trim', array('
                ' . $__compilerTemp1 . '
            '), false) . '</div>

            ' . $__templater->callMacro(null, 'post_comment_form', array(
		'post' => $__vars['post'],
	), $__vars) . '
        </div>
    </footer>
';
	return $__finalCompiled;
}
),
'post_footer_actions' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="postItem-footer--actionBar message-actionBar actionBar">
        ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                    ';
	if ($__templater->method($__vars['post']['FirstComment'], 'canReact', array())) {
		$__compilerTemp1 .= '
                        ' . $__templater->func('react', array(array(
			'content' => $__vars['post']['FirstComment'],
			'link' => 'group-comments/react',
			'list' => '< .postItem-footer | .js-reactionsList' . $__vars['post']['post_id'],
		))) . '
                        ';
		if ($__templater->method($__vars['post'], 'canComment', array())) {
			$__compilerTemp1 .= '
                            <a data-xf-click="tlg-reply"
                               data-target="< .postItem-footer | .js-postForm' . $__templater->escape($__vars['post']['post_id']) . '"
                               data-author="' . ($__vars['post']['FirstComment']['User'] ? $__templater->escape($__vars['comment']['User']['username']) : $__templater->escape($__vars['comment']['username'])) . '"
                               class="actionBar-action--reply actionBar-action"
                               data-focus-only="1">' . 'Comment' . '</a>
                        ';
		}
		$__compilerTemp1 .= '
                    ';
	}
	$__compilerTemp1 .= '
                ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
            <div class="actionBar-set actionBar-set--external">
                ' . $__compilerTemp1 . '
            </div>
        ';
	}
	$__finalCompiled .= '
        ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                    ';
	if ($__templater->method($__vars['post']['FirstComment'], 'canEdit', array())) {
		$__compilerTemp2 .= '
                        ' . '
                        ';
		$__templater->includeCss('editor.less');
		$__compilerTemp2 .= '
                        <a href="' . $__templater->func('link', array('group-comments/edit', $__vars['post']['FirstComment'], ), true) . '"
                           data-editor-target="< #js-post-' . $__templater->escape($__vars['post']['post_id']) . ' | .js-quickEditTarget"
                           class="postItem--actionBar--edit actionBar-action"
                           data-xf-click="quick-edit">' . 'Edit' . '</a>
                    ';
	}
	$__compilerTemp2 .= '
                ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
            <div class="actionBar-set actionBar-set--internal">
                ' . $__compilerTemp2 . '
            </div>
        ';
	}
	$__finalCompiled .= '
    </div>
';
	return $__finalCompiled;
}
),
'post_comment_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->method($__vars['post'], 'canComment', array())) {
		$__finalCompiled .= '
        ' . $__templater->callMacro('tlg_comment_macros', 'comment_form', array(
			'target' => 'js-postForm' . $__vars['post']['post_id'],
			'attachmentData' => $__templater->method($__templater->method($__vars['post'], 'getNewComment', array()), 'getAttachmentEditorData', array()),
			'formUrl' => $__templater->func('link', array('group-posts/comment', $__vars['post'], ), false),
		), $__vars) . '
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'post_notices' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__vars['post']['FirstComment']['message_state'] == 'deleted') {
		$__finalCompiled .= '
        <div class="messageNotice messageNotice--deleted">
            ' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['post']['FirstComment']['DeletionLog'],
		), $__vars) . '
        </div>
    ';
	} else if ($__vars['post']['FirstComment']['message_state'] == 'moderated') {
		$__finalCompiled .= '
        <div class="messageNotice messageNotice--moderated">
            ' . 'This message is awaiting moderator approval, and is invisible to normal visitors.' . '
        </div>
    ';
	}
	$__finalCompiled .= '
    ';
	if ($__templater->method($__vars['post'], 'isIgnored', array())) {
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
'post_actions' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                        ';
	if ($__templater->method($__vars['post'], 'canDelete', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-comments/delete', $__vars['post']['FirstComment'], ), true) . '"
                               class="menu-linkRow"
                               data-xf-click="overlay">' . 'Delete' . '</a>
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__templater->method($__vars['post']['FirstComment'], 'canReport', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-comments/report', $__vars['post']['FirstComment'], ), true) . '"
                               class="menu-linkRow"
                               data-xf-click="overlay">' . 'Report' . '</a>
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__templater->method($__vars['post'], 'canStickUnstick', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-posts/toggle-sticky', $__vars['post'], ), true) . '"
                               class="menu-linkRow">' . ($__vars['post']['sticky'] ? 'Unstick' : 'Stick') . '</a>
                        ';
	}
	$__compilerTemp1 .= '
                    ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
        <div class="postItem-header--menu">
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
                    ' . $__compilerTemp1 . '
                </div>
            </div>
        </div>
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'post_attribution' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'post' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <ul class="listInline listInline--bullet message-attribution">
        <li>
            <a href="' . $__templater->func('link', array('group-posts', $__vars['post'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['post']['post_date'], array(
	))) . '</a>
        </li>
        ';
	if ($__vars['showGroup']) {
		$__finalCompiled .= '
            <li>
                <a href="' . $__templater->func('link', array('groups', $__vars['post']['Group'], ), true) . '" class="u-concealed">' . $__templater->escape($__vars['post']['Group']['name']) . '</a>
            </li>
        ';
	}
	$__finalCompiled .= '
    </ul>
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

';
	return $__finalCompiled;
}
);