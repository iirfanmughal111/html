<?php
// FROM HASH: 795178aab3249cdd9888e02ffd954e95
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Delete comment');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['content'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['comment'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__templater->method($__vars['comment'], 'canDelete', array('hard', )),
	), $__vars) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('showcase/comments/delete', $__vars['comment'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);