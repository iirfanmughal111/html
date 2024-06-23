<?php
// FROM HASH: be86457da40d52c696ca8c3d83aa514a
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

' . $__templater->formRadioRow(array(
		'name' => 'options[style]',
		'value' => ($__vars['options']['style'] ?: 'simple'),
	), array(array(
		'value' => 'simple',
		'label' => 'Simple',
		'hint' => 'A simple view, designed for narrow spaces such as sidebars.',
		'_type' => 'option',
	),
	array(
		'value' => 'full',
		'label' => 'Full',
		'hint' => 'A full size view, displaying as a standard product list (using the style preference for grid view vs list view).',
		'_type' => 'option',
	),
	array(
		'value' => 'full-grid',
		'label' => 'Full' . ' ' . $__vars['xf']['language']['parenthesis_open'] . 'Grid' . $__vars['xf']['language']['parenthesis_close'],
		'hint' => 'A full size view, displaying as a standard product list (using grid view).',
		'_type' => 'option',
	),
	array(
		'value' => 'full-list',
		'label' => 'Full' . ' ' . $__vars['xf']['language']['parenthesis_open'] . 'List' . $__vars['xf']['language']['parenthesis_close'],
		'hint' => 'A full size view, displaying as a standard product list (using list view).',
		'_type' => 'option',
	)), array(
		'label' => 'Display style',
	)) . '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => 'All categories or contextual category',
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
		'name' => 'options[product_category_ids][]',
		'value' => ($__vars['options']['product_category_ids'] ?: 0),
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp1, array(
		'label' => 'Category limit',
		'explain' => 'If no categories are explicitly selected, this widget will pull from all categories unless used within a product category. In this case, the products will be limited to that category and descendents.',
	));
	return $__finalCompiled;
}
);