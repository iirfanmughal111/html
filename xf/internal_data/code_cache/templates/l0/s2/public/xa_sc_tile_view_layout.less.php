<?php
// FROM HASH: 1677c9970e215cc959ce3156000195e9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.gridContainerScTileView .sc-tile-view
{
	.m-listPlain();
	.m-clearFix();
	display: flex;
	flex-wrap: wrap;
	margin: 3px;

	.tile-image-link
	{
		display: block;
		position: relative;
		overflow: hidden;
		background: #ccc;
		min-height: 260px;
		transition: all .5s;
		&:hover
		{
			transform: scale(1.1);
		}
		&:after
		{
			content: "";
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 80%;
			background: linear-gradient(to bottom,rgba(0,0,0,0) 0,rgba(0,0,0,0.62) 50%,rgba(0,0,0,0.88) 100%);
			opacity: .92;
		}
	}

	.tile-container
	{
		flex: 1 1 33%;
		max-width: 33%;
		padding: 5px;

		@media (max-width: 1200px) { flex: 1 1 50%; max-width: 50%;}
		@media (max-width: @xf-responsiveWide) { flex: 1 1 50%; max-width: 50%;}
		@media (max-width: @xf-responsiveMedium) { flex: 1 1 100%; max-width: 100%;}
		@media (max-width: @xf-responsiveNarrow) { flex: 1 1 100%; max-width: 100%;}
		
		&.is-unread
		{
			.tile-caption .tile-item-heading
			{
				font-weight: @xf-fontWeightHeavy;
			}
		}
		&.is-mod-selected
		{
			.tile-caption
			{
				background: @xf-inlineModHighlightColor !important;
				border: 1px solid @xf-textColorMuted;

				.tile-item-heading,
				.tile-item-heading a
				{
					color: @xf-linkColor;
					text-shadow: none;		
				}
				.tile-item-location
				{
					color: @xf-textColorDimmed;
				}
				.tile-item-location a
				{
					color: @xf-linkColor;
				}
				.listInline.listInline--bullet 
				{
					.create-date
					{
						color: @xf-textColorMuted;
					}
					.category-title,
					.tile-username
					{
						color: @xf-linkColor;	
					}
				}
			}
		}
		&.is-moderated
		{
			.tile-caption
			{
				background: @xf-contentHighlightBg;
				border: 1px solid @xf-textColorMuted;

				.tile-item-heading,
				.tile-item-heading a
				{
					color: @xf-linkColor;
					font-style: italic;
					text-shadow: none;		
				}
				.tile-item-location
				{
					color: @xf-textColorDimmed;
				}
				.tile-item-location a
				{
					color: @xf-linkColor;
				}
				.listInline.listInline--bullet 
				{
					.create-date
					{
						color: @xf-textColorMuted;
					}
					.category-title,
					.tile-username
					{
						color: @xf-linkColor;	
					}
				}
			}
		}
		&.is-deleted
		{
			opacity: .7;

			.tile-caption .tile-item-heading
			{
				text-decoration: line-through;
			}
		}
	}

	.tile-item
	{
		position: relative;
		overflow: hidden;
		&.large--tile-item
		{
			.tile-image-link:after
			{
				height: 60%;
			}
		}
	}

	.tile-caption
	{
		width: 100%;
		max-width: 100%;
		position: absolute;
		padding: 16px 16px;
		bottom: 0px;
		display: block;
		&.tile-caption-small
		{
			max-width: initial;
		}
		.tile-item-heading
		{
			display: inline-block;
			padding-bottom: @xf-paddingMedium;
			font-size: @xf-fontSizeLarger;
			font-weight: @xf-fontWeightNormal;
			line-height: 1.4;
			color: #fff;
			text-shadow: 0 1px 1px rgba(0,0,0,.35);
		}
		.tile-item-heading a
		{
			color: #fff;
			text-shadow: 0 1px 1px rgba(0,0,0,.35);
		}		
		h3
		{
			padding: 0;
			margin: 0;
		}
		.tile-item-rating
		{
			font-size: @xf-fontSizeSmallest;
			padding-bottom: @xf-paddingSmall;			
		}		
		.tile-item-location
		{
			color: #bbb;
			font-size: @xf-fontSizeSmallest;
			padding-bottom: @xf-paddingSmall;
			
			.tile-item-location-icon 
			{
				font-size: @xf-fontSizeSmallest;
				padding-left: @xf-paddingSmall;
			}
		}		
		.tile-item-location a
		{
			color: #fff;
		}
		.listInline.listInline--bullet
		{
			color: #bbb;
			font-size: @xf-fontSizeSmallest;
		}
		.create-date,
		.category-title,
		.tile-username	
		{
			color: #bbb;
			font-size: @xf-fontSizeSmallest;
		}
		.tile-item-minor
		{
			font-size: @xf-fontSizeSmallest;
			color: @xf-textColorMuted;

			.m-hiddenLinks();
		}
		.tile-item-extraInfo
		{
			.m-listPlain();
			float: right;

			> li
			{
				float: left;
				margin-left: 8px;
			}

			input[type=checkbox]
			{
				.m-checkboxAligner();
			}
		}
	}
}

@media (max-width:800px)
{
	.gridContainerScTileView .sc-tile-view .tile-image-link
	{
		min-height: 240px;
	}

	.gridContainerScTileView .sc-tile-view .tile-caption
	{
		padding: 8px;
		max-width: 100%;
		bottom: 0;
	}

	.gridContainerScTileView .sc-tile-view .tile-caption .tile-item-heading
	{
		font-size: @xf-fontSizeLarge;
		padding-bottom: @xf-paddingSmall;
	}
}

@media (max-width:650px)
{
	.gridContainerScTileView .sc-tile-view .tile_container
	{
		float: none;
		width: auto;
		padding: 0;
		margin-bottom: @xf-paddingLarge;
	}
}';
	return $__finalCompiled;
}
);