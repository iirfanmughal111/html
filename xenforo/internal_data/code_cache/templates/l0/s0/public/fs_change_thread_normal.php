<?php
// FROM HASH: 38433fa1bc8c426a822afbb1fa16a0d6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure to Change Normal View?' . $__vars['xf']['language']['label_separator'] . '
				
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
		'action' => $__templater->func('link', array('threads/normal-view', $__vars['thread'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);