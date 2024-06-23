<?php
// FROM HASH: 49b175356d91eeee18ad996d62f70605
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Header Image');
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['thread'], 'getimageExit', array())) {
		$__compilerTemp1 .= '
										' . $__templater->formInfoRow('
											<img src="' . $__templater->escape($__templater->method($__vars['thread'], 'getImgPath', array())) . '" style="width:80px;height:60px" >
										', array(
			'rowtype' => 'confirm',
		)) . '
					';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
		
					' . $__templater->formInfoRow('
							<div class="blockMessage blockMessage--important"><strong>' . 'Note' . $__vars['xf']['language']['label_separator'] . '</strong> ' . 'Change View by upload Image required....' . '</div>
					', array(
	)) . '
			
					' . $__compilerTemp1 . '
			
			' . $__templater->formUploadRow(array(
		'name' => 'upload',
		'accept' => '.gif,.jpeg,.jpg,.jpe,.png,.svg',
	), array(
		'label' => 'Upload Header Image',
		'explain' => 'Minimum image required dimension 1920 × 460 px',
	)) . '
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('threads/article-view', $__vars['thread'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);