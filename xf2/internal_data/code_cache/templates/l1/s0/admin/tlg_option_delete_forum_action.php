<?php
// FROM HASH: 4b234b8a1919cff36f6a3a6b69ad3dd5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => '&nbsp;',
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['node_id'],
				'disabled' => ($__vars['treeEntry']['record']['node_type_id'] != 'Forum'),
				'label' => '
                    ' . $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']) . '
                ',
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['formatParams']);
	$__finalCompiled .= $__templater->formRadioRow(array(
		'name' => $__vars['inputName'] . '[type]',
		'value' => $__vars['option']['option_value']['type'],
	), array(array(
		'value' => '0',
		'label' => 'Hard delete forum and forum data itself.',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Unassociate with group and archive forum to' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[node_id]',
		'value' => $__vars['option']['option_value']['node_id'],
	), $__compilerTemp1)),
		'_type' => 'option',
	)), array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);