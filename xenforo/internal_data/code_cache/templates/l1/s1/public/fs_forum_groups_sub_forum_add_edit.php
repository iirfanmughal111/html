<?php
// FROM HASH: 49a5c92db1ca7d37b4d5a686bb281bd7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['forum'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add forum');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit forum' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['node']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['forum'], 'isUpdate', array())) {
		$__compilerTemp1 = '';
		if ($__vars['canChangeForumType']) {
			$__compilerTemp1 .= '
			' . $__templater->button('Change type', array(
				'href' => $__templater->func('link', array('forumGroups/change-type', $__vars['node'], ), false),
				'overlay' => 'true',
			), '', array(
			)) . '
		';
		}
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	<div class="buttonGroup">
		' . $__compilerTemp1 . '
		' . $__templater->button('', array(
			'href' => $__templater->func('link', array('forumGroups/delete', $__vars['node'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
	</div>
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canSetSiropuChatRoomUsers', array())) {
		$__compilerTemp2 .= '
				' . $__templater->formTokenInputRow(array(
			'name' => 'room_users',
			'value' => $__templater->filter($__templater->func('array_values', array($__vars['room']['room_users'], ), false), array(array('join', array(',', )),), false),
			'href' => $__templater->func('link', array('members/find', ), false),
		), array(
			'label' => 'Room users',
			'explain' => 'The names of the users who can join the room. Users will automatically join the room and get a notification about it.',
			'hint' => 'Optional',
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['xf']['visitor'], 'canPasswordProtectSiropuChatRooms', array())) {
		$__compilerTemp3 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'room_password',
			'value' => $__vars['room']['room_password'],
			'maxlength' => $__templater->func('max_length', array($__vars['room'], 'room_password', ), false),
		), array(
			'label' => 'Room password',
			'explain' => 'Allow room access using a password.',
			'hint' => 'Optional',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('node_edit_macros', 'title', array(
		'node' => $__vars['node'],
	), $__vars) . '
			' . $__templater->callMacro('node_edit_macros', 'description', array(
		'node' => $__vars['node'],
	), $__vars) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'replace_route',
		'value' => $__vars['routeFilter']['replace_route_readable'],
		'maxlength' => $__templater->func('max_length', array($__vars['routeFilter'], 'replace_route', ), false),
		'dir' => 'ltr',
		'required' => 'required',
	), array(
		'label' => 'Replace with',
		'explain' => 'The find and replace fields support wildcards in the format of {name}, with a unique name. The same wildcards should be found in both fields. To limit the wildcard to digits, use {name:digit}; to limit to a string, use {name:string}; {name} will match anything but a forward slash.',
		'hint' => 'Required',
	)) . '
			
			
			 ' . $__templater->formUploadRow(array(
		'name' => 'avatarFile',
	), array(
		'label' => 'Group Avatar',
		'explain' => 'It is recommended that you use an image that is at least ' . $__templater->escape($__vars['avatarWidth']) . 'x' . $__templater->escape($__vars['avatarHeight']) . ' pixels.',
	)) . '
			
			' . $__templater->formUploadRow(array(
		'name' => 'coverFile',
	), array(
		'label' => 'Group Cover',
		'explain' => 'It is recommended that you use an image that is at least ' . $__templater->escape($__vars['coverWidth']) . 'x' . $__templater->escape($__vars['coverHeight']) . ' pixels.',
	)) . '
				
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'room_name',
		'value' => $__vars['room']['room_name'],
		'maxlength' => $__templater->func('max_length', array($__vars['room'], 'room_name', ), false),
	), array(
		'label' => 'Room name',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'room_description',
		'value' => $__vars['room']['room_description'],
		'maxlength' => $__templater->func('max_length', array($__vars['room'], 'room_description', ), false),
		'rows' => '3',
	), array(
		'label' => 'Room description',
	)) . '
			
			' . $__compilerTemp2 . '

			' . $__compilerTemp3 . '

			' . $__templater->formHiddenVal('forum_type_id', $__vars['forumTypeId'], array(
	)) . '

			<hr class="formRowSep" />
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('forumGroups/save', $__vars['node'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);