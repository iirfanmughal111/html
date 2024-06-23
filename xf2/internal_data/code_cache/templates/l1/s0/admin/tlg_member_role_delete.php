<?php
// FROM HASH: 14c37df3591afe7c76ab3ddb7a6ea513
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Delete Confirmation');
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->formInfoRow('
                ' . 'Please confirm that you want to delete the following member role' . $__vars['xf']['language']['label_separator'] . '
                <strong><a href="' . $__templater->func('link', array('group-member-roles/edit', $__vars['memberRole'], ), true) . '">' . $__templater->escape($__vars['memberRole']['title']) . '</a></strong>
            ', array(
		'rowtype' => 'confirm',
	)) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
    </div>

    ' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('group-member-roles/delete', $__vars['memberRole'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);