<?php
// FROM HASH: 13cecf602624e671ef7099c1ea2beac9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['contentUrl']) {
		$__compilerTemp1 .= '
					<strong><a href="' . $__templater->escape($__vars['contentUrl']) . '">' . $__templater->escape($__vars['optiontitle']) . '</a></strong>
				';
	} else {
		$__compilerTemp1 .= '
					<strong>' . $__templater->escape($__vars['optiontitle']) . '</strong>
					<input name=\'thread_id\' value="' . $__templater->escape($__vars['thread_id']) . '" type="hidden">
					<input name=\'id\' value="' . $__templater->escape($__vars['id']) . '" type="hidden">
					
				';
	}
	$__compilerTemp2 = '';
	if ($__vars['deletionImportantPhrase']) {
		$__compilerTemp2 .= '
					<div class="blockMessage blockMessage--important blockMessage--iconic">
						' . $__templater->func('phrase_dynamic', array($__vars['deletionImportantPhrase'], ), true) . '
					</div>
				';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure to delete this dropdown Option' . '
				' . $__compilerTemp1 . '
				' . $__compilerTemp2 . '
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('opt-reply/deletesingle', $__vars['thread_id'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);