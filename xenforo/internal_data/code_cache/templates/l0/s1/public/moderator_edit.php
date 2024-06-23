<?php
// FROM HASH: cfb2e531d8ff0f3dcc1de9c0748c11e1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit moderator' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['user']['username']));
	$__finalCompiled .= '

';
	if (($__vars['contentModerator'] ? $__templater->method($__vars['contentModerator'], 'isUpdate', array()) : $__templater->method($__vars['generalModerator'], 'isUpdate', array()))) {
		$__compilerTemp1 = '';
		if ($__vars['contentModerator']) {
			$__compilerTemp1 .= '
		' . $__templater->button('', array(
				'href' => $__templater->func('link', array('forumGroups/content/delete', $__vars['contentModerator'], ), false),
				'icon' => 'delete',
				'overlay' => 'true',
			), '', array(
			)) . '
	';
		} else {
			$__compilerTemp1 .= '
		' . $__templater->button('', array(
				'href' => $__templater->func('link', array('forumGroups/super/delete', $__vars['generalModerator'], ), false),
				'icon' => 'delete',
				'overlay' => 'true',
			), '', array(
			)) . '
	';
		}
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__compilerTemp1 . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__vars['contentTitle']) {
		$__compilerTemp2 .= '
					' . $__templater->escape($__vars['contentTitle']) . '
					';
	} else {
		$__compilerTemp2 .= '
					' . 'Super moderator' . '
				';
	}
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['interfaceGroups'])) {
		foreach ($__vars['interfaceGroups'] AS $__vars['interfaceGroupId'] => $__vars['interfaceGroup']) {
			$__compilerTemp3 .= '
					';
			if ($__vars['globalPermissions'][$__vars['interfaceGroupId']]) {
				$__compilerTemp3 .= '
						';
				$__compilerTemp4 = array();
				if ($__templater->isTraversable($__vars['globalPermissions'][$__vars['interfaceGroupId']])) {
					foreach ($__vars['globalPermissions'][$__vars['interfaceGroupId']] AS $__vars['permission']) {
						$__compilerTemp4[] = array(
							'name' => 'globalPermissions[' . $__vars['permission']['permission_group_id'] . '][' . $__vars['permission']['permission_id'] . ']',
							'value' => 'allow',
							'selected' => ($__vars['existingValues'][$__vars['permission']['permission_group_id']][$__vars['permission']['permission_id']] == 'allow'),
							'label' => $__templater->escape($__vars['permission']['title']),
							'_type' => 'option',
						);
					}
				}
				$__compilerTemp3 .= $__templater->formCheckBoxRow(array(
					'listclass' => 'listColumns',
				), $__compilerTemp4, array(
				)) . '
					';
			}
			$__compilerTemp3 .= '
				';
		}
	}
	$__compilerTemp5 = '';
	if ($__templater->isTraversable($__vars['interfaceGroups'])) {
		foreach ($__vars['interfaceGroups'] AS $__vars['interfaceGroupId'] => $__vars['interfaceGroup']) {
			$__compilerTemp5 .= '
					';
			if ($__vars['contentPermissions'][$__vars['interfaceGroupId']]) {
				$__compilerTemp5 .= '
						';
				$__compilerTemp6 = array();
				if ($__templater->isTraversable($__vars['contentPermissions'][$__vars['interfaceGroupId']])) {
					foreach ($__vars['contentPermissions'][$__vars['interfaceGroupId']] AS $__vars['permission']) {
						$__compilerTemp6[] = array(
							'name' => 'contentPermissions[' . $__vars['permission']['permission_group_id'] . '][' . $__vars['permission']['permission_id'] . ']',
							'value' => 'content_allow',
							'selected' => ($__vars['existingValues'][$__vars['permission']['permission_group_id']][$__vars['permission']['permission_id']] == 'content_allow'),
							'label' => $__templater->escape($__vars['permission']['title']),
							'_type' => 'option',
						);
					}
				}
				$__compilerTemp5 .= $__templater->formCheckBoxRow(array(
					'listclass' => 'listColumns',
				), $__compilerTemp6, array(
				)) . '
					';
			}
			$__compilerTemp5 .= '
				';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__compilerTemp2 . '
			', array(
		'label' => 'Type of moderator',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'is_staff',
		'selected' => $__vars['isStaff'],
		'label' => 'Display user as staff',
		'hint' => 'If selected, this user will be listed publicly as a staff member.',
		'_type' => 'option',
	)), array(
	)) . '			

			<div id="piGroups">
				
				' . $__compilerTemp3 . '

				' . $__compilerTemp5 . '

			</div>
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>

	' . $__templater->formHiddenVal('user_id', $__vars['generalModerator']['user_id'], array(
	)) . '
	' . $__templater->formHiddenVal('content_type', $__vars['contentModerator']['content_type'], array(
	)) . '
	' . $__templater->formHiddenVal('content_id', $__vars['contentModerator']['content_id'], array(
	)) . '
', array(
		'action' => $__templater->func('link', array('forumGroups/moderator-save', ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);