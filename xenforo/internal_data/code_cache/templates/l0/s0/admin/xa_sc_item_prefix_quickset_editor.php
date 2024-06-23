<?php
// FROM HASH: 9c7850d5ec613641122840100b8cd95d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['extraOptions'] = $__templater->preEscaped('
		' . $__templater->formCheckBoxRow(array(
		'name' => 'apply_sc_item_category_ids',
	), array(array(
		'label' => 'Apply category options',
		'_dependent' => array('
					' . $__templater->callMacro('xa_sc_item_prefix_edit_macros', 'category_ids', array(
		'categoryIds' => $__vars['prefix']['category_ids'],
		'categoryTree' => $__vars['categoryTree'],
		'withRow' => false,
	), $__vars) . '
				'),
		'_type' => 'option',
	)), array(
		'label' => 'Applicable categories',
	)) . '
	');
	$__finalCompiled .= $__templater->includeTemplate('base_prefix_quickset_editor', $__compilerTemp1);
	return $__finalCompiled;
}
);