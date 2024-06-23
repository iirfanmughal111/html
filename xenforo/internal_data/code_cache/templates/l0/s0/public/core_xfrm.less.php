<?php
// FROM HASH: 9139f359dbcb43ef682668a163e09cad
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.avatar.avatar--resourceIconDefault
{
	color: xf-default(@xf-textColorMuted, black) !important;
	background: mix(xf-default(@xf-textColorMuted, black), xf-default(@xf-avatarBg, white), 25%) !important;
	text-align: center;

	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;

	> span:before
	{
		.m-faBase();
		.m-faContent(@fa-var-cog);
	}
}';
	return $__finalCompiled;
}
);