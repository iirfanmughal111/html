<?php
// FROM HASH: 3afdb19095041060b58f62804ce9f135
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Delete resources');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['canSetPublicReason']) {
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
	if ($__templater->isTraversable($__vars['resources'])) {
		foreach ($__vars['resources'] AS $__vars['resource']) {
			$__compilerTemp2 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['resource']['resource_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('Are you sure you want to delete ' . $__templater->escape($__vars['total']) . ' resource(s)?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__vars['canHardDelete'],
	), $__vars) . '

			' . $__compilerTemp1 . '

			' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
	)) . '
	</div>

	' . $__compilerTemp2 . '

	' . $__templater->formHiddenVal('type', 'resource', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'delete', array(
	)) . '
	' . $__templater->formHiddenVal('confirmed', '1', array(
	)) . '

	' . $__templater->func('redirect_input', array($__vars['redirect'], null, true)) . '
', array(
		'action' => $__templater->func('link', array('inline-mod', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);