<?php
// FROM HASH: 50b7e70866523e05eeb0b434042021b3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Checkout'), $__templater->func('link', array('dbtech-ecommerce/checkout', ), false), array(
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