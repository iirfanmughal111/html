<?php
// FROM HASH: 30ea6f02b8b90f67778e86756d406ad0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Promote members');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['memberRoles'])) {
		foreach ($__vars['memberRoles'] AS $__vars['memberRole']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['memberRole']['member_role_id'],
				'label' => $__templater->escape($__vars['memberRole']['title']),
				'hint' => $__templater->escape($__vars['memberRole']['description']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['members'])) {
		foreach ($__vars['members'] AS $__vars['member']) {
			$__compilerTemp2 .= '
        ' . $__templater->formHiddenVal('ids[]', $__vars['member']['member_id'], array(
			)) . '
    ';
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formInfoRow('Are you sure you want to promote ' . $__templater->escape($__vars['total']) . ' members?', array(
		'rowtype' => 'confirm',
	)) . '

            ' . $__templater->formRadioRow(array(
		'name' => 'member_role_id',
	), $__compilerTemp1, array(
		'label' => 'Change member role to',
	)) . '
        </div>
        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>

    ' . $__compilerTemp2 . '

    ' . $__templater->formHiddenVal('type', 'tl_group_member', array(
	)) . '
    ' . $__templater->formHiddenVal('action', 'promote', array(
	)) . '
    ' . $__templater->formHiddenVal('confirmed', '1', array(
	)) . '
    ' . $__templater->formHiddenVal('group_id', $__vars['group']['group_id'], array(
	)) . '

    ' . $__templater->func('redirect_input', array($__vars['redirect'], null, true)) . '
', array(
		'action' => $__templater->func('link', array('inline-mod', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);