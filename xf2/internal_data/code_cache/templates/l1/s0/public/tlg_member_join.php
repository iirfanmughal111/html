<?php
// FROM HASH: 00f3b4c43ed22d5c523cd7f6a6d5bde6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Join group' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['group']['name']));
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
        </div>

        ' . $__templater->formHiddenVal('group_id', $__vars['group']['group_id'], array(
	)) . '
        ' . $__templater->formHiddenVal('redirect', $__vars['redirect'], array(
	)) . '

        ' . $__templater->formSubmitRow(array(
		'submit' => 'Join group',
	), array(
	)) . '
    </div>
', array(
		'action' => $__templater->func('link', array('groups/member/join', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);