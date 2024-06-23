<?php
// FROM HASH: 7fddeb300b1d491824580347b50c625a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit image');
	$__finalCompiled .= '

';
	$__templater->includeCss('xfmg_media_edit_image.less');
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'prod' => 'xfmg/image_editor-compiled.js',
		'dev' => 'xfmg/vendor/cropper/cropper.js, xfmg/image_editor.js',
	));
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['mediaItem'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				' . $__templater->formInfoRow('
					<div class="media">
						<div class="media-container">
							<img src="' . $__templater->func('link', array('media/original', $__vars['mediaItem'], array('d' => $__vars['mediaItem']['last_edit_date'], ), ), true) . '" class="js-mediaImg" />
						</div>
					</div>
				', array(
		'rowtype' => 'noPadding',
	)) . '
				' . $__templater->formRow('
					<div class="buttonGroup">
						' . $__templater->button('', array(
		'class' => 'button--link js-ctrlDragMove',
		'icon' => 'move',
		'data-xf-init' => 'tooltip',
		'title' => 'Canvas drag mode: Move',
	), '', array(
	)) . '
						' . $__templater->button('', array(
		'class' => 'button--link button--iconOnly js-ctrlDragCrop',
		'icon' => 'crop',
		'data-xf-init' => 'tooltip',
		'title' => 'Canvas drag mode: Crop',
	), '', array(
	)) . '
					</div>
					<div class="buttonGroup">
						' . $__templater->button('', array(
		'class' => 'button--link button--iconOnly js-ctrlZoomIn',
		'icon' => 'zoom-in',
		'data-xf-init' => 'tooltip',
		'title' => 'Zoom in',
	), '', array(
	)) . '
						' . $__templater->button('', array(
		'class' => 'button--link button--iconOnly js-ctrlZoomOut',
		'icon' => 'zoom-out',
		'data-xf-init' => 'tooltip',
		'title' => 'Zoom out',
	), '', array(
	)) . '
					</div>
					<div class="buttonGroup">
						' . $__templater->button('', array(
		'class' => 'button--link button--iconOnly js-ctrlRotateLeft',
		'icon' => 'rotate-left',
		'data-xf-init' => 'tooltip',
		'title' => 'Rotate left',
	), '', array(
	)) . '
						' . $__templater->button('', array(
		'class' => 'button--link button--iconOnly js-ctrlRotateRight',
		'icon' => 'rotate-right',
		'data-xf-init' => 'tooltip',
		'title' => 'Rotate right',
	), '', array(
	)) . '
					</div>
					<div class="buttonGroup">
						' . $__templater->button('', array(
		'class' => 'button--link button--iconOnly js-ctrlFlipH',
		'data-scale' => '-1',
		'icon' => 'flip-h',
		'data-xf-init' => 'tooltip',
		'title' => 'Flip horizontally',
	), '', array(
	)) . '
						' . $__templater->button('', array(
		'class' => 'button--link button--iconOnly js-ctrlFlipV',
		'data-scale' => '-1',
		'icon' => 'flip-v',
		'data-xf-init' => 'tooltip',
		'title' => 'Flip vertically',
	), '', array(
	)) . '
					</div>
					' . $__templater->button('', array(
		'class' => 'button--link js-ctrlClear',
		'icon' => 'cancel',
		'data-xf-init' => 'tooltip',
		'title' => 'Clear selection',
	), '', array(
	)) . '
				', array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
			</div>
			' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
		'html' => '
					' . $__templater->button('', array(
		'type' => 'submit',
		'name' => 'preview',
		'icon' => 'preview',
	), '', array(
	)) . '
				',
	)) . '
		</div>
	</div>
	' . $__templater->formHiddenVal('crop_data', $__templater->filter(array('scaleX' => 1, 'scaleY' => 1, 'rotate' => 0, 'x' => 0, 'y' => 0, 'width' => 0, 'height' => 0, ), array(array('json', array()),), false), array(
		'class' => 'js-cropData',
	)) . '
', array(
		'action' => $__templater->func('link', array('media/edit-image', $__vars['mediaItem'], ), false),
		'ajax' => 'true',
		'data-xf-init' => 'image-editor',
		'class' => 'u-jsOnly',
	));
	return $__finalCompiled;
}
);