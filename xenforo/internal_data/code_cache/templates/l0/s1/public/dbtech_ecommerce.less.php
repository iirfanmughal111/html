<?php
// FROM HASH: 29f1d7c526f1165c31c1c5e94dcd200f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ############################ PRODUCT LIST ######################
.ribbon
{
	position: absolute;
	z-index: 1;
	overflow: hidden;
	width: 75px; height: 75px;
	text-align: right;

	span
	{
		font-size: 10px;
		font-weight: bold;
		text-transform: uppercase;
		text-align: center;
		line-height: 20px;
		width: 100px;
		display: block;
		position: absolute;
		top: 19px;

		&::before
		{
			content: "";
			position: absolute;
			left: 0;
			top: 100%;
			z-index: -1;
			border-right-color: transparent !important;
			border-bottom-color: transparent !important;
		}

		&::after
		{
			content: "";
			position: absolute;
			right: 0;
			top: 100%;
			z-index: -1;
			border-bottom-color: transparent !important;
			border-left-color: transparent !important;
		}
	}

	&&--sale
	{
		left: -5px; top: -5px;

		span
		{
			-ltr-transform: rotate(-45deg);
			-rtl-transform: rotate(45deg);
			.xf-dbtechEcommerceSaleRibbon();
			left: -21px;

			&::before
			{
				.xf-dbtechEcommerceSaleRibbonBorder();
			}

			&::after
			{
				.xf-dbtechEcommerceSaleRibbonBorder();
			}
		}
	}

	&&--featured
	{
		right: -5px; top: -5px;

		span
		{
			-ltr-transform: rotate(45deg);
			-rtl-transform: rotate(-45deg);
			.xf-dbtechEcommerceFeaturedRibbon();
			right: -21px;

			&::before
			{
				.xf-dbtechEcommerceFeaturedRibbonBorder();
			}

			&::after
			{
				.xf-dbtechEcommerceFeaturedRibbonBorder();
			}
		}
	}
}

.structItem-productRequirements
{
// 	margin-top: @xf-paddingSmall;
}

.structItem-productTagLine
{
	font-size: @xf-fontSizeSmaller;
	margin-top: @xf-paddingSmall;
}

.structItem-parts
{
	&--product
	{
		> li:nth-child(even)
		{
			color: inherit;
		}
	}
}

.structItem-cell.structItem-cell--checkbox
{
	width: 30px;
	font-size: @xf-fontSizeLarge;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
}

.structItem-cell.structItem-cell--productMeta
{
	width: 210px;

	.structItem-row--purchaseParent
	{
		padding-bottom: @xf-blockPaddingV;
	}
}

.structItem-metaItem--rating
{
	font-size: @xf-fontSizeSmall;
}

.dataList-cell
{
	&--flexHeight
	{
		.dataList-mainRow,
		.dataList-subRow
		{
			max-height: 100%;
		}
	}
}

.block-row
{
	&&--dbtechEcommerceTerms
	{
		max-height:25vh;
		overflow:auto
	}

	&&--checkout
	{
        padding-top: 0;
    }
}

.label
{
	&.label--smallest
	{
		&.label--aligner
		{
			vertical-align: middle;
		}
	}
}

@media (max-width: @xf-responsiveWide)
{
	.structItem-cell.structItem-cell--productMeta
	{
		width: 190px;
		font-size: @xf-fontSizeSmaller;
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.structItem-cell.structItem-cell--productMeta
	{
		display: block;
		width: auto;
		float: left;
		padding-top: 0;
		padding-left: 0;
		padding-right: 0;
		color: @xf-textColorMuted;

		.pairs
		{
			display: inline;

			&:before,
			&:after
			{
				display: none;
			}

			> dt,
			> dd
			{
				display: inline;
				float: none;
				margin: 0;
			}
		}

		.structItem-metaItem
		{
			display: inline;
		}

		.ratingStarsRow
		{
			display: inline;

			.ratingStarsRow-text
			{
				display: none;
			}
		}

		.structItem-metaItem--lastUpdate > dt
		{
			display: none;
		}

		.structItem-metaItem + .structItem-metaItem:before
		{
			display: inline;
			content: "\\20\\00B7\\20";
			color: @xf-textColorMuted;
		}
	}
}

// #################################### PRODUCT BODY / VIEW ########################

.block-body + .block-minorHeader
{
	border-top:none;
	margin-top: @xf-paddingLarge;
}

.block-row
{
	&--productIcon
	{
		float:left;
		padding-right: @xf-blockPaddingH;
		padding-top: @xf-blockPaddingV;
		padding-bottom: @xf-blockPaddingV;
	}

	&--pricingInfo
	{
		&&.block-row--pricingInfoDigital
		{
			.pairs--renewal
			{
				padding-bottom: @xf-paddingLarge;

				&:last-child
				{
					padding-bottom: 0;
				}
			}
		}
	}

	.productInfo-buttons
	{
		> .button
		{
			display: block;
			margin: 5px 0;

			&:first-child
			{
				margin-top: 0;
			}

			&:last-child
			{
				margin-bottom: 0;
			}
		}
	}
}

';
	if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width'] OR $__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height']) {
		$__finalCompiled .= '
.avatar
{
    &.avatar--productIconDefault,
    &.avatar--productIcon
    {
		&.avatar--xxs
		{
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width']) {
			$__finalCompiled .= '
				width: ' . ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width'] * 0.125) . 'px;
			';
		}
		$__finalCompiled .= '
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height']) {
			$__finalCompiled .= '
				height: ' . ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height'] * 0.125) . 'px;
			';
		}
		$__finalCompiled .= '
		}

		&.avatar--xs
		{
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width']) {
			$__finalCompiled .= '
				width: ' . ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width'] * 0.17) . 'px;
			';
		}
		$__finalCompiled .= '
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height']) {
			$__finalCompiled .= '
				height: ' . ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height'] * 0.17) . 'px;
			';
		}
		$__finalCompiled .= '
		}
		
		&.avatar--s
		{
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width']) {
			$__finalCompiled .= '
				width: ' . ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width'] * 0.25) . 'px;
			';
		}
		$__finalCompiled .= '
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height']) {
			$__finalCompiled .= '
				height: ' . ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height'] * 0.25) . 'px;
			';
		}
		$__finalCompiled .= '
		}
		
		&.avatar--m
		{
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width']) {
			$__finalCompiled .= '
				width: ' . ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width'] * 0.5) . 'px;
			';
		}
		$__finalCompiled .= '
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height']) {
			$__finalCompiled .= '
				height: ' . ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height'] * 0.5) . 'px;
			';
		}
		$__finalCompiled .= '
		}

		&.avatar--l
		{
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width']) {
			$__finalCompiled .= '
				width: ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['width']) . 'px;
			';
		}
		$__finalCompiled .= '
			';
		if ($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height']) {
			$__finalCompiled .= '
				height: ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceProductIconMaxDimensions']['height']) . 'px;
			';
		}
		$__finalCompiled .= '
		}
    }
}
';
	}
	$__finalCompiled .= '

.productBody
{
	.productBody--main
	{
		padding: @xf-blockPaddingV @xf-blockPaddingH;
	}

	.productBody--attachments
	{
		margin: .5em 0;

		.attachment
		{
			width: 75px;

			.attachment-name,
			.attachment-details
			{
				display: none;
			}
		}
	}

	.actionBar-set
	{
		margin-top: @xf-messagePadding;
		font-size: @xf-fontSizeSmall;
	}
}

.downloadBody
{
	.downloadBody--main
	{
		padding: @xf-blockPaddingV @xf-blockPaddingH;
	}

	.actionBar-set
	{
		margin-top: @xf-messagePadding;
		font-size: @xf-fontSizeSmall;
	}
}

@media (max-width: @xf-responsiveWide)
{
	.productBody
	{
		display: block;
	}
}

.paddedButtonGroup
{
	> .button
	{
		&:not(:last-child)
		{
			margin-bottom: @xf-paddingMedium;
		}
	}
}';
	return $__finalCompiled;
}
);