<?php
// FROM HASH: 97b7f3abd4678d560cf7b470f74b1b30
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '1',
	), array(
		'label' => 'Maximum entries',
	)) . '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => 'All categories',
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'label' => '
			' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . $__templater->escape($__vars['treeEntry']['record']['title']) . '
		',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[category_ids][]',
		'value' => ($__vars['options']['category_ids'] ?: 0),
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp1, array(
		'label' => 'Category limit',
	)) . '

' . $__templater->formRadioRow(array(
		'name' => 'options[condition]',
		'value' => $__vars['options']['condition'],
	), array(array(
		'value' => 'last_update',
		'label' => 'Updated since last email',
		'_type' => 'option',
	),
	array(
		'value' => 'create_date',
		'label' => 'Created since last email',
		'_type' => 'option',
	)), array(
		'label' => 'Include items',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[has_cover_image]',
		'selected' => $__vars['options']['has_cover_image'],
		'label' => 'A cover image or content image',
		'_type' => 'option',
	),
	array(
		'label' => 'At least X comments',
		'selected' => $__vars['options']['min_comments'] !== null,
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'options[min_comments]',
		'value' => ($__vars['options']['min_comments'] ?: 0),
	))),
		'_type' => 'option',
	),
	array(
		'label' => 'Reaction score of at least X',
		'selected' => $__vars['options']['min_reaction_score'] !== null,
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'options[min_reaction_score]',
		'value' => ($__vars['options']['min_reaction_score'] ?: 0),
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Only include items with' . '...',
		'rowtype' => 'noColon',
	)) . '

';
	$__compilerTemp3 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->formRow('

	<div class="inputPair">
		' . $__templater->formSelect(array(
		'name' => 'options[order]',
		'value' => $__vars['options']['order'],
	), $__compilerTemp3) . '
		' . $__templater->formSelect(array(
		'name' => 'options[direction]',
		'value' => $__vars['options']['direction'],
	), array(array(
		'value' => 'ASC',
		'label' => 'Ascending',
		'_type' => 'option',
	),
	array(
		'value' => 'DESC',
		'label' => 'Descending',
		'_type' => 'option',
	))) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Sort',
	)) . '

<hr class="formRowSep" />

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[display_header]',
		'selected' => $__vars['options']['display_header'],
		'label' => 'Display header',
		'_type' => 'option',
	),
	array(
		'name' => 'options[display_attribution]',
		'selected' => $__vars['options']['display_attribution'],
		'label' => 'Display attribution',
		'_type' => 'option',
	),
	array(
		'name' => 'options[display_description]',
		'selected' => $__vars['options']['display_description'],
		'label' => 'Display description',
		'_type' => 'option',
	),
	array(
		'name' => 'options[display_footer]',
		'selected' => $__vars['options']['display_footer'],
		'label' => 'Display footer',
		'_type' => 'option',
	),
	array(
		'name' => 'options[display_footer_opposite]',
		'selected' => $__vars['options']['display_footer_opposite'],
		'label' => 'Display footer opposite',
		'_type' => 'option',
	)), array(
		'label' => 'Email display options' . '...',
		'rowtype' => 'noColon',
	)) . '

' . $__templater->formRadioRow(array(
		'name' => 'options[snippet_type]',
		'value' => $__vars['options']['snippet_type'],
	), array(array(
		'value' => 'rich_text',
		'label' => 'Rich text snippet',
		'_type' => 'option',
	),
	array(
		'value' => 'plain_text',
		'label' => 'Plain text snippet',
		'_type' => 'option',
	)), array(
		'label' => 'Email snippet type',
	));
	return $__finalCompiled;
}
);