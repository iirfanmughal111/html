<?php
// FROM HASH: 90dbb6fdf3e3d0c79a8fb3aabbb29826
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Change resource icon');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('resources');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__vars['recommendSize'] = $__templater->preEscaped($__templater->escape($__vars['xf']['app']['options']['tl_groups_resourceIconSize']));
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . '' . '
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

            ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'delete',
		'value' => '1',
		'label' => 'Delete current icon',
		'_type' => 'option',
	)), array(
		'label' => '',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('group-resources/icon', $__vars['resource'], ), false),
		'upload' => 'true',
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);