<?php
// FROM HASH: af010f15847c59375d018d428cfd5366
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Batch update groups');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__vars['groupIds']) {
		$__compilerTemp1 .= '
                    <span role="presentation" aria-hidden="true">&middot;</span>
                    <a href="' . $__templater->func('link', array($__vars['linkPrefix'] . '/list', null, array('criteria' => $__vars['criteria'], 'all' => true, ), ), true) . '">' . 'View or filter matches' . '</a>
                ';
	}
	$__compilerTemp2 = array(array(
		'value' => '0',
		'_type' => 'option',
	));
	if ($__templater->isTraversable($__vars['categories'])) {
		foreach ($__vars['categories'] AS $__vars['category']) {
			$__compilerTemp2[] = array(
				'value' => $__vars['category']['value'],
				'label' => $__templater->escape($__vars['category']['label']),
				'disabled' => $__vars['category']['disabled'],
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = '';
	if ($__vars['groupIds']) {
		$__compilerTemp3 .= '
        ' . $__templater->formHiddenVal('thread_ids', $__templater->filter($__vars['groupIds'], array(array('json', array()),), false), array(
		)) . '
    ';
	} else {
		$__compilerTemp3 .= '
        ' . $__templater->formHiddenVal('criteria', $__templater->filter($__vars['criteria'], array(array('json', array()),), false), array(
		)) . '
    ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <h2 class="block-header">' . 'Update groups' . '</h2>
        <div class="block-body">
            ' . $__templater->formRow('
                ' . $__templater->filter($__vars['total'], array(array('number', array()),), true) . '
                ' . $__compilerTemp1 . '
            ', array(
		'label' => 'Matched groups',
	)) . '

            <hr class="formRowSep" />

            ' . $__templater->formSelectRow(array(
		'name' => 'actions[category_id]',
	), $__compilerTemp2, array(
		'label' => 'Move to category',
	)) . '

            ' . $__templater->formSelectRow(array(
		'name' => 'actions[privacy]',
	), array(array(
		'value' => 'public',
		'label' => 'Public Group',
		'_type' => 'option',
	),
	array(
		'value' => 'closed',
		'label' => 'Closed Group',
		'_type' => 'option',
	),
	array(
		'value' => 'secret',
		'label' => 'Secret Group',
		'_type' => 'option',
	)), array(
		'label' => 'Change group privacy',
	)) . '

            <hr class="formRowSep" />

            ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'actions[approve]',
		'value' => '1',
		'label' => 'Approve groups',
		'_type' => 'option',
	),
	array(
		'name' => 'actions[unapprove]',
		'value' => '1',
		'label' => 'Unapprove groups',
		'_type' => 'option',
	),
	array(
		'name' => 'actions[soft_delete]',
		'value' => '1',
		'label' => 'Soft delete groups',
		'_type' => 'option',
	)), array(
	)) . '

        </div>
        ' . $__templater->formSubmitRow(array(
		'submit' => 'Update groups',
		'icon' => 'save',
	), array(
	)) . '
    </div>

    ' . $__compilerTemp3 . '
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/batch-update/action', ), false),
		'class' => 'block',
	)) . '

';
	$__compilerTemp4 = '';
	if ($__vars['groupIds']) {
		$__compilerTemp4 .= '
        ' . $__templater->formHiddenVal('group_ids', $__templater->filter($__vars['groupIds'], array(array('json', array()),), false), array(
		)) . '
    ';
	} else {
		$__compilerTemp4 .= '
        ' . $__templater->formHiddenVal('criteria', $__templater->filter($__vars['criteria'], array(array('json', array()),), false), array(
		)) . '
    ';
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <h2 class="block-header">' . 'Delete groups' . '</h2>
        <div class="block-body">
            ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'actions[delete]',
		'label' => '
                    ' . 'Confirm deletion of ' . $__templater->filter($__vars['total'], array(array('number', array()),), true) . ' groups' . '
                ',
		'_type' => 'option',
	)), array(
	)) . '
        </div>
        ' . $__templater->formSubmitRow(array(
		'name' => 'confirm_delete',
		'icon' => 'delete',
	), array(
	)) . '
    </div>

    ' . $__compilerTemp4 . '
', array(
		'action' => $__templater->func('link', array($__vars['linkPrefix'] . '/batch-update/action', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);