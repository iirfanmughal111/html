<?php
// FROM HASH: 9bc9fd6d495a513f520e18034d51e83d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['fieldLabel']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['canDelete']) {
		$__compilerTemp1 .= '
                ' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'delete',
			'label' => 'Delete existing image',
			'_type' => 'option',
		)), array(
		)) . '
            ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formUploadRow(array(
		'name' => 'file',
	), array(
		'label' => $__templater->escape($__vars['fieldLabel']),
		'explain' => 'It is recommended that you use an image that is at least ' . $__templater->escape($__vars['baseWidth']) . 'x' . $__templater->escape($__vars['baseHeight']) . ' pixels.',
	)) . '

            ' . $__compilerTemp1 . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'submit' => 'Upload',
		'icon' => 'upload',
	), array(
		'rowtype' => 'simple',
	)) . '
    </div>
', array(
		'action' => $__vars['formAction'],
		'ajax' => 'true',
		'upload' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);