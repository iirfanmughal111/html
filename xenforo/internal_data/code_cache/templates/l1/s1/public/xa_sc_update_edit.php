<?php
// FROM HASH: fba36915e75143ceb2aabfd240e53019
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('xa_sc_edit_update');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_updates',
		'set' => $__vars['update']['custom_fields'],
		'group' => 'above',
		'editMode' => $__templater->method($__vars['update'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['update_field_cache'],
		'rowType' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
				' . $__compilerTemp2 . '
			';
	}
	$__compilerTemp3 = '';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_updates',
		'set' => $__vars['update']['custom_fields'],
		'group' => 'below',
		'editMode' => $__templater->method($__vars['update'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['update_field_cache'],
		'rowType' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__compilerTemp3 .= '
				' . $__compilerTemp4 . '
			';
	}
	$__compilerTemp5 = '';
	$__compilerTemp6 = '';
	$__compilerTemp6 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'sc_updates',
		'set' => $__vars['update']['custom_fields'],
		'group' => 'self_place',
		'editMode' => $__templater->method($__vars['update'], 'getFieldEditMode', array()),
		'onlyInclude' => $__vars['category']['update_field_cache'],
		'rowType' => ($__vars['quickEdit'] ? 'fullWidth' : ''),
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp6)) > 0) {
		$__compilerTemp5 .= '
				' . $__compilerTemp6 . '
			';
	}
	$__compilerTemp7 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp7 .= '
					' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
				';
	}
	$__compilerTemp8 = '';
	if ($__templater->method($__vars['update'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp8 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
			'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		)) . '
			';
	}
	$__compilerTemp9 = '';
	if ($__vars['quickEdit']) {
		$__compilerTemp9 .= '
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

			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['update']['title_'],
		'maxlength' => $__templater->func('max_length', array($__vars['update'], 'title', ), false),
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		'label' => 'Update title',
		'hint' => 'Required',
		'explain' => '',
	)) . '

			' . $__compilerTemp1 . '

			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['update']['message'],
		'attachments' => $__vars['attachmentData']['attachments'],
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
		'label' => 'Message',
	)) . '

			' . $__compilerTemp3 . '

			' . $__compilerTemp5 . '

			' . $__templater->formRow('
				' . $__compilerTemp7 . '
			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '

			' . $__templater->formRow('
				' . $__templater->callMacro('helper_action', 'edit_type', array(
		'canEditSilently' => $__templater->method($__vars['update'], 'canEditSilently', array()),
	), $__vars) . '
			', array(
		'rowtype' => ($__vars['quickEdit'] ? 'fullWidth noLabel' : ''),
	)) . '

			' . $__compilerTemp8 . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
		'rowtype' => ($__vars['quickEdit'] ? 'simple' : ''),
		'html' => '
				' . $__compilerTemp9 . '
			',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/update/edit', $__vars['update'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager',
		'data-preview-url' => $__templater->func('link', array('showcase/update/preview', $__vars['update'], ), false),
	));
	return $__finalCompiled;
}
);