<?php
// FROM HASH: 99ecf72b380590507e2ab35f1138039c
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
		'label' => 'Only include comments with' . '...',
		'rowtype' => 'noColon',
	)) . '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->formRow('

	<div class="inputPair">
		' . $__templater->formSelect(array(
		'name' => 'options[order]',
		'value' => $__vars['options']['order'],
	), $__compilerTemp1) . '
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