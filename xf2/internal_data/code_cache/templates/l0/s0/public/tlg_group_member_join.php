<?php
// FROM HASH: 0fbcf77785e4827a258077e357213018
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['isInline']) {
		$__finalCompiled .= '
    ';
		$__compilerTemp1 = $__vars;
		$__compilerTemp1['pageSelected'] = $__templater->preEscaped('about');
		$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Join group');
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formInfoRow('
                ' . 'Are you sure you want to join the following group?' . '
                <a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '"><strong>' . $__templater->escape($__vars['group']['name']) . '</strong></a>
            ', array(
		'rowtype' => 'confirm',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'submit' => ($__vars['group']['always_moderate_join'] ? 'Request to join' : 'Join group'),
	), array(
		'rowtype' => 'simple',
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('groups/join', $__vars['group'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);