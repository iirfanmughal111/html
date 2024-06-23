<?php
// FROM HASH: 0a067d9fb1c9690e478384228817f1e1
return array(
'macros' => array('group_privacy_rows' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'category' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ' . $__templater->callMacro('tlg_group_macros', 'privacy_row', array(
		'selected' => $__vars['group']['privacy'],
		'group' => $__vars['group'],
		'allowSecret' => $__templater->method($__vars['category'], 'canAddSecretGroup', array()),
		'allowClosed' => $__templater->method($__vars['category'], 'canAddGroupType', array('closed', )),
	), $__vars) . '

    ' . $__templater->callMacro('tlg_group_macros', 'extra_privacy_rows', array(
		'group' => $__vars['group'],
	), $__vars) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Update privacy');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('about');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->callMacro(null, 'group_privacy_rows', array(
		'group' => $__vars['group'],
		'category' => $__vars['group']['Category'],
	), $__vars) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('groups/privacy', $__vars['group'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	)) . '

';
	return $__finalCompiled;
}
);