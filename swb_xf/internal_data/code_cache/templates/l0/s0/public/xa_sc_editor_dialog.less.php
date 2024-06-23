<?php
// FROM HASH: 1d9c184f02b950fff769068e4977ec60
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.scEmbedTabs
{
	.scEmbedTabs-tab
	{
		position: relative;

		.badge
		{
			display: none;
		}

		&.has-selected
		{
			.badge
			{
				display: inline;

				&.badge--highlighted
				{
					color: @xf-paletteColor1;
					background: @xf-paletteColor3;
				}
			}
		}
	}
}

.scItemList
{
	&.scItemList--picker
	{
		.scItemList-item
		{
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;

			margin: 5px;
			padding: 5px;

			border-top: @xf-borderSize solid @xf-borderColorLight;

			&:first-child
			{
				border-top: none;
			}

			&.is-selected
			{
				border: 4px solid @xf-borderColorFeature;
			}

			label
			{
				cursor: pointer;
				color: @xf-linkColor;
			}

			img
			{
				-webkit-user-drag: none;
				pointer-events: none; // for IE11
			}
		}

		.scItemList-checkbox
		{
			display: none;
		}

		.scItemList-footer
		{
			flex-basis: 100%;
			margin: @xf-paddingMedium @xf-paddingSmall;

			.button
			{
				float: right;
			}
		}
	}
}

' . $__templater->includeTemplate('xa_sc.less', $__vars);
	return $__finalCompiled;
}
);