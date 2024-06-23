<?php
// FROM HASH: 56786b2a5ee596d29656b803f117af7b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Comment');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['content'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => '_xfUsername',
			'data-xf-init' => 'guest-username',
			'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'username', ), false),
		), array(
			'label' => 'Name',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['defaultMessage'],
		'placeholder' => 'Write your reply...',
		'data-preview-url' => $__templater->func('link', array($__vars['linkPrefix'] . '/preview', $__vars['content'], ), false),
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Message',
	)) . '

			' . $__compilerTemp1 . '

			' . $__templater->formRowIfContent($__templater->func('captcha_options', array(array(
		'label' => 'Verification',
		'context' => 'xfmg_comment',
	))), array(
		'label' => 'Verification',
		'context' => 'xfmg_comment',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Post comment',
		'icon' => 'reply',
		'sticky' => 'true',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/add-comment', $__vars['content'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'draft' => $__templater->func('link', array($__vars['linkPrefix'] . '/draft', $__vars['content'], ), false),
	));
	return $__finalCompiled;
}
);