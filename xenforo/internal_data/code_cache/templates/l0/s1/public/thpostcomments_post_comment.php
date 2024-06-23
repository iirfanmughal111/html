<?php
// FROM HASH: 046741bfb98330989d1308947ef221e2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Comment on ' . $__templater->escape($__vars['post']['username']) . '\'s post');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'themehouse/post-comments/comment.js',
		'addon' => 'ThemeHouse/PostComments',
	));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['thread'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__vars['edId'] = $__templater->func('unique_id', array(), false);
	$__templater->inlineJs('
				$(\'#' . $__vars['edId'] . '\').froalaEditor(\'events.focus\', true)
			');
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
					' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
			'forceHash' => $__vars['thread']['draft_reply']['attachment_hash'],
		), $__vars) . '
				';
	}
	$__compilerTemp2 = '';
	if (!$__vars['xf']['visitor']['user_id']) {
		$__compilerTemp2 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => '_xfUsername',
			'data-xf-init' => 'guest-username',
			'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'username', ), false),
		), array(
			'label' => 'Name',
		)) . '

				' . $__templater->formRowIfContent($__templater->func('captcha', array(false, false)), array(
			'label' => 'Verification',
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__vars['inlineComment']) {
		$__compilerTemp3 .= '
					' . $__templater->button('Cancel', array(
			'class' => 'js-cancelButton',
		), '', array(
		)) . '
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			<span class="u-anchorTarget js-commentContainer"></span>
			' . '' . '
			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'id' => $__vars['edId'],
		'value' => $__vars['defaultMessage'],
		'attachments' => ($__vars['attachmentData'] ? $__vars['attachmentData']['attachments'] : array()),
		'autofocus' => 'autofocus',
		'placeholder' => ($__vars['xf']['options']['thpostcomments_replaceReplyButton'] ? 'Write your reply...' : 'Write your comment...'),
		'data-min-height' => '100',
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Message',
	)) . '
			' . '' . '

			' . $__templater->formRow('
				' . $__compilerTemp1 . '

				' . $__templater->button('', array(
		'class' => 'button--link u-jsOnly',
		'data-xf-click' => 'preview-click',
		'icon' => 'preview',
	), '', array(
	)) . '
			', array(
	)) . '

			' . $__compilerTemp2 . '
		</div>

		' . $__templater->formSubmitRow(array(
		'submit' => 'Post comment',
		'icon' => 'reply',
	), array(
		'rowtype' => ($__vars['inlineComment'] ? 'simple' : ''),
		'html' => '
				' . $__compilerTemp3 . '
			',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('posts/add-comment', $__vars['post'], ), false),
		'class' => 'block message message--depth' . $__vars['post']['thpostcomments_depth'] . ' message-quickReply',
		'draft' => $__templater->func('link', array('threads/draft', $__vars['thread'], ), false),
		'data-xf-init' => 'attachment-manager',
		'data-preview-url' => $__templater->func('link', array('threads/reply-preview', $__vars['thread'], ), false),
	));
	return $__finalCompiled;
}
);