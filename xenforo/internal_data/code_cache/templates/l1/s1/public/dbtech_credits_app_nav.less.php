<?php
// FROM HASH: 41f6e7d722c2af31febce18722ade749
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.p-navgroup-link
{
	&.p-navgroup-link--dbtech-credits i:after
	{
		.m-faBase();
		display: inline-block;
		min-width: 1.4em;
		.m-faContent(@fa-var-money-bill-alt, .88em);
		
		';
	$__vars['addOnId'] = $__templater->preEscaped('ThemeHouse/UIX');
	$__finalCompiled .= '
		';
	if ($__vars['xf']['addOns'][$__vars['addOnId']]) {
		$__finalCompiled .= '
			' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'payment',
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
	}
}';
	return $__finalCompiled;
}
);