<?php
// FROM HASH: 8e58c8bb4a36729f8145e925d9e4efcb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Checkout'), $__templater->func('link', array('dbtech-ecommerce/checkout', ), false), array(
	));
	$__finalCompiled .= '

';
	$__vars['registerBackup'] = $__vars['xf']['options']['registrationSetup']['enabled'];
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['xf']['options']['registrationSetup']['enabled'] = '0';
	$__finalCompiled .= $__templater->includeTemplate('login', $__compilerTemp1) . '

';
	$__vars['xf']['options']['registrationSetup']['enabled'] = $__vars['registerBackup'];
	$__finalCompiled .= '

';
	if ($__vars['xf']['options']['registrationSetup']['enabled']) {
		$__finalCompiled .= '
	<div class="blocks-textJoiner"><span></span><em>' . 'or' . '</em><span></span></div>

	' . $__templater->includeTemplate('register_form', $__vars) . '

	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Login or register');
		$__finalCompiled .= '	
';
	}
	return $__finalCompiled;
}
);