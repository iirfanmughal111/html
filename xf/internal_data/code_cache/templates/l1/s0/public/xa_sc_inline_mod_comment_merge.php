<?php
// FROM HASH: eba00154540a0b7d3f9a2c85add093af
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation - Merge comments');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['comments'])) {
		foreach ($__vars['comments'] AS $__vars['commentId'] => $__vars['comment']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['commentId'],
				'label' => ($__templater->escape($__vars['comment']['User']['username']) ?: $__templater->escape($__vars['comment']['username'])) . ' &middot; ' . $__templater->func('date_time', array($__vars['comment']['comment_date'], ), true),
				'_type' => 'option',
			);
		}
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
			' . $__templater->formInfoRow('Are you sure you want to merge ' . $__templater->escape($__vars['total']) . ' comments together?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'target_comment_id',
		'value' => $__vars['first']['comment_id'],
	), $__compilerTemp1, array(
		'label' => 'Merge into comment',
	)) . '

			' . $__templater->formTextAreaRow(array(
		'name' => 'message',
		'value' => $__vars['message'],
		'rows' => '5',
		'autosize' => 'true',
		'maxlength' => $__vars['xf']['options']['messageMaxLength'],
	), array(
		'label' => 'Preview',
	)) . '

			' . $__templater->callMacro('helper_action', 'author_alert', array(
		'selected' => ($__vars['total'] == 1),
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'merge',
	), array(
	)) . '
	</div>

	' . $__compilerTemp2 . '

	' . $__templater->formHiddenVal('type', 'sc_comment', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'merge', array(
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