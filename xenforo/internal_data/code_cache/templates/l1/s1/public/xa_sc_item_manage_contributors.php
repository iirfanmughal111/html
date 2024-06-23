<?php
// FROM HASH: 60d2d7c3747759c8b27b8f1fbfb520d7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Manage contributors/co-owners');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['item'], 'canRemoveContributors', array()) AND !$__templater->test($__vars['contributors'], 'empty', array())) {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = array();
		if ($__templater->isTraversable($__vars['contributors'])) {
			foreach ($__vars['contributors'] AS $__vars['contributor']) {
				if ($__vars['contributor']['is_co_owner']) {
					$__compilerTemp2[] = array(
						'value' => $__vars['contributor']['user_id'],
						'label' => $__templater->escape($__vars['contributor']['User']['username']) . ' - ' . 'Co-owner',
						'_type' => 'option',
					);
				}
			}
		}
		if ($__templater->isTraversable($__vars['contributors'])) {
			foreach ($__vars['contributors'] AS $__vars['contributor']) {
				if (!$__vars['contributor']['is_co_owner']) {
					$__compilerTemp2[] = array(
						'value' => $__vars['contributor']['user_id'],
						'label' => $__templater->escape($__vars['contributor']['User']['username']),
						'_type' => 'option',
					);
				}
			}
		}
		$__compilerTemp1 .= $__templater->formCheckBoxRow(array(
			'name' => 'remove_contributors',
		), $__compilerTemp2, array(
			'label' => 'Remove contributors/co-owners',
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['item'], 'canAddContributors', array())) {
		$__compilerTemp3 .= '
				' . $__templater->formTokenInputRow(array(
			'name' => 'add_contributors',
			'href' => $__templater->func('link', array('members/find', ), false),
		), array(
			'label' => 'Add contributors',
			'explain' => '
						' . 'You may enter multiple names here.' . '
						' . 'You may have up to a combination of ' . $__templater->filter($__vars['maxContributors'], array(array('number', array()),), true) . ' contributor(s) and or co-owner(s).' . '
					',
		)) . '
			';
	}
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['item'], 'canAddCoOwners', array())) {
		$__compilerTemp4 .= '
				' . $__templater->formTokenInputRow(array(
			'name' => 'add_co_owners',
			'href' => $__templater->func('link', array('members/find', ), false),
		), array(
			'label' => 'Add co-owners',
			'explain' => '
						' . 'You may enter multiple names here.' . '
						' . 'You may have up to a combination of ' . $__templater->filter($__vars['maxContributors'], array(array('number', array()),), true) . ' contributor(s) and or co-owner(s).' . '
					',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '

			' . $__compilerTemp3 . '

			' . $__compilerTemp4 . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/manage-contributors', $__vars['item'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);