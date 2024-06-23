<?php
// FROM HASH: b464238cecfd0cba2ada7c3bc399775c
return array(
'macros' => array('user_criteria' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'criteria' => '!',
		'data' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_criteria[tlg_has_group][rule]',
		'value' => 'tlg_has_group',
		'label' => 'Joined any groups',
		'selected' => $__vars['criteria']['tlg_has_group'],
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[tlg_has_manage_group][rule]',
		'value' => 'tlg_has_manage_group',
		'label' => 'Manage any groups',
		'selected' => $__vars['criteria']['tlg_has_manage_group'],
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[tlg_member_of_groups][rule]',
		'value' => 'tlg_member_of_groups',
		'selected' => $__vars['criteria']['tlg_member_of_groups'],
		'label' => 'Member of specific groups',
		'_dependent' => array($__templater->formTextBox(array(
		'name' => 'user_criteria[tlg_member_of_groups][data][ids]',
		'dir' => 'ltr',
		'value' => $__vars['criteria']['tlg_member_of_groups']['ids'],
	))),
		'afterhint' => 'Each group ID must be separate by comma (,). And only valid members are applied by this rule.',
		'_type' => 'option',
	)), array(
		'label' => '[tl] Social Groups: Options',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);