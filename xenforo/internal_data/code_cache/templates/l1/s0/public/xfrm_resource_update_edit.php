<?php
// FROM HASH: ee6951c89740f01574a71c108cf8cabe
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit update');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource'], 'getBreadcrumbs', array()));
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
	if ($__templater->method($__vars['update'], 'canSendModeratorActionAlert', array())) {
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

			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['update']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['update'], 'title', ), false),
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
		'label' => 'Title',
	)) . '

			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['update']['message'],
		'data-min-height' => '100',
		'attachments' => $__vars['attachmentData']['attachments'],
		'data-preview-url' => $__templater->func('link', array('resources/update/preview', $__vars['update'], ), false),
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		'label' => 'Message',
	)) . '

			' . $__templater->formRow('
				' . $__compilerTemp1 . '
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
		'action' => $__templater->func('link', array('resources/update/edit', $__vars['update'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager',
	));
	return $__finalCompiled;
}
);