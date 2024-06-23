<?php
// FROM HASH: 18f4fdc3db2964f27f2344b0e5e11a01
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Leave resource team');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to leave the resource team?' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'submit' => 'Leave',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('resources/leave-team', $__vars['resource'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);