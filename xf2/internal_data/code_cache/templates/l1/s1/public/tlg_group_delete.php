<?php
// FROM HASH: 2ca4fbfbe0500f00bc3bb8624fedec63
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm deletion');
	$__finalCompiled .= '

';
	if ($__vars['breadcrumbs']) {
		$__finalCompiled .= '
    ';
		$__templater->breadcrumbs($__vars['breadcrumbs']);
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__templater->method($__vars['entity'], 'canDelete', array('hard', )),
	), $__vars) . '
        </div>

        ' . $__templater->formSubmitRow(array(
		'submit' => 'Delete',
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
    </div>
', array(
		'action' => $__vars['confirmUrl'],
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);