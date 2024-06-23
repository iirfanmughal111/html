<?php
// FROM HASH: 3fd07da48d39eda15c65df45b49981f2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation - Move media items');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['canSendAlert']) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(), $__vars) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['mediaItems'])) {
		foreach ($__vars['mediaItems'] AS $__vars['mediaItem']) {
			$__compilerTemp2 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['mediaItem']['media_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('Are you sure you want to move ' . $__templater->filter($__vars['total'], array(array('number', array()),), true) . ' media item(s)?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->callMacro('xfmg_media_move_chooser', 'move_chooser', array(
		'categoryTree' => $__vars['categoryTree'],
	), $__vars) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'move',
	), array(
	)) . '
	</div>

	' . $__compilerTemp2 . '

	' . $__templater->formHiddenVal('type', 'xfmg_media', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'move', array(
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