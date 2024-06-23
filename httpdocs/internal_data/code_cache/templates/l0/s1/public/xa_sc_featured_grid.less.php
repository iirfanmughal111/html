<?php
// FROM HASH: 14412b390f0e486196542c5aaecd8c55
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.sc-featured-grid
{
	.m-listPlain();
	.m-clearFix();
	.first
	{
		float: left;
		width: 50%;
		padding-right: 10px;
	}
	.second
	{
		float: left;
		width: 50%;
	}
	.item
	{
		position: relative;
		overflow: hidden;
		&.large-item
		{
			.image-link:after
			{
				height: 60%;
			}
		}
		&.medium-item
		{
			margin-bottom: 10px;
			.image-link
			{
				height: 233px;
				min-height: 233px;
			}
			.item-heading
			{
				font-size: @xf-fontSizeLarger;
   				line-height: 1.4;
			}
		}
		&.medium-item-2
		{
			margin-bottom: 10px;
			.image-link
			{
				min-height: 216px;
			}
			.item-heading
			{
				font-size: @xf-fontSizeLarger;
   				line-height: 1.4;
			}
		}
		&.small-item
		{
			float: left;
			width: 50%;
			.image-link
			{
				min-height: 216px;
			}
			.item-heading
			{
				font-size: @xf-fontSizeNormal;
   				line-height: 1.4;
			}
			&.first-small-item
			{
				padding-right: 5px;
			}
			&.second-small-item
			{
				padding-left: 5px;
			}
		}
		&.is-unread
		{
			.caption .item-heading
			{
				font-weight: @xf-fontWeightHeavy;
			}
		}
	}
	.caption
	{
		max-width: 100%;
		position: absolute;
		padding: 16px 16px;
		bottom: 0px;
		display: block;
		&.caption-small
		{
			max-width: initial;
		}
		.item-heading
		{
			display: inline-block;
			padding-bottom: @xf-paddingMedium;
			font-size: @xf-fontSizeLarger;
			font-weight: @xf-fontWeightNormal;
			line-height: 1.4;
			color: #fff;
			text-shadow: 0 1px 1px rgba(0,0,0,.35);
		}
		.item-heading a
		{
			color: #fff;
			text-shadow: 0 1px 1px rgba(0,0,0,.35);
		}		
		h3
		{
			padding: 0;
			margin: 0;
		}
		.item-rating
		{
			font-size: @xf-fontSizeSmallest;
			padding-bottom: @xf-paddingSmall;			
		}		
		.item-location
		{
			color: #bbb;
			font-size: @xf-fontSizeSmallest;
			padding-bottom: @xf-paddingSmall;
			
			.item-location-icon 
			{
				font-size: @xf-fontSizeSmallest;
				padding-left: @xf-paddingSmall;
			}
		}		
		.item-location a
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
		.item-share	
		{
			color: #bbb;
			font-size: @xf-fontSizeSmallest;
		}
	}
	.image-link
	{
		display: block;
		position: relative;
		overflow: hidden;
		background: #ccc;
		min-height: 460px;
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
}

@media (max-width:800px)
{
	.sc-featured-grid .image-link
	{
		min-height: 240px;
	}
	
	.sc-featured-grid .item.medium-item .image-link 
	{
		height: 115px;
	}

	.sc-featured-grid .item.medium-item .image-link,
	.sc-featured-grid .item.medium-item-2 .image-link,
	.sc-featured-grid .item.small-item .image-link 
	{
		min-height: 115px;
	}

	.sc-featured-grid .caption
	{
		padding: 8px;
		max-width: 100%;
		bottom: 0;
	}

	.sc-featured-grid .caption .item-heading,
	.sc-featured-grid .item.medium-item .caption .item-heading,
	.sc-featured-grid .item.medium-item-2 .caption .item-heading  
	{
		padding-bottom: @xf-paddingSmall;
		font-size: @xf-fontSizeLarge;
	}
}

@media (max-width:650px)
{
	.sc-featured-grid .first, 
	.sc-featured-grid .second
	{
		float: none;
		width: auto;
		padding: 0;
		margin-bottom: 10px;
	}

	.sc-featured-grid .item.medium-item .image-link 
	{
		height: 240px;
	}

	.sc-featured-grid .item.medium-item .image-link,
	.sc-featured-grid .item.medium-item-2 .image-link,
	.sc-featured-grid .item.small-item .image-link 
	{
		min-height: 240px;
	}

	.sc-featured-grid .caption .item-heading,
	.sc-featured-grid .item.medium-item .caption .item-heading,
	.sc-featured-grid .item.medium-item-2 .caption .item-heading  
	{
		padding-bottom: @xf-paddingSmall;
		font-size: @xf-fontSizeLarge;
	}
}

@media (max-width:450px)
{
	.sc-featured-grid .item.small-item
	{
		float: none;
		width: auto;
		margin: 0 0 10px;
		padding: 0 !important;
	}
}';
	return $__finalCompiled;
}
);