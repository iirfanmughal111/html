<?php
// FROM HASH: f8651e63b1957dd3be3384391281081e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__templater->method($__vars['resource'], 'exists', array()) ? 'Edit resource' : 'Add resource'));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('resources');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title">
        <h2 class="p-title-value">' . ($__templater->method($__vars['resource'], 'exists', array()) ? 'Edit resource' : 'Add resource') . '</h2>
    </div>
</div>

';
	$__compilerTemp2 = '';
	if (!$__templater->method($__vars['resource'], 'exists', array())) {
		$__compilerTemp2 .= '
                <hr class="formRowSep" />
                ';
		$__vars['recommendSize'] = $__templater->preEscaped($__templater->escape($__vars['xf']['app']['options']['tl_groups_resourceIconSize']));
		$__compilerTemp2 .= '
                ' . $__templater->formRadioRow(array(
			'name' => 'icon_type',
			'value' => ($__vars['resource']['icon_url'] ? 'remote' : 'local'),
		), array(array(
			'value' => 'local',
			'label' => 'Upload from your device',
			'_dependent' => array('
                            ' . $__templater->formUpload(array(
			'name' => 'resource_icon',
		)) . '
                        '),
			'_type' => 'option',
		),
		array(
			'value' => 'remote',
			'label' => 'Remote image URL',
			'_dependent' => array('
                            ' . $__templater->formTextBox(array(
			'name' => 'icon_url',
			'value' => $__vars['resource']['icon_url'],
			'maxlength' => $__templater->func('max_length', array($__vars['resource'], 'icon_url', ), false),
		)) . '
                        '),
			'_type' => 'option',
		)), array(
			'label' => 'Resource icon',
			'explain' => 'It is recommended that you use an image that is at least ' . $__templater->escape($__vars['recommendSize']) . 'x' . $__templater->escape($__vars['recommendSize']) . ' pixels.',
		)) . '
            ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['resource']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['resource'], 'title', ), false),
	), array(
		'label' => 'Title',
	)) . '
            ' . $__templater->formEditorRow(array(
		'name' => 'message',
		'value' => ($__vars['resource']['FirstComment'] ? $__vars['resource']['FirstComment']['message'] : ''),
	), array(
		'label' => 'Description',
	)) . '

            ' . $__templater->formRow('
                ' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
		'attachmentData' => $__vars['attachmentData'],
	), $__vars) . '
            ', array(
		'label' => 'Uploaded files',
		'data-xf-init' => 'attachment-manager',
	)) . '

            ' . $__compilerTemp2 . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>
', array(
		'action' => ($__templater->method($__vars['resource'], 'exists', array()) ? $__templater->func('link', array('group-resources/edit', $__vars['resource'], ), false) : $__templater->func('link', array('group-resources/add', null, array('group_id' => $__vars['group']['group_id'], ), ), false)),
		'ajax' => 'true',
		'upload' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);