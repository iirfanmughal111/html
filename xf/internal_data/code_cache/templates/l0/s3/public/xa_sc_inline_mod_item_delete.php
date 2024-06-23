<?php
// FROM HASH: 80ad588a712366df379bbe59d9780987
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Delete items');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['items'])) {
		foreach ($__vars['items'] AS $__vars['item']) {
			$__compilerTemp1 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['item']['item_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('Are you sure you want to delete ' . $__templater->escape($__vars['total']) . ' item(s)?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__vars['canHardDelete'],
	), $__vars) . '

			' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
	)) . '
	</div>

	' . $__compilerTemp1 . '

	' . $__templater->formHiddenVal('type', 'sc_item', array(
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