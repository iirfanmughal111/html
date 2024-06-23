<?php
// FROM HASH: badfe4252c8eeb1dfdbe0d5b054f7877
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('xa_sc_comment');
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

				' . $__templater->formRowIfContent($__templater->func('captcha_options', array(array(
			'label' => 'Verification',
		))), array(
			'label' => 'Verification',
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
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Message',
	)) . '

			<div class="js-previewContainer"></div>
			' . $__compilerTemp1 . '
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
		'data-preview-url' => $__templater->func('link', array($__vars['linkPrefix'] . '/preview', $__vars['content'], ), false),
	));
	return $__finalCompiled;
}
);