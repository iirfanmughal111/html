<?php
// FROM HASH: 44a6ead1a593a4dc0b42209510741f9b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['trophies'] = $__templater->method($__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('XF:Trophy', )), 'findTrophiesForList', array()), 'fetch', array());
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['trophies'])) {
		foreach ($__vars['trophies'] AS $__vars['trophy']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['trophy']['trophy_id'],
				'label' => $__templater->escape($__vars['trophy']['title']) . ' (' . 'Points' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['trophy']['trophy_points']) . ')',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'settings[trophy][]',
		'multiple' => 'true',
		'size' => '8',
		'value' => ($__vars['event']['settings']['trophy'] ? $__vars['event']['settings']['trophy'] : array()),
	), $__compilerTemp1, array(
		'label' => 'Trophy',
	));
	return $__finalCompiled;
}
);