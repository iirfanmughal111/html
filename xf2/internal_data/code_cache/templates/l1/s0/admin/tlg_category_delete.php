<?php
// FROM HASH: 4b55364fd81fe960328f7bb19735dcaf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['category'], 'hasChildren', array())) {
		$__compilerTemp1 .= '
                ' . $__templater->formRadioRow(array(
			'name' => 'child_nodes_action',
		), array(array(
			'value' => 'move',
			'selected' => true,
			'label' => 'Attach this categories children to it\'s parent',
			'_type' => 'option',
		),
		array(
			'value' => 'delete',
			'label' => 'Delete this categories children',
			'_type' => 'option',
		)), array(
		)) . '
            ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formInfoRow('
                ' . 'Please confirm that you want to delete the following category and all groups belong to this' . $__vars['xf']['language']['label_separator'] . '
                <strong><a href="' . $__templater->func('link', array('group-categories/edit', $__vars['category'], ), true) . '">' . $__templater->escape($__vars['category']['category_title']) . '</a></strong>
            ', array(
		'rowtype' => 'confirm',
	)) . '

            ' . $__compilerTemp1 . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => ((!$__templater->method($__vars['category'], 'hasChildren', array())) ? 'simple' : ''),
	)) . '
    </div>
    ' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('group-categories/delete', $__vars['category'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);