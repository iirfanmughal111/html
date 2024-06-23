<?php
// FROM HASH: ab814d5c6660f6ee248eb46625307f18
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Change Public key');
	$__finalCompiled .= '
' . $__templater->form('
		  
    <div class="block-container">
		<div class="block-body">
			

	' . $__templater->formRow($__templater->escape($__vars['xf']['visitor']['username']), array(
		'label' => 'Hi',
	)) . '

      	' . $__templater->formTextAreaRow(array(
		'name' => 'public_key',
		'rows' => '5',
		'autosize' => 'true',
		'required' => 'required',
	), array(
		'label' => 'Public Key',
		'hint' => 'Required',
	)) . '
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('account/new-public-key', $__vars['null'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);