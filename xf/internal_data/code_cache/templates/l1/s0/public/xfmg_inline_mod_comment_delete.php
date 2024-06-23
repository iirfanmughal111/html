<?php
// FROM HASH: 981d07b3d95a4629bb6ceb4ce1f47ab9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation - Delete comments');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['canSendAlert']) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['comments'])) {
		foreach ($__vars['comments'] AS $__vars['comment']) {
			$__compilerTemp2 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['comment']['comment_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('Are you sure you want to delete ' . $__templater->escape($__vars['total']) . ' comment(s)?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->callMacro('helper_action', 'delete_type', array(
		'canHardDelete' => $__vars['canHardDelete'],
	), $__vars) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
	)) . '
	</div>

	' . $__compilerTemp2 . '

	' . $__templater->formHiddenVal('type', 'xfmg_comment', array(
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