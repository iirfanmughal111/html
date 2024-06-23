<?php
// FROM HASH: 19b33a3a8d5d12bf5c2420e2793f1fe8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Thread Thumbnail');
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['thread'], 'getThumbnailExit', array())) {
		$__compilerTemp1 .= '
										' . $__templater->formInfoRow('
											<img src="' . $__templater->escape($__templater->method($__vars['thread'], 'getThumbnailPath', array())) . '" style="width:80px;height:60px" >
										', array(
			'rowtype' => 'confirm',
		)) . '
					';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
		' . '
					' . $__compilerTemp1 . '
			' . $__templater->formTextBoxRow(array(
		'value' => $__vars['thread']['thumbnail_title'],
		'name' => 'thumbnail_title',
	), array(
		'label' => 'Thumbnail Title',
		'hint' => 'Required',
	)) . '
			' . $__templater->formUploadRow(array(
		'name' => 'upload',
		'accept' => '.gif,.jpeg,.jpg,.jpe,.png,.svg',
	), array(
		'label' => 'Thumbnail Image',
		'explain' => 'Minimum image required dimension ' . $__templater->escape($__vars['xf']['options']['thumbnailImageDimensions']['width']) . ' * ' . $__templater->escape($__vars['xf']['options']['thumbnailImageDimensions']['height']) . '  px',
	)) . '
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('threads/thumbnail', $__vars['thread'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);