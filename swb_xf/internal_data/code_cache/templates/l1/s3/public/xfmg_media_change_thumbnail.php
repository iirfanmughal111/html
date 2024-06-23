<?php
// FROM HASH: 7780cec41baa2b4db5c730008a51c71d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Change thumbnail');
	$__finalCompiled .= '

';
	$__templater->includeCss('account_avatar.less');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['mediaItem'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (($__vars['mediaItem']['media_type'] == 'audio') OR ($__vars['mediaItem']['media_type'] == 'video')) {
		$__compilerTemp1 .= '
										<br /><br />
										' . '<strong>Note:</strong> This will also update the poster image for this media item.' . '
									';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<ul class="block-body">
			<li class="block-row block-row--separated avatarControl">
				<div class="avatarControl-preview">
					' . $__templater->func('xfmg_thumbnail', array($__vars['mediaItem'], '', false, 'default', ), true) . '
				</div>
				<div class="avatarControl-inputs">
					' . $__templater->formRadio(array(
		'name' => 'thumbnail_type',
	), array(array(
		'value' => 'default',
		'selected' => !$__vars['mediaItem']['custom_thumbnail_date'],
		'label' => 'Use default thumbnail',
		'hint' => 'If selected, this option will rebuild the existing thumbnail, if available. If a custom thumbnail has been uploaded it will be deleted.',
		'_type' => 'option',
	))) . '
				</div>
			</li>

			<li class="block-row block-row--separated avatarControl">
				<div class="avatarControl-preview">
					' . $__templater->func('xfmg_thumbnail', array($__vars['mediaItem'], 'xfmgThumbnail--upload', false, 'custom', ), true) . '
				</div>
				<div class="avatarControl-inputs">
					' . $__templater->formRadio(array(
		'name' => 'thumbnail_type',
	), array(array(
		'value' => 'custom',
		'selected' => $__vars['mediaItem']['custom_thumbnail_date'],
		'label' => 'Use custom thumbnail',
		'_dependent' => array('
								<label>' . 'Upload new custom thumbnail:' . '</label>
								' . $__templater->formUpload(array(
		'name' => 'upload',
		'accept' => '.gif,.jpeg,.jpg,.jpe,.png',
	)) . '
								<dfn class="inputChoices-explain">
									' . 'It is recommended that you use an image that is at least ' . $__templater->filter($__vars['xf']['options']['xfmgThumbnailDimensions']['width'], array(array('number', array()),), true) . 'x' . $__templater->filter($__vars['xf']['options']['xfmgThumbnailDimensions']['height'], array(array('number', array()),), true) . ' pixels.' . '
									' . $__compilerTemp1 . '
								</dfn>
							'),
		'_type' => 'option',
	))) . '
				</div>
			</li>
		</ul>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('media/change-thumbnail', $__vars['mediaItem'], ), false),
		'upload' => 'true',
		'ajax' => 'true',
		'data-force-flash-message' => 'on',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);