<?php
// FROM HASH: d5cf6a915bb0bba48d0a229cda183281
return array(
'macros' => array('tag_input' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'note' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextBox(array(
		'name' => 'tagged_username',
		'value' => ($__vars['note']['TaggedUser'] ? $__vars['note']['TaggedUser']['username'] : $__vars['note']['tagged_username']),
		'placeholder' => 'Name' . $__vars['xf']['language']['ellipsis'],
		'maxlength' => $__templater->func('max_length', array($__vars['note'], 'tagged_username', ), false),
		'ac' => 'single',
	)) . '
';
	return $__finalCompiled;
}
),
'note_input' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'note' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formTextArea(array(
		'name' => 'note_text',
		'value' => $__vars['note']['note_text'],
		'placeholder' => 'Write something' . $__vars['xf']['language']['ellipsis'],
		'maxlength' => $__templater->func('max_length', array($__vars['note'], 'note_text', ), false),
		'autosize' => 'true',
		'rows' => '2',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	if (!$__vars['note']['note_id']) {
		$__compilerTemp1 .= '
			' . $__templater->formRadio(array(
			'name' => 'note_type',
		), array(array(
			'label' => 'Tag a user' . $__vars['xf']['language']['label_separator'],
			'value' => 'user_tag',
			'selected' => (((!$__vars['note']['note_id']) OR ($__vars['note']['note_type'] == 'user_tag'))),
			'_dependent' => array($__templater->callMacro(null, 'tag_input', array(
			'note' => $__vars['note'],
		), $__vars)),
			'_type' => 'option',
		),
		array(
			'label' => 'Write a note' . $__vars['xf']['language']['label_separator'],
			'value' => 'note',
			'selected' => ($__vars['note']['note_type'] == 'note'),
			'_dependent' => array($__templater->callMacro(null, 'note_input', array(
			'note' => $__vars['note'],
		), $__vars)),
			'_type' => 'option',
		))) . '
		';
	} else {
		$__compilerTemp1 .= '
			';
		if ($__vars['note']['note_type'] == 'user_tag') {
			$__compilerTemp1 .= '
				' . 'Tag a user' . $__vars['xf']['language']['label_separator'] . '
				' . $__templater->callMacro(null, 'tag_input', array(
				'note' => $__vars['note'],
			), $__vars) . '
				' . $__templater->formHiddenVal('note_type', 'user_tag', array(
			)) . '
			';
		} else if ($__vars['note']['note_type'] == 'note') {
			$__compilerTemp1 .= '
				' . 'Write a note' . $__vars['xf']['language']['label_separator'] . '
				' . $__templater->callMacro(null, 'note_input', array(
				'note' => $__vars['note'],
			), $__vars) . '
				' . $__templater->formHiddenVal('note_type', 'note', array(
			)) . '
			';
		}
		$__compilerTemp1 .= '
		';
	}
	$__compilerTemp2 = '';
	if ($__vars['note']['note_id'] AND $__templater->method($__vars['note'], 'canDelete', array())) {
		$__compilerTemp2 .= '
			' . $__templater->button('', array(
			'type' => 'submit',
			'name' => 'delete',
			'class' => 'button--icon button--padded button--icon--delete button--iconOnly',
		), '', array(
		)) . '
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="noteTooltip-row">
		' . $__compilerTemp1 . '
	</div>

	<div class="noteTooltip-footer">
		' . $__templater->button('', array(
		'type' => 'submit',
		'icon' => 'save',
		'class' => 'button--primary button--padded',
	), '', array(
	)) . '
		' . $__compilerTemp2 . '
		' . $__templater->button('', array(
		'type' => 'reset',
		'icon' => 'cancel',
		'class' => 'button--padded js-cancelButton',
	), '', array(
	)) . '
	</div>

	' . $__templater->formHiddenVal('note_data', $__templater->filter($__vars['note']['note_data'], array(array('json', array()),), false), array(
		'class' => 'js-noteData',
	)) . '
	' . $__templater->formHiddenVal('note_id', $__vars['note']['note_id'], array(
	)) . '
', array(
		'action' => $__templater->func('link', array('media/note-edit', $__vars['mediaItem'], ), false),
		'class' => 'noteTooltip js-noteTooltipForm',
		'ajax' => 'true',
	)) . '

' . '

';
	return $__finalCompiled;
}
);