<?php
// FROM HASH: 9fc935d9a82ffa63e46e24f44954c570
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Notifications');
	$__finalCompiled .= '

';
	if ($__vars['needWrapper']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = $__templater->preEscaped('members');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'alert',
		'selected' => (($__vars['member']['alert'] == 'all') OR (($__vars['member']['alert'] == 'alert') ? true : false)),
		'label' => 'Alerts',
		'_type' => 'option',
	),
	array(
		'name' => 'email',
		'selected' => (($__vars['member']['alert'] == 'all') OR (($__vars['member']['alert'] == 'email') ? true : false)),
		'label' => 'Emails',
		'_type' => 'option',
	)), array(
		'label' => 'Receive group notifications via',
		'explain' => 'By checked alert options, you will receive a notice when new contents (event, thread,...) published to this group.',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('group-members/notify', $__vars['member'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);