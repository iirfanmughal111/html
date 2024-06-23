<?php
// FROM HASH: 12bcbb73aef343e1931d162e505a7543
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation - Move albums');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'value' => '0',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'No category' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['categoryTree'], 'getFlattened', array(0, ));
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['category_id'],
				'disabled' => (($__vars['treeEntry']['record']['category_type'] != 'album') ? 'disabled' : ''),
				'label' => $__templater->func('repeat_raw', array('&nbsp; ', $__vars['treeEntry']['depth'], ), true) . ' ' . $__templater->escape($__vars['treeEntry']['record']['title']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp3 = '';
	if ($__vars['canSendAlert']) {
		$__compilerTemp3 .= '
				' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'notify_watchers',
			'value' => '1',
			'selected' => ($__vars['total'] == 1),
			'label' => 'Notify members watching the destination category',
			'_type' => 'option',
		)), array(
		)) . '

				' . $__templater->callMacro('helper_action', 'author_alert', array(
			'selected' => ($__vars['total'] == 1),
		), $__vars) . '
			';
	}
	$__compilerTemp4 = '';
	if ($__templater->isTraversable($__vars['albums'])) {
		foreach ($__vars['albums'] AS $__vars['album']) {
			$__compilerTemp4 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['album']['album_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('Are you sure you want to move ' . $__templater->escape($__vars['total']) . ' album(s)?', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'target_category_id',
		'value' => $__vars['first']['category_id'],
	), $__compilerTemp1, array(
		'label' => 'Destination category',
		'explain' => 'Must be an album category or select \'No category\' to make the album(s) uncategorised (personal albums).',
	)) . '

			' . $__compilerTemp3 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'move',
	), array(
	)) . '
	</div>

	' . $__compilerTemp4 . '

	' . $__templater->formHiddenVal('type', 'xfmg_album', array(
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