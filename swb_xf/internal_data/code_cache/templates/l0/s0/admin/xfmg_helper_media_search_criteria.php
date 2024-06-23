<?php
// FROM HASH: c364e3ad6811fee748bd3ddd55f6bbae
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


';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['categories'])) {
		foreach ($__vars['categories'] AS $__vars['category']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['category']['value'],
				'disabled' => $__vars['category']['disabled'],
				'label' => $__templater->escape($__vars['category']['label']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'criteria[category_id]',
		'value' => $__vars['criteria']['category_id'],
		'multiple' => 'true',
	), $__compilerTemp1, array(
		'label' => 'Specific category',
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
		'name' => 'criteria[media_date][start]',
		'value' => $__vars['criteria']['media_date']['start'],
		'size' => '15',
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formDateInput(array(
		'name' => 'criteria[media_date][end]',
		'value' => $__vars['criteria']['media_date']['end'],
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
		'name' => 'criteria[media_state]',
	), array(array(
		'value' => 'visible',
		'selected' => $__templater->func('in_array', array('visible', $__vars['criteria']['media_state'], ), false),
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted',
		'selected' => $__templater->func('in_array', array('deleted', $__vars['criteria']['media_state'], ), false),
		'label' => 'Deleted',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'selected' => $__templater->func('in_array', array('moderated', $__vars['criteria']['media_state'], ), false),
		'label' => 'Moderated',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	)) . '

' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[watermarked]',
	), array(array(
		'value' => '0',
		'selected' => $__templater->func('in_array', array(0, $__vars['criteria']['watermarked'], ), false),
		'label' => 'Not watermarked',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'selected' => $__templater->func('in_array', array(1, $__vars['criteria']['watermarked'], ), false),
		'label' => 'Watermarked',
		'_type' => 'option',
	)), array(
		'label' => 'Watermark state',
	)) . '

' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[media_type]',
	), array(array(
		'value' => 'image',
		'selected' => $__templater->func('in_array', array('image', $__vars['criteria']['media_type'], ), false),
		'label' => 'Images',
		'_type' => 'option',
	),
	array(
		'value' => 'audio',
		'selected' => $__templater->func('in_array', array('audio', $__vars['criteria']['media_type'], ), false),
		'label' => 'Audio',
		'_type' => 'option',
	),
	array(
		'value' => 'video',
		'selected' => $__templater->func('in_array', array('video', $__vars['criteria']['media_type'], ), false),
		'label' => 'Videos',
		'_type' => 'option',
	),
	array(
		'value' => 'embed',
		'selected' => $__templater->func('in_array', array('embed', $__vars['criteria']['media_type'], ), false),
		'label' => 'Embeds',
		'_type' => 'option',
	)), array(
		'label' => 'Media type',
	));
	return $__finalCompiled;
}
);