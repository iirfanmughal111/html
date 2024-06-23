<?php
// FROM HASH: 3bb0930dcc30a88f33d9faa489d255b3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'pgp_option',
		'selected' => $__vars['xf']['visitor']['pgp_option'],
		'label' => 'PGP',
		'hint' => 'Enable PGP',
		'_type' => 'option',
	),
	array(
		'name' => 'passphrase_option',
		'selected' => $__vars['xf']['visitor']['passphrase_option'],
		'label' => 'PassPhrase',
		'hint' => 'Enable Passphrase',
		'_type' => 'option',
	)), array(
		'label' => 'Login Options',
	));
	return $__finalCompiled;
}
);