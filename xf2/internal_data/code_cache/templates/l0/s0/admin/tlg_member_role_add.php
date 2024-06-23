<?php
// FROM HASH: aa66bab1e4e0479ed715703b46bddfa7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__templater->method($__vars['memberRole'], 'exists', array()) ? 'Edit member role' : 'Add new member role'));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['roleGroups'])) {
		foreach ($__vars['roleGroups'] AS $__vars['roleGroupId']) {
			$__compilerTemp1 .= '
                    <a class="tabs-tab" role="tab" tabindex="0"
                       aria-controls="role-' . $__templater->escape($__vars['roleGroupId']) . '">' . $__templater->escape($__templater->method($__vars['roles'][$__vars['roleGroupId']], 'getRoleGroupTitle', array())) . '</a>
                ';
		}
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['memberRole'], 'exists', array())) {
		$__compilerTemp2 .= '
                        ' . $__templater->formRow($__templater->escape($__vars['memberRole']['member_role_id']), array(
			'label' => 'Member Role ID',
		)) . '
                        ';
	} else {
		$__compilerTemp2 .= '
                        ' . $__templater->formTextBoxRow(array(
			'name' => 'new_member_role_id',
			'maxlength' => $__templater->func('max_length', array($__vars['memberRole'], 'member_role_id', ), false),
		), array(
			'label' => 'Member Role ID',
			'explain' => 'This is the unique identifier for this field. It cannot be changed once set. Only accept there characters: a-z A-Z 0-9',
		)) . '
                    ';
	}
	$__compilerTemp3 = $__templater->mergeChoiceOptions(array(), $__vars['userGroups']);
	$__compilerTemp4 = '';
	if ($__templater->isTraversable($__vars['roleGroups'])) {
		foreach ($__vars['roleGroups'] AS $__vars['roleGroupId']) {
			$__compilerTemp4 .= '
                <li id="role-' . $__templater->escape($__vars['roleGroupId']) . '" role="tabpanel">
                    <div class="block-body">
                        ';
			$__compilerTemp5 = '';
			if ($__templater->isTraversable($__vars['roles'][$__vars['roleGroupId']])) {
				foreach ($__vars['roles'][$__vars['roleGroupId']] AS $__vars['roleId'] => $__vars['roleRef']) {
					$__compilerTemp5 .= '
                                    <li class="inputChoices-choice">
                                        ' . $__templater->formCheckBox(array(
					), array(array(
						'name' => 'role_permissions[' . $__vars['roleGroupId'] . '][' . $__vars['roleId'] . ']',
						'label' => $__templater->escape($__vars['roleRef']['title']),
						'selected' => ($__templater->method($__vars['memberRole'], 'hasRole', array($__vars['roleGroupId'], $__vars['roleId'], )) ? 1 : 0),
						'hint' => ($__vars['xf']['debug'] ? (('(' . $__templater->escape($__vars['roleId'])) . ') ') : '') . $__templater->escape($__vars['roleRef']['explain']),
						'_type' => 'option',
					))) . '
                                    </li>
                                ';
				}
			}
			$__compilerTemp4 .= $__templater->formRow('
                            <ul class="inputChoices" id="perms-' . $__templater->escape($__vars['roleGroupId']) . '">
                                ' . $__compilerTemp5 . '
                                <li class="u-muted inputChoices-choice" style="margin-left: 1.5em">
                                    ' . $__templater->formCheckBox(array(
				'standalone' => 'true',
			), array(array(
				'name' => '_',
				'data-xf-init' => 'check-all',
				'data-container' => '#perms-' . $__vars['roleGroupId'],
				'label' => 'Check All',
				'_type' => 'option',
			))) . '
                                </li>
                            </ul>
                        ', array(
				'label' => 'Permissions',
			)) . '
                    </div>
                </li>
            ';
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <h2 class="block-tabHeader tabs hScroller" data-xf-init="tabs h-scroller" role="tablist">
            <span class="hScroller-scroll">
                <a class="tabs-tab is-active" role="tab" tabindex="0" aria-controls="basic-info">' . 'Basic' . '</a>
                ' . $__compilerTemp1 . '
            </span>
        </h2>

        <ul class="tabPanes">
            <li id="basic-info" class="is-active" role="tabpanel">
                <div class="block-body">
                    ' . $__compilerTemp2 . '

                    ' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['memberRole']['title'],
	), array(
		'label' => 'Title',
	)) . '
                    ' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['memberRole']['description'],
	), array(
		'label' => 'Description',
	)) . '

                    ' . $__templater->formNumberBoxRow(array(
		'name' => 'memberRole[display_order]',
		'value' => $__vars['memberRole']['display_order'],
	), array(
		'label' => 'Display order',
	)) . '

                    ' . $__templater->formCheckBoxRow(array(
		'name' => 'memberRole[user_group_ids][]',
		'value' => $__vars['memberRole']['user_group_ids'],
	), $__compilerTemp3, array(
		'label' => 'Promote to user groups',
		'explain' => 'The extra user groups members has when be promoted to this member role. When member be removed out of this member role, the user groups will be revoke.',
	)) . '
                </div>
            </li>

            ' . $__compilerTemp4 . '
        </ul>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
    </div>

', array(
		'action' => $__templater->func('link', array('group-member-roles/save', $__vars['memberRole'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);