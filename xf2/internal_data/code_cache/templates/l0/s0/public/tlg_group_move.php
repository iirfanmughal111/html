<?php
// FROM HASH: 0e54cbd64357f7fc9815c577f42de0e8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Move group');
	$__finalCompiled .= '

';
	if ($__vars['hasWrapper']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['selected'] = $__templater->preEscaped('about');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = array();
	$__compilerTemp3 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp3)) {
		foreach ($__compilerTemp3 AS $__vars['treeEntry']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'disabled' => (($__vars['treeEntry']['record']['category_id'] === $__vars['group']['category_id']) ? 1 : 0),
				'label' => $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['category_title']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formSelectRow(array(
		'name' => 'category_id',
		'value' => $__vars['group']['category_id'],
	), $__compilerTemp2, array(
		'label' => 'Destination Category',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('groups/move', $__vars['group'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);