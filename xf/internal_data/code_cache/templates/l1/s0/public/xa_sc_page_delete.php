<?php
// FROM HASH: 2e25b5a4dbbdb9edd1a36befc9856084
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Delete page');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['page'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['page'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__vars['from_page_management']) {
		$__compilerTemp2 .= '
				' . $__templater->formHiddenVal('mp', true, array(
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__templater->method($__vars['page'], 'canDelete', array('hard', )),
	), $__vars) . '

			' . $__compilerTemp1 . '
			
			' . $__compilerTemp2 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
	' . $__templater->func('redirect_input', array(null, null, true)) . '
', array(
		'action' => $__templater->func('link', array('showcase/page/delete', $__vars['page'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);