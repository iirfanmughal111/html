<?php
// FROM HASH: 265dbebe132a58fd477bd1b3acd66013
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['title'])), $__vars['editLink'], array(
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['includeAuthorAlert']) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('public:helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('public:dbtech_ecommerce_helper_action', 'delete_type', array(
		'content' => $__vars['entity'],
		'stateKey' => $__vars['stateKey'],
		'canHardDelete' => $__vars['canHardDelete'],
	), $__vars) . '
			
			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__vars['deleteLink'],
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);