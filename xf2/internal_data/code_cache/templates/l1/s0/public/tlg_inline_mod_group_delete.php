<?php
// FROM HASH: 1f59c0487b5582c2f14600bdfaebfeab
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation delete groups');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['groups'])) {
		foreach ($__vars['groups'] AS $__vars['group']) {
			$__compilerTemp1 .= '
        ' . $__templater->formHiddenVal('ids[]', $__vars['group']['group_id'], array(
			)) . '
    ';
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formInfoRow('Are you want to delete ' . $__templater->escape($__vars['total']) . ' groups?', array(
		'rowtype' => 'confirm',
	)) . '

            ' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__vars['canHardDelete'],
	), $__vars) . '
        </div>
        ' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
	)) . '
    </div>

    ' . $__compilerTemp1 . '

    ' . $__templater->formHiddenVal('type', 'tl_group', array(
	)) . '
    ' . $__templater->formHiddenVal('action', 'delete', array(
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