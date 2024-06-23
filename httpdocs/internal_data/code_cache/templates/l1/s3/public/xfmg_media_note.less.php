<?php
// FROM HASH: bed04323a1fead4fb4cd88aa64f8f877
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.media-container
{
	.cropper-container
	{
		&.cropper-bg
		{
			background: none;
		}
	}

	.media-container-image
	{
		position: relative;

		.has-touchevents &,
		&.is-tooltip-active,
		&:hover
		{
			.mediaNote
			{
				opacity: .75;
				transition: opacity .75s ease-out;
			}
		}
	}

	.mediaNote
	{
		display: none;
		position: absolute;

		border-radius: @xf-borderRadiusSmall;
		border: @xf-borderSizeMinorFeature solid @xf-paletteNeutral1;
		.m-dropShadow(0, 5px, 10px, 0, .35);
		opacity: 0;
	}

	.mediaNote-tooltip
	{
		display: none;
	}
}

@_tooltip-mediaNoteArrowSize: 10px;

.tooltip
{
	&.tooltip--mediaNote
	{
		max-width: 100%;
		width: 250px;
		padding: 0 15px;

		// Tooltip positioning is literal.
		&.tooltip--top { padding-bottom: @_tooltip-mediaNoteArrowSize; }
		&.tooltip--right { -ltr-rtl-padding-left: @_tooltip-mediaNoteArrowSize; }
		&.tooltip--bottom { padding-top: @_tooltip-mediaNoteArrowSize; }
		&.tooltip--left { -ltr-rtl-padding-right: @_tooltip-mediaNoteArrowSize; }
	}
}

.tooltip-content
{
	.tooltip--mediaNote &
	{
		.xf-contentBase();
		padding: 0;
		text-align: left;
		border: 1px solid @xf-borderColor;
		border-radius: @xf-borderRadiusMedium;
		font-size: @xf-fontSizeSmall;
		.m-dropShadow(0, 5px, 10px, 0, .35);
	}

	.tooltip--mediaNote--plain &
	{
		.contentRow-minor
		{
			font-size: @xf-fontSizeSmallest;
		}
	}
}

.noteTooltip-row
{
	margin: 0;
	padding: @xf-paddingSmall;

	&.noteTooltip-row--separated
	{
		+ .noteTooltip-row
		{
			border-top: @xf-borderSize solid @xf-borderColorLight;
		}
	}
}

.noteTooltip-footer
{
	font-size: @xf-fontSizeSmaller;
	color: @xf-textColorDimmed;
	background: @xf-contentAltBg;
	border-top: @xf-borderSize solid @xf-borderColorLight;
	padding: @xf-paddingSmall;

	&.noteTooltip-footer--smallest
	{
		font-size: @xf-fontSizeSmallest;
	}
}

/* XF-RTL:disable */
.tooltip-arrow
{
	.m-tooltipArrow(@xf-borderColor, @_tooltip-mediaNoteArrowSize + 1px, ~\'.tooltip--mediaNote\', @xf-contentBg);

	.tooltip--mediaNote.tooltip--top &:after
	{
		.m-triangleDown(xf-default(@xf-contentAltBg, transparent), @_tooltip-mediaNoteArrowSize);
	}
}
/* XF-RTL:enable */';
	return $__finalCompiled;
}
);