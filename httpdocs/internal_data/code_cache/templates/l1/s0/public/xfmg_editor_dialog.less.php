<?php
// FROM HASH: a74682b23b0298a3cdecfdff5b3f9d59
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.embedTabs
{
	.embedTabs-tab
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

.itemList
{
	&.itemList--picker
	{
		.itemList-item
		{
			width: (@_thumbSize / 1.5) / 1.6;
			max-width: @_thumbSize / 1.5;

			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;

			&.itemList-item--placeholder--temp
			{
				margin-top: 0;
				margin-bottom: 0;
				height: 0;
			}

			&.is-selected
			{
				border: 4px solid @xf-borderColorFeature;
			}

			label
			{
				cursor: pointer;
			}

			img
			{
				-webkit-user-drag: none;
				pointer-events: none; // for IE11
			}
		}

		.itemList-checkbox
		{
			display: none;
		}

		.itemList-itemOverlay
		{
			height: 18px;
			bottom: -18px;
		}

		.itemInfoRow
		{
			.itemInfoRow-title
			{
				font-size: @xf-fontSizeSmallest;
			}
		}

		.itemList-footer
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

' . $__templater->includeTemplate('xfmg_media_list.less', $__vars);
	return $__finalCompiled;
}
);