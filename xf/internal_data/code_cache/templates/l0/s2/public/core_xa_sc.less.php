<?php
// FROM HASH: 7402a0d12ef7369f2165a4f5ec2663ce
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ############################ MISC SHOWCASE CSS ######################

.structItem-status
{
	&--scItem::before { .m-faContent(@fa-var-images, 1.2em); color: @xf-textColorDimmed; }
	&--poll::before { .m-faContent(@fa-var-chart-bar); }		
}

.scItemSearchResultRow .contentRow-figure,
.scSeriesSearchResultRow .contentRow-figure
{
	max-width: 100px;
}

.avatar.avatar--itemIconDefault
{
	color: @xf-textColorMuted !important;
	background: mix(@xf-textColorMuted, @xf-avatarBg, 25%) !important;
	text-align: center;
	line-height: 1.5;

	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;

	> span:before
	{
		.m-faBase();
		.m-faContent(@fa-var-cog, .86em);
		vertical-align: -0.03em;
	}
}';
	return $__finalCompiled;
}
);