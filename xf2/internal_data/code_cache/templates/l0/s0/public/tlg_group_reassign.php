<?php
// FROM HASH: 58275da6ab666c4ba27ca335cc6c580c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Reassign group');
	$__finalCompiled .= '

';
	if (!$__vars['quickAssign']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['selected'] = $__templater->preEscaped('about');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'value' => '',
		'ac' => 'single',
	), array(
		'label' => 'Reassign to user',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('groups/reassign', $__vars['group'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);