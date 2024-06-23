<?php
// FROM HASH: a419e8786c4c086b823b29e38b4df689
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
		'value' => '',
		'label' => 'All categories',
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'label' => $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[category_ids][]',
		'size' => '7',
		'multiple' => 'multiple',
		'value' => $__vars['options']['category_ids'],
	), $__compilerTemp1, array(
		'label' => 'Display from categories',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[include_personal_albums]',
		'selected' => $__vars['options']['include_personal_albums'],
		'label' => 'Display personal albums',
		'_type' => 'option',
	)), array(
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'label' => 'At least X comments',
		'selected' => $__vars['options']['min_comments'] !== null,
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'options[min_comments]',
		'value' => ($__vars['options']['min_comments'] ?: 0),
		'min' => '0',
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
		'label' => 'Only include albums with' . '...',
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