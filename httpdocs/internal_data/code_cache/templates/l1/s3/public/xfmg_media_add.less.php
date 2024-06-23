<?php
// FROM HASH: 1b0876b043ab9d93c169f7eaf2f95565
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.block
{
	&.block--mediaList
	{
		margin-top: 0;
	}
}

.mediaList
{
	.m-listPlain();
	.m-clearFix();

	margin: 4px 0 0;

	> li
	{
		.xf-contentAltBase();
		.xf-blockBorder();
		border-radius: @xf-blockBorderRadius;

		margin-bottom: @xf-paddingMedium;
		padding: @xf-paddingMedium;

		.contentRow-main
		{
			padding-left: @xf-paddingMedium;

			.mediaItem-input
			{
				border-top: none;

				> dt
				{
					padding: 0;
				}
			}
		}

		&:last-child
		{
			margin-bottom: 0;
		}

		&.is-uploadError
		{
			.contentRow-title span // span needed due to opacity creating new stacking context
			{
				text-decoration: line-through;
				opacity: .5;
			}

			.contentRow-figure
			{
				opacity: .5;
			}
		}
	}

	&.mediaList--buttons
	{
		> .mediaList-button
		{
			float: left;
			width: 120px;
			height: 120px;

			display: flex;
			align-items: center;
			justify-content: center;
			position: relative;

			margin-right: @xf-paddingLarge;
			margin-bottom: @xf-paddingMedium;

			&:last-child
			{
				margin-right: 0;
			}

			&.is-hidden
			{
				display: none;
			}

			.mediaList-inner
			{
				text-align: center;
				display: block;

				&.mediaList-inner--footer
				{
					position: absolute;
					bottom: 2px;
					margin-top: 1px;
				}

				&::before
				{
					.m-faBase();
					font-size: 45px;
					display: block;
				}

				&--upload::before { .m-faContent(@fa-var-upload); }
				&--link::before { .m-faContent(@fa-var-cloud-upload); }

				&:hover
				{
					text-decoration: none;
				}
			}
		}
	}
}

.mediaList-figure.contentRow-figure
{
	width: 100px;

	img
	{
		max-height: 100px;
		max-width: 100px;
	}
}

.mediaList-placeholder
{
	display: block;
	width: 100px;

	&:before
	{
		display: inline-block;
		.m-faBase();
		.m-faContent(@fa-var-camera);
		font-size: 60px;
		vertical-align: middle;
		color: @xf-textColorFeature;
		border-radius: 100%;
	}
}

.mediaList-progress
{
	position: relative;

	i
	{
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		background: @xf-progressBarColor;
		color: contrast(@xf-progressBarColor);
		border-radius: @xf-borderRadiusMedium;
		padding-right: .2em;
		text-align: right;
		font-style: normal;
		white-space: nowrap;
		min-width: 2em;

		.m-transition(width);
	}
}

.mediaList-error
{
	color: @xf-textColorAttention;
}

@media (max-width: @xf-responsiveNarrow)
{
	.mediaList-figure.contentRow-figure
	{
		width: 50px;

		img
		{
			max-height: 50px;
			max-width: 50px;
		}
	}

	.mediaList-placeholder
	{
		display: block;
		width: 50px;

		&:before
		{
			font-size: 30px;
		}
	}
}

.typesList
{
	.typesList-type
	{
		.xf-link();

		margin-right: @xf-paddingLarge;

		&:last-child
		{
			margin-right: 0;
		}

		&:hover
		{
			.xf-linkHover();
			text-decoration: none;
		}

		&::before
		{
			.m-faBase();
		}

		&--image::before { .m-faContent(@fa-var-image); }
		&--video::before { .m-faContent(@fa-var-video); }
		&--audio::before { .m-faContent(@fa-var-music); }
		&--embed::before { .m-faContent(@fa-var-cloud-upload); }
	}
}';
	return $__finalCompiled;
}
);