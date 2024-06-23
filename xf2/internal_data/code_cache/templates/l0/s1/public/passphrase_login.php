<?php
// FROM HASH: c8513828f780dd929d95d0d64ac856d5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Require Passphrase');
	$__finalCompiled .= '
' . $__templater->form('
		  
    <div class="block-container">
		<div class="block-body">
			
        ' . $__templater->formRow($__templater->escape($__vars['user']['username']), array(
		'label' => 'Hi',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'passphrase',
		'required' => 'true',
	), array(
		'label' => 'PassPhrase',
	)) . '
		
		<input type="hidden" name="user_id" value="' . $__templater->escape($__vars['user']['user_id']) . '" />
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('login/pas-phrase', $__vars['user'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);