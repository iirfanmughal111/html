<?php
// FROM HASH: 18124b821230415c1175194810840978
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation - Move albums');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['canSendAlert']) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_action', 'author_alert', array(
			'selected' => ($__vars['total'] == 1),
		), $__vars) . '
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['albums'])) {
		foreach ($__vars['albums'] AS $__vars['album']) {
			$__compilerTemp2 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['album']['album_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure you want to change the privacy of ' . $__templater->escape($__vars['total']) . ' album(s)?' . '
			', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->formInfoRow('
				<div class="blockMessage blockMessage--important"><strong>' . 'Note' . $__vars['xf']['language']['label_separator'] . '</strong> ' . 'Changing the privacy settings in this way will overwrite all existing privacy settings in the selected albums.' . '</div>
			', array(
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'change_view',
		'label' => 'Change who can view media items:',
		'_dependent' => array('
						' . $__templater->callMacro('xfmg_album_edit', 'change_privacy_view', array(
		'album' => $__templater->filter($__vars['albums'], array(array('first', array()),), false),
		'viewUsers' => $__vars['viewUsers'],
		'row' => false,
	), $__vars) . '
					'),
		'_type' => 'option',
	)), array(
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'change_add',
		'label' => 'Change who can add media items:',
		'_dependent' => array('
						' . $__templater->callMacro('xfmg_album_edit', 'change_privacy_add', array(
		'album' => $__templater->filter($__vars['albums'], array(array('first', array()),), false),
		'addUsers' => $__vars['addUsers'],
		'row' => false,
	), $__vars) . '
					'),
		'_type' => 'option',
	)), array(
	)) . '

			' . $__compilerTemp1 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'confirm',
	), array(
	)) . '
	</div>

	' . $__compilerTemp2 . '

	' . $__templater->formHiddenVal('type', 'xfmg_album', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'change_privacy', array(
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