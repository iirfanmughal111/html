<?php
// FROM HASH: 40cd90927fec8e0c9fbd00cd24b7517c
return array(
'macros' => array('user_group_field' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'name' => '!',
		'userGroups' => '!',
		'label' => '!',
		'explain' => null,
		'value' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['userGroups']);
	$__finalCompiled .= $__templater->formRadioRow(array(
		'name' => $__vars['name'] . '_type',
	), array(array(
		'value' => '1',
		'selected' => (($__vars['value'] === -1) ? true : false),
		'label' => 'All',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'label' => 'All users belong to this user groups',
		'selected' => (($__vars['value'] !== -1) ? true : false),
		'_dependent' => array($__templater->formCheckBox(array(
		'name' => $__vars['name'] . '[]',
		'value' => $__vars['value'],
		'listclass' => 'listColumns',
	), $__compilerTemp1)),
		'_type' => 'option',
	)), array(
		'label' => $__templater->escape($__vars['label']),
		'explain' => $__templater->escape($__vars['explain']),
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(($__templater->method($__vars['category'], 'exists', array()) ? 'Edit category' : 'Add new category'));
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'label' => $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['category_title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = $__templater->mergeChoiceOptions(array(), $__vars['navigationTabs']);
	$__compilerTemp4 = array(array(
		'value' => '',
		'label' => 'Inherit from options',
		'_type' => 'option',
	));
	$__compilerTemp4 = $__templater->mergeChoiceOptions($__compilerTemp4, $__vars['navigationTabs']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
            ' . $__templater->formTextBoxRow(array(
		'name' => 'category_title',
		'value' => $__vars['category']['category_title'],
		'maxlength' => $__templater->func('max_length', array($__vars['category'], 'category_title', ), false),
	), array(
		'label' => 'Title',
	)) . '
            ' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['category']['description'],
		'autosize' => 'true',
		'maxlength' => $__templater->func('max_length', array($__vars['category'], 'description', ), false),
	), array(
		'label' => 'Description',
		'hint' => 'You may use HTML',
	)) . '
            <hr class="formRowSep" />

            ' . $__templater->formSelectRow(array(
		'name' => 'parent_category_id',
		'value' => $__vars['category']['parent_category_id'],
	), $__compilerTemp1, array(
		'label' => 'Parent category',
	)) . '

            ' . $__templater->callMacro('display_order_macros', 'row', array(
		'name' => 'display_order',
		'value' => $__vars['category']['display_order'],
		'explain' => 'The position of this item relative to other nodes with the same parent.',
	), $__vars) . '

            <hr class="formRowSep" />
            ' . $__templater->formRadioRow(array(
		'name' => 'default_privacy',
		'value' => $__vars['category']['default_privacy'],
	), array(array(
		'value' => 'public',
		'label' => 'Public Group',
		'hint' => 'Anyone can find the group and view it\'s content.',
		'_type' => 'option',
	),
	array(
		'value' => 'closed',
		'label' => 'Closed Group',
		'hint' => 'Anyone can find the group. Only members of the group have permission to view group content.',
		'_type' => 'option',
	),
	array(
		'value' => 'secret',
		'label' => 'Secret Group',
		'hint' => 'Only members of the group have permission to view the group and its content.',
		'_type' => 'option',
	)), array(
		'label' => 'Default privacy',
	)) . '
            ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'always_moderate',
		'selected' => $__vars['category']['always_moderate'],
		'label' => 'Always moderation new groups published this category.',
		'_type' => 'option',
	)), array(
		'label' => '',
	)) . '
            ' . $__templater->formNumberBoxRow(array(
		'name' => 'min_tags',
		'value' => $__vars['category']['min_tags'],
		'min' => '0',
	), array(
		'label' => 'Minimum required tags',
		'explain' => 'This will require users to provide at least this many tags when creating a group.',
	)) . '

            ' . $__templater->formCheckBoxRow(array(
		'listclass' => 'listColumns',
		'name' => 'disabled_navigation_tabs[]',
		'value' => $__vars['category']['disabled_navigation_tabs'],
	), $__compilerTemp3, array(
		'label' => 'Disabled navigation tabs',
		'explain' => 'Tabs will not show in group navigation, it does not disable tabs completely.',
	)) . '

            ' . $__templater->formRadioRow(array(
		'name' => 'default_tab',
		'value' => $__vars['category']['default_tab'],
		'listclass' => 'listColumns',
	), $__compilerTemp4, array(
		'label' => 'Default group tab',
		'explain' => 'The tab content will be loaded when viewing group.',
	)) . '

            ' . $__templater->callMacro(null, 'user_group_field', array(
		'label' => 'Allow viewable user groups',
		'explain' => 'All users belong to this user groups can view category and it\'s content.',
		'name' => 'allow_view_user_group_ids',
		'userGroups' => $__vars['userGroups'],
		'value' => $__vars['selViewUserGroups'],
	), $__vars) . '

            ' . $__templater->callMacro(null, 'user_group_field', array(
		'label' => 'Allow create groups',
		'explain' => 'All users belong to this user groups can create new groups in this category.',
		'name' => 'allow_create_user_group_ids',
		'userGroups' => $__vars['userGroups'],
		'value' => $__vars['selCreateUserGroups'],
	), $__vars) . '

            ' . '
		</div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('group-categories/save', $__vars['category'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	)) . '

';
	return $__finalCompiled;
}
);