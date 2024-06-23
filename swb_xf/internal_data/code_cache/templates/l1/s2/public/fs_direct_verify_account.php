<?php
// FROM HASH: 5737c06e0dd093a5aa90d8c4bf7a34be
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Activation Account');
	$__finalCompiled .= '


' . $__templater->form('

		<div class="block-container">
		<div class="block-body">
			
				' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'placeholder' => 'username',
	), array(
		'label' => 'username',
	)) . '

				' . $__templater->formTextBoxRow(array(
		'name' => 'activation_id',
		'placeholder' => 'Activation Id',
	), array(
		'label' => 'Activation Id',
	)) . '
	
</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Activate',
	), array(
	)) . '
	</div>
	
', array(
		'action' => $__templater->func('link', array('register/direct-verify', ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);