<?php
// FROM HASH: 71ae4b46f723e4e865d39e45cc8c366a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['download']['Product'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Releases'), $__templater->func('link', array('dbtech-ecommerce/releases', $__vars['download']['Product'], ), false), array(
	));
	$__finalCompiled .= '

' . $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('dbtech_ecommerce_helper_action', 'delete_type', array(
		'content' => $__vars['download'],
		'stateKey' => 'download_state',
		'canHardDelete' => $__templater->method($__vars['download'], 'canDelete', array('hard', )),
	), $__vars) . '

			' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>

	' . $__templater->func('redirect_input', array(null, null, true)) . '

', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/release/delete', $__vars['download'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);