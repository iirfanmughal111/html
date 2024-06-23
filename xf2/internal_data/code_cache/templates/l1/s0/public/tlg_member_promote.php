<?php
// FROM HASH: 6070134787262acc68a690ac6acf4153
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Promote member' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['member']['username']));
	$__finalCompiled .= '

';
	if (!$__vars['inlinePromote']) {
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
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['memberRoles'])) {
		foreach ($__vars['memberRoles'] AS $__vars['memberRole']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['memberRole']['member_role_id'],
				'selected' => (($__vars['memberRole']['member_role_id'] == $__vars['member']['member_role_id']) ? 1 : 0),
				'label' => $__templater->escape($__vars['memberRole']['title']),
				'hint' => $__templater->escape($__vars['memberRole']['description']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formRadioRow(array(
		'name' => 'member_role_id',
	), $__compilerTemp2, array(
		'label' => 'Change member role to',
		'explain' => '<a href="' . $__templater->func('link', array('groups/browse/roles', null, array('group_id' => $__vars['member']['group_id'], ), ), true) . '" data-xf-click="overlay">Read more</a> about role permissions.',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'submit' => 'Promote member',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('group-members/promote', $__vars['member'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '

';
	return $__finalCompiled;
}
);