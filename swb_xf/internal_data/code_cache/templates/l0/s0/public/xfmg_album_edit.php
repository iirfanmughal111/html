<?php
// FROM HASH: 1fb412ee711b14e2db9647d597cc31a8
return array(
'macros' => array('change_privacy' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
		'addUsers' => array(),
		'viewUsers' => array(),
		'values' => array('private', 'members', 'public', 'shared', ),
		'rows' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->callMacro(null, 'change_privacy_view', array(
		'album' => $__vars['album'],
		'viewUsers' => $__vars['viewUsers'],
		'values' => $__vars['values'],
		'row' => $__vars['rows'],
	), $__vars) . '
	' . $__templater->callMacro(null, 'change_privacy_add', array(
		'album' => $__vars['album'],
		'addUsers' => $__vars['addUsers'],
		'values' => $__vars['values'],
		'row' => $__vars['rows'],
	), $__vars) . '
';
	return $__finalCompiled;
}
),
'change_privacy_view' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
		'valueOverride' => '',
		'viewUsers' => array(),
		'values' => array('private', 'members', 'public', 'shared', ),
		'row' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['album'], 'isInsert', array())) {
		$__compilerTemp1 .= '
			';
		$__vars['viewName'] = $__templater->preEscaped('album[view_privacy]');
		$__compilerTemp1 .= '
			';
		$__vars['usersName'] = $__templater->preEscaped('album[view_users]');
		$__compilerTemp1 .= '
		';
	} else {
		$__compilerTemp1 .= '
			';
		$__vars['viewName'] = $__templater->preEscaped('view_privacy');
		$__compilerTemp1 .= '
			';
		$__vars['usersName'] = $__templater->preEscaped('view_users');
		$__compilerTemp1 .= '
		';
	}
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['values'])) {
		foreach ($__vars['values'] AS $__vars['value']) {
			if ($__vars['value'] != 'shared') {
				$__compilerTemp2[] = array(
					'value' => $__vars['value'],
					'label' => $__templater->escape($__templater->method($__vars['album'], 'getPrivacyPhrase', array($__vars['value'], ))),
					'_type' => 'option',
				);
			} else {
				$__compilerTemp2[] = array(
					'value' => $__vars['value'],
					'label' => $__templater->escape($__templater->method($__vars['album'], 'getPrivacyPhrase', array($__vars['value'], ))) . $__templater->escape($__vars['xf']['language']['label_separator']),
					'_dependent' => array('
							' . $__templater->formTokenInput(array(
					'name' => $__vars['usersName'],
					'value' => $__templater->filter($__vars['viewUsers'], array(array('join', array(', ', )),), false),
					'href' => $__templater->func('link', array('members/find', ), false),
				)) . '
						'),
					'html' => '
							<div class="formRow-explain">
								' . 'Enter the names above of the members who are allowed to view media items. The album owner is always permitted automatically.' . '
							</div>
						',
					'_type' => 'option',
				);
			}
		}
	}
	$__vars['input'] = $__templater->preEscaped('
		' . $__compilerTemp1 . '
		' . $__templater->formRadio(array(
		'name' => $__vars['viewName'],
		'value' => ($__vars['valueOverride'] ?: $__vars['album']['view_privacy']),
	), $__compilerTemp2) . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		' . $__templater->formRow('
			' . $__templater->filter($__vars['input'], array(array('raw', array()),), true) . '
		', array(
			'label' => 'Can view media items',
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['input'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'change_privacy_add' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
		'addUsers' => array(),
		'values' => array('private', 'members', 'public', 'shared', ),
		'row' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['album'], 'isInsert', array())) {
		$__compilerTemp1 .= '
			';
		$__vars['addName'] = $__templater->preEscaped('album[add_privacy]');
		$__compilerTemp1 .= '
			';
		$__vars['usersName'] = $__templater->preEscaped('album[add_users]');
		$__compilerTemp1 .= '
		';
	} else {
		$__compilerTemp1 .= '
			';
		$__vars['addName'] = $__templater->preEscaped('add_privacy');
		$__compilerTemp1 .= '
			';
		$__vars['usersName'] = $__templater->preEscaped('add_users');
		$__compilerTemp1 .= '
		';
	}
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['values'])) {
		foreach ($__vars['values'] AS $__vars['value']) {
			if ($__vars['value'] != 'shared') {
				$__compilerTemp2[] = array(
					'value' => $__vars['value'],
					'label' => $__templater->escape($__templater->method($__vars['album'], 'getPrivacyPhrase', array($__vars['value'], ))),
					'_type' => 'option',
				);
			} else {
				$__compilerTemp2[] = array(
					'value' => $__vars['value'],
					'label' => $__templater->escape($__templater->method($__vars['album'], 'getPrivacyPhrase', array($__vars['value'], ))) . $__templater->escape($__vars['xf']['language']['label_separator']),
					'_dependent' => array('
							' . $__templater->formTokenInput(array(
					'name' => $__vars['usersName'],
					'value' => $__templater->filter($__vars['addUsers'], array(array('join', array(', ', )),), false),
					'href' => $__templater->func('link', array('members/find', ), false),
				)) . '
						'),
					'html' => '
							<div class="formRow-explain">
								' . 'Enter the names above of the members who are allowed to add media items. The album owner is always permitted automatically.' . '
							</div>
						',
					'_type' => 'option',
				);
			}
		}
	}
	$__vars['input'] = $__templater->preEscaped('
		' . $__compilerTemp1 . '
		' . $__templater->formRadio(array(
		'name' => $__vars['addName'],
		'value' => $__vars['album']['add_privacy'],
	), $__compilerTemp2) . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		' . $__templater->formRow('
			' . $__templater->filter($__vars['input'], array(array('raw', array()),), true) . '
		', array(
			'label' => 'Can add media items',
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['input'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit album');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['album'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['album'], 'canChangePrivacy', array())) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('xfmg_album_edit', 'change_privacy', array(
			'album' => $__vars['album'],
			'addUsers' => $__vars['addUsers'],
			'viewUsers' => $__vars['viewUsers'],
		), $__vars) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['album'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp2 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['album']['title_'],
		'maxlength' => $__templater->func('max_length', array($__vars['album'], 'title', ), false),
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => $__vars['album']['description_'],
		'autosize' => 'true',
		'maxlength' => $__templater->func('max_length', array($__vars['album'], 'description', ), false),
	), array(
		'label' => 'Description',
	)) . '

			' . $__compilerTemp1 . '

			' . $__compilerTemp2 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media/albums/edit', $__vars['album'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '

' . '

' . '

';
	return $__finalCompiled;
}
);