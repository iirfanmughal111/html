<?php
// FROM HASH: dbb62681c8f3ae523e5ed15a32d9001c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[title]',
		'value' => $__vars['criteria']['title'],
		'type' => 'search',
	), array(
		'label' => 'Title',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[username]',
		'value' => $__vars['criteria']['username'],
		'type' => 'search',
	), array(
		'label' => 'Created by',
	)) . '

<hr class="formRowSep" />

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formDateInput(array(
		'name' => 'criteria[create_date][start]',
		'value' => $__vars['criteria']['create_date']['start'],
		'size' => '15',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formDateInput(array(
		'name' => 'criteria[create_date][end]',
		'value' => $__vars['criteria']['create_date']['end'],
		'size' => '15',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Created between',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[comment_count][start]',
		'value' => $__vars['criteria']['comment_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[comment_count][end]',
		'value' => $__vars['criteria']['comment_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Comment count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[reaction_score][start]',
		'value' => $__vars['criteria']['reaction_score']['start'],
		'size' => '5',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[reaction_score][end]',
		'value' => $__vars['criteria']['reaction_score']['end'],
		'size' => '5',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Reaction score between',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[view_count][start]',
		'value' => $__vars['criteria']['view_count']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[view_count][end]',
		'value' => $__vars['criteria']['view_count']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'View count between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

<hr class="formRowSep" />

' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[album_state]',
	), array(array(
		'value' => 'visible',
		'selected' => $__templater->func('in_array', array('visible', $__vars['criteria']['album_state'], ), false),
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted',
		'selected' => $__templater->func('in_array', array('deleted', $__vars['criteria']['album_state'], ), false),
		'label' => 'Deleted',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'selected' => $__templater->func('in_array', array('moderated', $__vars['criteria']['album_state'], ), false),
		'label' => 'Moderated',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	));
	return $__finalCompiled;
}
);