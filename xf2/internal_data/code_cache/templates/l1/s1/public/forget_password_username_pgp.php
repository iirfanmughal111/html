<?php
// FROM HASH: 3366268fe5037631473eff4613d77780
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Forget Password');
	$__finalCompiled .= '
' . $__templater->form('
		  
    <div class="block-container">
		<div class="block-body">
			
	' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'autofocus' => 'autofocus',
		'autocomplete' => 'username',
	), array(
		'label' => 'Username',
	)) . '
			
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('lost-password', $__vars['null'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);