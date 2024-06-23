<?php
// FROM HASH: 2e52b3ee4d8d1efb598568213d63a71e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Ban members');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['members'])) {
		foreach ($__vars['members'] AS $__vars['member']) {
			$__compilerTemp1 .= '
        ' . $__templater->formHiddenVal('ids[]', $__vars['member']['member_id'], array(
			)) . '
    ';
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formInfoRow('Are you sure you want to ban ' . $__templater->escape($__vars['total']) . ' members?', array(
		'rowtype' => 'confirm',
	)) . '

            ' . $__templater->formRadioRow(array(
		'name' => 'type',
	), array(array(
		'value' => '0',
		'label' => 'No limitation',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Expires after' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formDateInput(array(
		'name' => 'end_date',
		'value' => '',
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Time period',
	)) . '
        </div>
        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>

    ' . $__compilerTemp1 . '

    ' . $__templater->formHiddenVal('type', 'tl_group_member', array(
	)) . '
    ' . $__templater->formHiddenVal('action', 'ban', array(
	)) . '
    ' . $__templater->formHiddenVal('confirmed', '1', array(
	)) . '
    ' . $__templater->formHiddenVal('group_id', $__vars['group']['group_id'], array(
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