<?php
// FROM HASH: a4dd1e2bc982b2001d92549755a8c614
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ############################ Escrow List View ######################

.structItem-cell.structItem-cell--listingMeta
{
	width: 250px;
}
.label--counter{
	font-size:13px;
}
.auction-category{
	font-size: 12px;
    color: #8c8c8c;
}
#counter-before:after{
	content:none;
}
.structItem-metaItem--ratingf
{
	font-size: @xf-fontSizeSmall;
}
.label--counter-single{
	font-size:25px;
}
@media (max-width: @xf-responsiveWide)
{
	.structItem-cell.structItem-cell--listingMeta
	{
		width: 190px;
		font-size: @xf-fontSizeSmaller;
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.structItem-cell.structItem-cell--listingMeta
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

// #################################### Escrow LISTING BODY / VIEW ########################

.listingBody
{
	display: flex;
}

.listingBody-main
{
	flex: 1;
	min-width: 0;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
}

.listingBody-sidebar
{
	flex: 0 0 auto;
	width: 250px;
	.xf-contentAltBase();
	border-left: @xf-borderSize solid @xf-borderColor;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
	font-size: @xf-fontSizeSmall;

	> :first-child
	{
		margin-top: 0;
	}

	> :last-child
	{
		margin-bottom: 0;
	}
}

.listingBody-fields
{
	&.listingBody-fields--before
	{
		margin-bottom: @xf-paddingLarge;
		padding-bottom: @xf-paddingMedium;
		border-bottom: @xf-borderSize solid @xf-borderColorLight;
	}

	&.listingBody-fields--after
	{
		margin-top: @xf-paddingLarge;
		padding-top: @xf-paddingMedium;
		border-top: @xf-borderSize solid @xf-borderColorLight;
	}
}

.listingBody-attachments
{
	margin: .5em 0;
}

.listingBody .actionBar-set
{
	margin-top: @xf-messagePadding;
	font-size: @xf-fontSizeSmall;
}

.listingSidebarGroup
{
	margin-bottom: @xf-elementSpacer;

	&.listingSidebarGroup--buttons
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

.listingSidebarGroup-title
{
	margin: 0;
	padding: 0;

	font-size: @xf-fontSizeLarge;
	font-weight: @xf-fontWeightNormal;
	color: @xf-textColorFeature;
	padding-bottom: @xf-paddingMedium;

	.m-hiddenLinks();
}

.listingSidebarList
{
	.m-listPlain();

	> li
	{
		padding: @xf-paddingSmall 0;

		&:first-child
		{
			padding-top: 0;
		}

		&:last-child
		{
			padding-bottom: 0;
		}
	}
}

@media (max-width: @xf-responsiveWide)
{
	.listingBody
	{
		display: block;
	}

	.listingBody-sidebar
	{
		width: auto;
		border-left: none;
		border-top: @xf-borderSize solid @xf-borderColor;
	}

	.listingSidebarGroup
	{
		max-width: 600px;
		margin-left: auto;
		margin-right: auto;
	}
}


.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconListingCoverImage
{
	width: 110px;
}

.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconListingCoverImageGrid
{
	min-width: 300px !important;
}

.structItem-cell--iconListingCoverImage .structItem-iconContainer .avatar
{
	width: 96px;
	height: 96px;
	font-size: 57.6px;
	border-radius:0;
}';
	return $__finalCompiled;
}
);