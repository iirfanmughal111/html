<?php
// FROM HASH: 9d3b8fca9eaf69990b3f57645644e992
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.avatar.avatar--productIconDefault
{
	color: @xf-textColorMuted !important;
	background: mix(@xf-textColorMuted, @xf-avatarBg, 25%) !important;
	text-align: center;

	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;

	> span:before
	{
		.m-faBase();
		.m-faContent(@fa-var-cog);
		vertical-align: middle;
	}
}

.avatar--productIcon
{
	border-radius: inherit;
}

.p-navgroup-link
{
	&--dbtechEcommerceCart i:after
	{
		.m-faBase();
		display: inline-block;
		min-width: 1em;
		.m-faContent(@fa-var-shopping-cart, .88em);
		
		';
	$__vars['addOnId'] = $__templater->preEscaped('ThemeHouse/UIX');
	$__finalCompiled .= '
		';
	if ($__vars['xf']['addOns'][$__vars['addOnId']]) {
		$__finalCompiled .= '
			' . $__templater->callMacro('uix_icons.less', 'content', array(
			'icon' => 'shopping-cart',
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
	}
}';
	return $__finalCompiled;
}
);