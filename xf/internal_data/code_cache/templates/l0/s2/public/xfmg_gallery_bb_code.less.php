<?php
// FROM HASH: dc1f6640bac40fab88949a559d685d13
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.embeddedMedia
{
	width: fit-content;
	max-width: 560px;

	margin: 0;
	border: 1px solid @xf-borderColor;
	border-radius: @xf-borderRadiusMedium;
	text-align: left;

	.m-blockAligner();

	.embeddedMedia-container
	{
		border-bottom: 1px solid @xf-borderColor;

		img
		{
			max-width: 100%;
			vertical-align: middle;
		}

		.bbMediaWrapper
		{
			width: 100%;
		}

		video
		{
			width: 100%;

			&[data-video-type="audio"]
			{
				&[poster=""]
				{
					display: block;
					max-height: 90px;
					padding: @xf-paddingMedium;
				}
			}
		}
	}

	.embeddedMedia-thumbList
	{
		display: flex;
		flex-flow: row wrap;
		margin: 1px;
	}

	.embeddedMedia-thumbList-item
	{
		flex: auto;
		width: 92px; // 100px - borders and margins
		max-width: 250px;
		margin: 1px;

		position: relative;
		overflow: hidden;

		&.embeddedMedia-thumbList-item--showMore
		{
			.xf-contentAltBase();
			.xf-blockBorder();

			img
			{
				width: 100%;
				height: 100%;
			}

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

		&.embeddedMedia-thumbList-item--placeholder
		{
			margin-top: 0;
			margin-bottom: 0;
			height: 0;
		}
	}

	.embeddedMedia-info
	{
		margin-top: 5px;

		.contentRow-main
		{
			padding: @xf-paddingMedium;
		}
	}
}';
	return $__finalCompiled;
}
);