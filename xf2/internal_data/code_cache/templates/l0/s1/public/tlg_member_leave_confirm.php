<?php
// FROM HASH: cb6b310cd98f959270a25a7f90cab957
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Leave group');
	$__finalCompiled .= '

';
	if ($__vars['needWrapper']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = $__templater->preEscaped('members');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['member'], 'isOwner', array())) {
		$__compilerTemp2 .= '
            <div class="block-body">
                ' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'ac' => 'single',
		), array(
			'label' => 'Leave and reassign group for',
		)) . '
            </div>
        ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body block-row">' . 'Are you want to leave the group?' . '</div>

        ' . $__compilerTemp2 . '

        ' . $__templater->formSubmitRow(array(
		'submit' => 'Leave group',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('group-members/leave', $__vars['member'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);