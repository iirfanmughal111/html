<?php
// FROM HASH: 1db6acb763610b58bfea25c2c7aab2b1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.embeddedProduct
{
	width: 560px;
	max-width: 100%;

	margin: 0;
	border: 1px solid @xf-borderColor;
	border-radius: @xf-borderRadiusMedium;
	overflow: hidden;

	.embeddedProduct-container
	{
		border-top: 1px solid @xf-borderColor;

		.avatar
		{
			margin-top: @xf-paddingMedium;
			margin-left: auto;
			margin-right: auto;
			position: relative;
			display: block;
		}

		img
		{
			max-width: 100%;
			vertical-align: middle;
		}

		.bbMediaWrapper
		{
			width: 100%;
		}
	}

	.embeddedProduct-thumbList
	{
		display: flex;
		flex-flow: row wrap;
		margin: 1px;
	}

	.embeddedProduct-thumbList-item
	{
		flex: auto;
		width: 92px; // 100px - borders and margins
		max-width: 250px;
		margin: 1px;

		position: relative;
		overflow: hidden;

		&.embeddedProduct-thumbList-item--showMore
		{
			.xf-contentAltBase();
			.xf-blockBorder();

			span
			{
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translate(-50%, -50%);
				color: @xf-textColorMuted;
				.m-textOutline(@xf-textColorMuted, xf-intensify(@xf-textColorMuted, 20%));
				font-size: @xf-fontSizeLargest * 1.5;
			}
		}

		&.embeddedProduct-thumbList-item--placeholder
		{
			margin-top: 0;
			margin-bottom: 0;
			height: 0;
		}
	}

	.embeddedProduct-info
	{
		margin-top: 5px;

		.contentRow-figure
		{
			padding-left: @xf-paddingMedium;
			padding-bottom: @xf-paddingMedium;
		}

		.contentRow-main
		{
			padding: @xf-paddingMedium;
		}
	}
}';
	return $__finalCompiled;
}
);