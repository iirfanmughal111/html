<?php
// FROM HASH: 1464453e03cc9d07c230984c4eafd3ef
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Delete resource');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['resource'], 'canSetPublicDeleteReason', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'public_delete_reason',
		), array(
			'label' => 'Public deletion reason',
			'explain' => 'Any reason provided here will be included in the message that is automatically posted in the resource discussion thread explaining that the resource is no longer available.',
		)) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['resource'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp2 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__templater->method($__vars['resource'], 'canDelete', array('hard', )),
	), $__vars) . '

			' . $__compilerTemp1 . '

			' . $__compilerTemp2 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('resources/delete', $__vars['resource'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);