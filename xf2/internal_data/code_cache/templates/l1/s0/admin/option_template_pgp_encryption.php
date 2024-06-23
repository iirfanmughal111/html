<?php
// FROM HASH: 800dd6e986d6110b470f2bb35a88395f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
		'name' => $__vars['inputName'] . '[enable_admin_pgp]',
		'value' => $__vars['option']['option_value']['enable_admin_pgp'],
	), array(array(
		'value' => 'enable_public_key',
		'label' => 'Enable for Admin',
		'data-hide' => 'true',
		'_dependent' => array('
		
			' . $__templater->formTextAreaRow(array(
		'name' => $__vars['inputName'] . '[public_key]',
		'rows' => '5',
		'autosize' => 'true',
		'placeholder' => 'public_key',
		'value' => $__vars['option']['option_value']['public_key'],
		'required' => 'true',
	), array(
		'label' => 'Public Key',
	)) . '
	   
	'),
		'_type' => 'option',
	)), array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);