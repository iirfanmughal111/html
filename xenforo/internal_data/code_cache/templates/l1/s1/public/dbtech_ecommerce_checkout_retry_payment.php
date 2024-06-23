<?php
// FROM HASH: 6a1181a6b746aee4230ab25d4c7d7325
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Complete purchase');
	$__finalCompiled .= '

';
	$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_checkout_macros', 'payment_options', array(
		'order' => $__vars['order'],
		'profiles' => $__vars['profiles'],
	), $__vars);
	return $__finalCompiled;
}
);