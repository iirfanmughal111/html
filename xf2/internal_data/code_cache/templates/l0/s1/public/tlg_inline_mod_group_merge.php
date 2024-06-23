<?php
// FROM HASH: aa4523bc26dc63561cb1197821db6e9f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation merge groups');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['entities'])) {
		foreach ($__vars['entities'] AS $__vars['entity']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['entity']['group_id'],
				'label' => $__templater->escape($__vars['entity']['name']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['entities'])) {
		foreach ($__vars['entities'] AS $__vars['group']) {
			$__compilerTemp2 .= '
        ' . $__templater->formHiddenVal('ids[]', $__vars['group']['group_id'], array(
			)) . '
    ';
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formInfoRow('Are you sure that you want to merge ' . $__templater->escape($__vars['total']) . ' groups?', array(
		'rowtype' => 'confirm',
	)) . '

            ' . $__templater->formSelectRow(array(
		'name' => 'target_group_id',
		'value' => $__vars['first']['group_id'],
	), $__compilerTemp1, array(
		'label' => 'Destination group',
	)) . '

            ' . $__templater->formRadioRow(array(
		'name' => 'alert_type',
		'value' => 'admin',
	), array(array(
		'value' => '',
		'label' => 'Do not send notifications',
		'_type' => 'option',
	),
	array(
		'value' => 'admin',
		'label' => 'Send notifications to admin and moderator of source group only.',
		'_type' => 'option',
	),
	array(
		'value' => 'all',
		'label' => 'Send notifications to all members of source group.',
		'_type' => 'option',
	)), array(
		'label' => 'Options',
	)) . '
        </div>
        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>

    ' . $__compilerTemp2 . '

    ' . $__templater->formHiddenVal('type', 'tl_group', array(
	)) . '
    ' . $__templater->formHiddenVal('action', 'merge', array(
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