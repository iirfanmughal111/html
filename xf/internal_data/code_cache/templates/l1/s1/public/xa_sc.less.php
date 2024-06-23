<?php
// FROM HASH: 663aea91fb88fa8a705bac9649f44829
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ############################ MISC CSS ######################

.sc-muted
{
	color: @xf-textColorMuted;
}

.structItem--item.awaiting
{
	background-color: @xf-inlineModHighlightColor;
}

.scSetCoverImage-attachments .attachment-icon.attachment-icon--img img 
{
	max-height: 100px;
	max-width: 100%;
}

.scSetCoverImage-attachments .avatar
{
	border-radius: 0;
}

.scHeader-figure.contentRow-figure
{
	img
	{
		max-height: 300px;
		max-width: 300px;
	}
}

.scCoverImage.scDisplayAboveItemNarrow
{
	display: none;
}

@media (max-width: @xf-responsiveWide)
{
	.scHeader-figure.contentRow-figure
	{
		img
		{
			max-height: 200px;
			max-width: 200px;
		}
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.scHeader-figure.contentRow-figure
	{
		img
		{
			max-height: 100px;
			max-width: 100px;
		}
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.scCoverImage.scDisplayAboveItemNarrow
	{
		display: block;
	}
}


// ############################ CAROUSEL Full/Simple CSS ######################

.carousel--scFeaturedItems .contentRow-figure
{
	width: 175px;
	margin-left:10px;
	float:right;
}

.carousel--scFeaturedSeries .contentRow-figure
{
	width: 150px;
	margin-left:10px;
	float:right;	
}

.carousel--scFeaturedItems .carousel-item
{
		&.is-unread
		{
			.contentRow-title
			{
				font-weight: @xf-fontWeightHeavy;
			}
		}
}

.carousel--scFeaturedItems
{
	.contentRow-title
	{
		font-size: @xf-fontSizeLarger;
	}	
}

.carousel--scCarousleSimple,
.carousel--scFeaturedItemsSimple
{
	.contentRow-figure
	{
		width: 100%;
		padding-top: 5px;	
		padding-bottom: 5px;
		margin: 0 auto;
	}

	.contentRow-title
	{
		font-size: @xf-fontSizeNormal;
	}
	
	.carousel-body
	{
		height: 100% !important;
	}	
}

.carousel--scFeaturedItems .contentRow-scCategory,
.carousel--scFeaturedItemsSimple .contentRow-scCategory,
.carousel--scFeaturedSeries .contentRow-scSeries
{
	font-size: @xf-fontSizeSmaller;
	font-style: italic;
	color: @xf-textColorMuted;
	padding-bottom: 2px;
}

.carousel--scFeaturedItems .contentRow-lesser,
.carousel--scFeaturedItemsSimple .contentRow-lesser,
.carousel--scFeaturedSeries .contentRow-lesser
{
	padding: 5px 0;
}

.carousel--scFeaturedItems .contentRow-scLatestItem,
.carousel--scFeaturedItemsSimple .contentRow-scLatestItem,
.carousel--scFeaturedSeries .contentRow-scLatestItem
{
	font-size: @xf-fontSizeSmaller;
	font-style: italic;
	color: @xf-textColorMuted;
	padding-top: 5px;
}

.carousel--scFeaturedItems .contentRow-itemLocation,
.carousel--scFeaturedItemsSimple .contentRow-itemLocation
{
	font-size: @xf-fontSizeSmaller;
	color: @xf-textColorDimmed;
	margin-top: @xf-paddingSmall;
}

.carousel--scFeaturedItems .contentRow-itemLocationIcon,
.carousel--scFeaturedItemsSimple .contentRow-itemLocationIcon
{
	font-size: @xf-fontSizeSmaller;
	padding-left: @xf-paddingSmall;
}

.carousel--scFeaturedItems .contentRow-itemCustomFields,
.carousel--scFeaturedItemsSimple .contentRow-itemCustomFields
{
	font-size: @xf-fontSizeSmaller;
	margin-top: @xf-paddingSmall;
	margin-bottom: @xf-paddingSmall;
}

.carousel--scFeaturedItems .contentRow-itemMeta,
.carousel--scFeaturedItemsSimple .contentRow-itemMeta
{
	padding-top: @xf-paddingSmall
}

@media (max-width: @xf-responsiveMedium)
{
	.carousel--scFeaturedItems .contentRow-figure,
	.carousel--scFeaturedSeries .contentRow-figure	
	{
		width: 150px;
	}

	.carousel--scFeaturedItems
	{
		.contentRow-title
		{
			font-size: @xf-fontSizeLarge;
		}	
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.carousel--scFeaturedItems .contentRow-figure,
	.carousel--scFeaturedSeries .contentRow-figure		
	{
		width: 125px;
	}
}

@media (max-width: 374px)
{
	.carousel--scFeaturedItems .contentRow-figure,
	.carousel--scFeaturedSeries .contentRow-figure		
	{
		width: 100px;
	}
}


// ############################ ITEM LIST (non layout specific) CSS ######################

.ratingStars--scAuthorRating .ratingStars-star.ratingStars-star--full::before,
.ratingStars--scAuthorRating .ratingStars-star.ratingStars-star--half::after
{
	color: #176093;
}

.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconScCoverImage,
.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconScSeriesCoverImage    
{
    width: 175px;
}

.structItem-cell--iconScCoverImage .structItem-iconContainer .avatar,
.structItem-cell--iconScSeriesCoverImage .structItem-iconContainer .avatar
{
	width: 96px;
	height: 96px;
	font-size: 57.6px;
}

@media (max-width: @xf-responsiveMedium)
{
	.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconScCoverImage,
	.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconScSeriesCoverImage   
	{
    		width: 150px;		
	}
}

@media (max-width: @xf-responsiveNarrow)
{
	.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconScCoverImage,
	.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconScSeriesCoverImage   
	{
    		width: 125px;		
	}
}

@media (max-width: 374px) 
{
	.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconScCoverImage,
	.structItem-cell.structItem-cell--icon.structItem-cell--iconExpanded.structItem-cell--iconScSeriesCoverImage 
	{
		width: 100px;
	}

	.structItem-cell--iconScCoverImage .structItem-iconContainer .avatar,
	.structItem-cell--iconScSeriesCoverImage .structItem-iconContainer .avatar
	{
		width: 48px;
		height: 48px;
		font-size: 28.8px;
	}
}


// #################################### LIST VIEW LAYOUT SPECIFIC CSS ########################

.structItem-cell.structItem-cell--listViewLayout
{
	display: block;
	padding-bottom: .2em;
}

.structItem-cell.structItem-cell--listViewLayout .structItem-minor 
{
	float: none !important;
}

.structItem-cell.structItem-cell--listViewLayout
{
	.structItem-title
	{
		font-size: @xf-fontSizeLarger;
		padding-bottom: @xf-paddingSmall;
	}	
}

.structItem-itemCategoryTitleHeader
{
	font-size: @xf-fontSizeSmaller;
	font-style: italic;
	color: @xf-textColorMuted;
}

.structItem-LatestItemTitleFooter
{
	padding-top: @xf-paddingSmall;
	font-size: @xf-fontSizeSmaller;
	font-style: italic;
	color: @xf-textColorMuted;
}

.structItem-itemLocation
{
	font-size: @xf-fontSizeSmaller;
	color: @xf-textColorDimmed;
	padding-bottom: @xf-paddingSmall;

	.structItem-itemLocationIcon
	{
		font-size: @xf-fontSizeSmaller;
		padding-left: @xf-paddingSmall;
	}
}

.structItem-itemDescription
{
	font-size: @xf-fontSizeSmaller;
	padding-top: @xf-paddingSmall;
	padding-bottom: @xf-paddingSmall
}

.structItem-itemCustomFields
{
	font-size: @xf-fontSizeSmaller;
	padding-bottom: @xf-paddingSmall;
}

.structItem-listViewMeta
{
	padding-bottom: @xf-paddingSmall;
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

	.structItem-metaItem--author > dt,
	.structItem-metaItem--publishdate > dt,
	.structItem-metaItem--createdate > dt
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

@media (max-width: @xf-responsiveMedium)
{
	.structItem-cell.structItem-cell--listViewLayout
	{
		.structItem-title
		{
			font-size: @xf-fontSizeLarge;
		}	
	}
}


// #################################### ITEM VIEW LAYOUT SPECIFIC CSS ########################

.message--itemView
{
	&.is-unread
	{
		.message-attribution-scItemTitle .contentRow-title
		{
			font-weight: @xf-fontWeightHeavy;
		}
	}	
	&.is-moderated
	{
		background: @xf-contentHighlightBg;
	}

	&.is-deleted
	{
		opacity: .7;

		.message-attribution-scItemTitle .contentRow-title
		{
			text-decoration: line-through;
		}
	}
}

.message--itemView .message-cell.message-cell--main
{
	padding-left: @xf-messagePadding;
}

.message--itemView .message-attribution-scCategoryTitle
{
	font-style: italic;
}

.message--itemView .message-attribution-scItemTitle
{
	border-bottom: none;
	
	.contentRow-title
	{
		font-size: @xf-fontSizeLarger;

		.label 
		{
    			font-weight: @xf-fontWeightNormal;
		}
	}	
}

.message--itemView .message-attribution-scItemLocation
{
	border-bottom: none;
	font-size: @xf-fontSizeSmall;
	color: @xf-textColorDimmed;

	.message-attribution-scItemLocationIcon
	{
		padding-left: @xf-paddingSmall;
		font-size: @xf-fontSizeSmall;
	}
}

.message--itemView .message-attribution-scItemMeta
{
	border-bottom: none;
}

@media (max-width: @xf-responsiveMedium)
{
	.message--itemView .message-attribution-scItemTitle .contentRow-title
	{
		font-size: @xf-fontSizeLarge;
	}
}


// #################################### ITEM BODY / VIEW SPECIFIC CSS ########################

.itemBody
{
	display: flex;
}

.itemBody-main
{
	flex: 1;
	min-width: 0;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
	
	';
	if ($__templater->func('property', array('xbMessageLink', ), false)) {
		$__finalCompiled .= '
		.bbWrapper a { .xf-xbMessageLink(); &:hover { .xf-xbMessageLinkHover(); } }
	';
	}
	$__finalCompiled .= '
}

.itemBody-sidebar
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

.itemBody-description
{
	margin-bottom: @xf-paddingLarge;
	padding-bottom: @xf-paddingMedium;
	font-weight: @xf-fontWeightHeavy;
	font-style: italic;
	color: @xf-textColorMuted;
}

.itemBody-fields
{
	&.itemBody-fields--header
	{
		margin-top: @xf-paddingLarge;
		padding-top: @xf-paddingMedium;
	}

	&.itemBody-fields--before
	{
		margin-bottom: @xf-paddingLarge;
		padding-bottom: @xf-paddingMedium;
		border-bottom: @xf-borderSize solid @xf-borderColorLight;
	}

	&.itemBody-fields--after
	{
		margin-top: @xf-paddingLarge;
		padding-top: @xf-paddingMedium;
		border-top: @xf-borderSize solid @xf-borderColorLight;
	}
}

.itemBody-attachments
{
	margin: .5em 0;
}

.itemBody .actionBar-set
{
	margin-top: @xf-messagePadding;
	font-size: @xf-fontSizeSmall;
}

.itemSidebarGroup
{
	margin-bottom: @xf-elementSpacer;

	&.itemSidebarGroup--buttons
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

.itemSidebarGroup-title
{
	margin: 0;
	padding: 0;

	font-size: @xf-fontSizeLarge;
	font-weight: @xf-fontWeightNormal;
	color: @xf-textColorFeature;
	padding-bottom: @xf-paddingMedium;

	.m-hiddenLinks();
}

.itemSidebarList
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

.scBusinessHours .additionalHours > dt
{
	display: none;
}

@media (max-width: @xf-responsiveWide)
{
	.itemBody
	{
		display: block;
	}

	.itemBody-sidebar
	{
		width: auto;
		border-left: none;
		border-top: @xf-borderSize solid @xf-borderColor;
	}

	.itemSidebarGroup
	{
		max-width: 600px;
		margin-left: auto;
		margin-right: auto;
	}
}


// #################################### ITEM PAGE(S) LIST SPECIFIC CSS ########################

.message-attribution-scPageTitle
{
	border-bottom: none !important;
}

.message-attribution-scPageTitle .contentRow-title
{
	font-size: @xf-fontSizeLarge;
	font-weight: @xf-fontWeightHeavy
}

.message-attribution-scPageMeta
{
	border-bottom: none !important;
}

.message-attribution-scPageStats
{
	padding-top: 5px;
	border-bottom: none !important;
}


@media (max-width: @xf-responsiveNarrow)
{
	.message-attribution-scPageTitle .contentRow-title
	{
		font-size: @xf-fontSizeNormal;
	}
}


// #################################### SERIES LIST SPECIFIC CSS ########################

.structItem-status
{
	&--community::before { .m-faContent(@fa-var-users, 1.04em); color: @xf-textColorFeature; }	
}

.structItem-seriesTitleHeader
{
	font-size: @xf-fontSizeSmall;
}

.actionBarSeries
{
	font-size: @xf-fontSizeSmall;	
}


// #################################### SERIES BODY / VIEW SPECIFIC CSS ########################

.seriesBody
{
	display: flex;
}

.seriesBody-main
{
	flex: 1;
	min-width: 0;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
	
	';
	if ($__templater->func('property', array('xbMessageLink', ), false)) {
		$__finalCompiled .= '
		.bbWrapper a { .xf-xbMessageLink(); &:hover { .xf-xbMessageLinkHover(); } }
	';
	}
	$__finalCompiled .= '	
}

.seriesBody-sidebar
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

.seriesBody-attachments
{
	margin: .5em 0;
}

.seriesBody .actionBar-set
{
	margin-top: @xf-messagePadding;
	font-size: @xf-fontSizeSmall;
}

.seriesSidebarGroup
{
	margin-bottom: @xf-elementSpacer;

	&.seriesSidebarGroup--buttons
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

.seriesSidebarGroup-title
{
	margin: 0;
	padding: 0;

	font-size: @xf-fontSizeLarge;
	font-weight: @xf-fontWeightNormal;
	color: @xf-textColorFeature;
	padding-bottom: @xf-paddingMedium;

	.m-hiddenLinks();
}

.seriesSidebarList
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
	.seriesBody
	{
		display: block;
	}

	.seriesBody-sidebar
	{
		width: auto;
		border-left: none;
		border-top: @xf-borderSize solid @xf-borderColor;
	}

	.seriesSidebarGroup
	{
		max-width: 600px;
		margin-left: auto;
		margin-right: auto;
	}
}


// #################################### MISC REVIEW CSS ########################

.review-title
{
	font-weight: 600;
	margin-top: @xf-paddingSmall;
	margin-bottom: @xf-paddingSmall;
}

// #################################### COVER IMAGE CONTAINER FOR ITEM VIEW LAYOUT AND ITEM VIEW ####################################

.scCoverImage
{
	position: relative;
	margin-bottom: @xf-elementSpacer;

	&.has-caption
	{
		margin-bottom: 0px;
	}
}

.message--itemView .scCoverImage
{
	margin-top: @xf-paddingLarge;
}

.scCoverImage-container
{
	display: flex;
	justify-content: center;
	align-items: center;

	border: 1px solid transparent;

	min-height: 50px;

	img
	{
		max-width: 100%;
		max-height: 80vh;
	}
	
	.scCoverImage-container-image
	{
		position: relative;
	}	
}

.scCoverImage-caption
{
	font-style: italic;
	color: @xf-textColorDimmed;
	margin-bottom: @xf-elementSpacer;
}


// #################################### CATEGORY MAP SPECIFIC CSS ########################

.scMapContainer {}

	.scMapContainer.top 
	{
		padding-bottom: @xf-paddingLarge;
	}
	
	.scMapContainer.bottom {}

.scMapInfoWindow 
{
	width: 400px;
}
	
.scMapInfoWindowItem
{
	font-size: @xf-fontSizeSmall;
	font-weight: @xf-fontWeightNormal;
	word-wrap: normal;
}

	.scMapInfoWindowItem .listBlock
	{
		vertical-align: top;
	}

	.scMapInfoWindowItem .listBlockInner
	{
		padding-right: 5px;		
	}

	.scMapInfoWindowItem .listBlockInnerImage 
	{
		padding: 0px;
	}

	.scMapInfoWindowItem .itemCoverImage
	{
		width: 20%;
		min-width: 100px;
		max-width: 100px;
	}

		.scMapInfoWindowItem .itemCoverImage.left
		{
			float: left;
			margin-right: 10px;
		}

		.scMapInfoWindowItem .itemCoverImage .thumbImage
		{
			width: 100%;
			vertical-align: middle;
		}

		.scMapInfoWindowItem .itemCoverImage .listBlockInner
		{
			padding-right: 0px;
		}

	.scMapInfoWindowItem .title 
	{
		font-size: @xf-fontSizeLarge;
		font-weight:  @xf-fontWeightHeavy;
		padding: 5px 0;	 
	}
	
	.scMapInfoWindowItem .address, 
	.scMapInfoWindowItem .authorRating, 
	.scMapInfoWindowItem .userRating
	{
		padding: 2px 0;
	}

	.scMapInfoWindowItem .sc-muted
	{
		color: @xf-textColorMuted;
	}		

@media (max-width: @xf-responsiveMedium)
{
	.scMapInfoWindow 
	{
		width: 100%;
	}

	.scMapInfoWindowItem
	{
		font-size: @xf-fontSizeSmaller;
	}

	.scMapInfoWindow .scMapInfoWindowItem .itemCoverImage
	{
		width: 10%;
		min-width: 75px;
		max-width: 75px;
	}	

	.scMapInfoWindow .scMapInfoWindowItem .title
	{
		font-size: @xf-fontSizeNormal;
	}			
}

@media (max-width: @xf-responsiveNarrow)
{
	.scMapInfoWindow 
	{
		width: 100%;
	}

	.scMapInfoWindowItem
	{
		font-size: @xf-fontSizeSmallest;
	}

	.scMapInfoWindow .scMapInfoWindowItem .itemCoverImage
	{
		width: 10%;
		min-width: 50px;
		max-width: 50px;
	}	

	.scMapInfoWindow .scMapInfoWindowItem .title
	{
		font-size: @xf-fontSizeSmall;
	}				
}';
	return $__finalCompiled;
}
);