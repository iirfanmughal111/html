<?php
// FROM HASH: 022b5ac4a993bba04cd7d2edbeca8dfe
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add update');
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
		'rowType' => 'fullWidth',
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
		'rowType' => 'fullWidth',
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
		'rowType' => 'fullWidth',
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
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body" data-xf-init="attachment-manager">

			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'maxlength' => $__templater->func('max_length', array('XenAddons/Showcase:ItemUpdate', 'title', ), false),
		'placeholder' => 'Update title' . $__vars['xf']['language']['ellipsis'],
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Update title',
	)) . '

			' . $__compilerTemp1 . '
			
			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => $__vars['update']['message'],
		'data-min-height' => '200',
		'attachments' => $__vars['attachmentData']['attachments'],
		'data-preview-url' => $__templater->func('link', array('showcase/update-preview', $__vars['item'], ), false),
	), array(
		'rowtype' => 'fullWidth noLabel',
		'label' => 'Showcase update',
		'hint' => '',
		'explain' => '',
	)) . '

			' . $__compilerTemp3 . '

			' . $__compilerTemp5 . '
			
			' . $__templater->formRow('
				' . $__compilerTemp7 . '
			', array(
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'submit' => 'Post update',
		'icon' => 'add',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/add-update', $__vars['item'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);