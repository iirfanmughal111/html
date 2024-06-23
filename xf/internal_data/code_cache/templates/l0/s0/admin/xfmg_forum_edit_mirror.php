<?php
// FROM HASH: e337a9c4c3a182966cd2f936a2119bcc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['xfmgCategoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'disabled' => ($__vars['treeEntry']['record']['category_type'] != 'media'),
				'label' => '
			' . $__templater->filter($__templater->func('repeat', array('&nbsp;&nbsp;', $__vars['treeEntry']['depth'], ), false), array(array('raw', array()),), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']) . '
		',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'xfmg_media_mirror_category_id',
		'value' => $__vars['forum']['xfmg_media_mirror_category_id'],
	), $__compilerTemp1, array(
		'label' => 'Mirror attachments to media category',
		'explain' => '
		' . 'Media items will be automatically created in the selected category from attachments posted in this forum. Media will be accessible to anyone that can view this category, regardless of whether they can view the original attachment.' . '
	',
	));
	return $__finalCompiled;
}
);