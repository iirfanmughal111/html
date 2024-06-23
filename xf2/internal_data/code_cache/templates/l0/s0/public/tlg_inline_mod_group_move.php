<?php
// FROM HASH: 00999ce60d3a1ff9f0a84bf8cf8f6b48
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation move groups');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	$__compilerTemp2 = $__templater->method($__vars['nodeTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'disabled' => (($__vars['treeEntry']['record']['category_id'] === $__vars['group']['category_id']) ? 1 : 0),
				'label' => $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['category_title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['groups'])) {
		foreach ($__vars['groups'] AS $__vars['group']) {
			$__compilerTemp3 .= '
        ' . $__templater->formHiddenVal('ids[]', $__vars['group']['group_id'], array(
			)) . '
    ';
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formSelectRow(array(
		'name' => 'category_id',
		'value' => $__vars['group']['category_id'],
	), $__compilerTemp1, array(
		'label' => 'Destination Category',
	)) . '
        </div>
        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>

    ' . $__compilerTemp3 . '

    ' . $__templater->formHiddenVal('type', 'tl_group', array(
	)) . '
    ' . $__templater->formHiddenVal('action', 'move', array(
	)) . '
    ' . $__templater->formHiddenVal('confirmed', '1', array(
	)) . '

    ' . $__templater->func('redirect_input', array($__vars['redirect'], null, true)) . '
', array(
		'action' => $__templater->func('link', array('inline-mod', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);