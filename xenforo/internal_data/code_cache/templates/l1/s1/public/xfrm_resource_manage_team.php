<?php
// FROM HASH: b94cdacade5820419f2e3ccff5f99f8c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Manage resource team');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['resource'], 'canRemoveTeamMembers', array()) AND !$__templater->test($__vars['teamMembers'], 'empty', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = array();
		if ($__templater->isTraversable($__vars['teamMembers'])) {
			foreach ($__vars['teamMembers'] AS $__vars['teamMember']) {
				$__compilerTemp2[] = array(
					'value' => $__vars['teamMember']['user_id'],
					'label' => $__templater->escape($__vars['teamMember']['User']['username']),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp1 .= $__templater->formCheckBoxRow(array(
			'name' => 'remove_members',
		), $__compilerTemp2, array(
			'label' => 'Remove members',
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['resource'], 'canAddTeamMembers', array())) {
		$__compilerTemp3 .= '
				' . $__templater->formTokenInputRow(array(
			'name' => 'add_members',
			'href' => $__templater->func('link', array('members/find', ), false),
		), array(
			'label' => 'Add members',
			'explain' => '
						' . 'You may enter multiple names here.' . '
						' . 'You may have up to ' . $__templater->filter($__vars['maxMembers'], array(array('number', array()),), true) . ' team member(s).' . '
						' . 'Team members are not displayed publicly, except to moderators and team members themselves.' . '
					',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '

			' . $__compilerTemp3 . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('resources/manage-team', $__vars['resource'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);