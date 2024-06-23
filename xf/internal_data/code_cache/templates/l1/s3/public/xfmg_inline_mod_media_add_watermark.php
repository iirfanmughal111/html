<?php
// FROM HASH: d90d03b99c1455cec76aaf9ec01a2e4a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Inline moderation - Watermark media items');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['mediaItems'])) {
		foreach ($__vars['mediaItems'] AS $__vars['mediaItem']) {
			$__compilerTemp1 .= '
		' . $__templater->formHiddenVal('ids[]', $__vars['mediaItem']['media_id'], array(
			)) . '
	';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('Are you sure you want to add a watermark to ' . $__templater->escape($__vars['total']) . ' media item(s)?', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Watermark',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>

	' . $__compilerTemp1 . '

	' . $__templater->formHiddenVal('type', 'xfmg_media', array(
	)) . '
	' . $__templater->formHiddenVal('action', 'add_watermark', array(
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