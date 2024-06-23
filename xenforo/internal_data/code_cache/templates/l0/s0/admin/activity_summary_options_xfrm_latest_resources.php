<?php
// FROM HASH: 79fe55b1d0ade92d1dbc891fb6960b64
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
				'value' => $__vars['treeEntry']['record']['resource_category_id'],
				'label' => '
			' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . $__templater->escape($__vars['treeEntry']['record']['title']) . '
		',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[resource_category_ids][]',
		'value' => ($__vars['options']['resource_category_ids'] ?: 0),
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
		'value' => 'resource_date',
		'label' => 'Created since last email',
		'_type' => 'option',
	)), array(
		'label' => 'Include resources',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'label' => 'Reaction score of at least X',
		'selected' => $__vars['options']['min_reaction_score'] !== null,
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'options[min_reaction_score]',
		'value' => ($__vars['options']['min_reaction_score'] ?: 0),
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Only include resources with' . '...',
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
	));
	return $__finalCompiled;
}
);