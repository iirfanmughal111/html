<?php
// FROM HASH: 2a1296cb3b6fa1d44adb5edbd8691085
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit image - Preview');
	$__finalCompiled .= '

';
	$__templater->includeCss('xfmg_media_edit_image.less');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['mediaItem'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				<img src="' . $__templater->escape($__vars['previewUri']) . '" />
			', array(
		'rowclass' => 'editImagePreview',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>

	' . $__templater->formHiddenVal('crop_data', $__templater->filter($__vars['cropData'], array(array('json', array()),), false), array(
		'class' => 'js-cropData',
	)) . '
', array(
		'action' => $__templater->func('link', array('media/edit-image', $__vars['mediaItem'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);