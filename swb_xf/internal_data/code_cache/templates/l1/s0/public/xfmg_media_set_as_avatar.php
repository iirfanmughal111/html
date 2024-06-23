<?php
// FROM HASH: 108b034317e9f387e9032e161c2a8682
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Set as avatar');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['mediaItem'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to set your avatar to the following' . $__vars['xf']['language']['label_separator'] . '
			', array(
		'rowtype' => 'confirm',
	)) . '
			' . $__templater->formInfoRow('
				<div class="avatar avatar--l">
					<img src="' . $__templater->escape($__vars['previewUri']) . '" />
				</div>
			', array(
		'rowtype' => 'confirm',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media/set-as-avatar', $__vars['mediaItem'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);