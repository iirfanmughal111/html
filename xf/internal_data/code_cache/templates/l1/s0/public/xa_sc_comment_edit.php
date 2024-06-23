<?php
// FROM HASH: f51587fee06eb86a0b0642fdd9e31e85
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit comment');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['content'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
					' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
				';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['comment'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp2 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp3 .= '
					' . $__templater->button('Cancel', array(
			'class' => 'js-cancelButton',
			'icon' => 'cancel',
		), '', array(
		)) . '
				';
	}
	$__finalCompiled .= $__templater->form('

	<div class="block-container">
		<div class="block-body">
			<span class="u-anchorTarget js-editContainer"></span>

			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['comment']['message'],
		'attachments' => $__vars['attachmentData']['attachments'],
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		'label' => 'Message',
	)) . '

			' . $__templater->formRow('
				' . $__compilerTemp1 . '
			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '

			' . $__templater->formRow('
				' . $__templater->callMacro('helper_action', 'edit_type', array(
		'canEditSilently' => $__templater->method($__vars['comment'], 'canEditSilently', array()),
	), $__vars) . '
			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '

			' . $__compilerTemp2 . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
		'html' => '
				' . $__compilerTemp3 . '
			',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/comments/edit', $__vars['comment'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager',
		'data-preview-url' => $__templater->func('link', array('showcase/comments/edit-preview', $__vars['comment'], ), false),
	));
	return $__finalCompiled;
}
);