<?php
// FROM HASH: a3fe5010bdd02ea8647f8c2d7b6a0f99
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ############################ RESOURCE LIST ######################

.structItem-resourceTagLine
{
	font-size: @xf-fontSizeSmaller;
	margin-top: @xf-paddingSmall;
}

.structItem-cell.structItem-cell--resourceMeta
{
	width: 210px;

	.structItem-metaItem
	{
		margin-top: 3px;

		&:first-child
		{
			margin-top: 0;
		}
	}
}

.structItem-metaItem--rating
{
	font-size: @xf-fontSizeSmall;
}

.structItem-status
{
	&--team::before { .m-faContent(@fa-var-users-crown); color: @xf-textColorAttention; }
}

@media (max-width: @xf-responsiveWide)
{
	.structItem-cell.structItem-cell--resourceMeta
	{
		width: 200px;
		font-size: @xf-fontSizeSmaller;
	}
}

@media (max-width: @xf-responsiveMedium)
{
	.structItem-cell.structItem-cell--resourceMeta
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
			margin-top: 0;
		}

		.ratingStarsRow
		{
			display: inline;

			.ratingStarsRow-text
			{
				display: none;
			}
		}

		.ratingStars
		{
			font-size: 110%;
			vertical-align: -.2em;
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

// #################################### RESOURCE BODY / VIEW ########################

.resourceBody
{
	display: flex;
}

.resourceBody-main
{
	flex: 1;
	min-width: 0;
	padding: @xf-blockPaddingV @xf-blockPaddingH;
}

.resourceBody-main .bbWrapper
{
	.m-clearFix();
}

.resourceBody-sidebar
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

.resourceBody-fields
{
	&.resourceBody-fields--before
	{
		margin-bottom: @xf-paddingLarge;
		padding-bottom: @xf-paddingMedium;
		border-bottom: @xf-borderSize solid @xf-borderColorLight;
	}

	&.resourceBody-fields--after
	{
		margin-top: @xf-paddingLarge;
		padding-top: @xf-paddingMedium;
		border-top: @xf-borderSize solid @xf-borderColorLight;
	}
}

.resourceBody-attachments
{
	margin: .5em 0;
}

.resourceBody .actionBar-set
{
	margin-top: @xf-messagePadding;
	font-size: @xf-fontSizeSmall;
}

.resourceSidebarGroup
{
	margin-bottom: @xf-elementSpacer;

	&.resourceSidebarGroup--buttons
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

.resourceSidebarGroup-title
{
	margin: 0;
	padding: 0;

	font-size: @xf-fontSizeLarge;
	font-weight: @xf-fontWeightNormal;
	color: @xf-textColorFeature;
	padding-bottom: @xf-paddingMedium;

	.m-hiddenLinks();
}

.resourceSidebarList
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
	.resourceBody
	{
		display: block;
	}

	.resourceBody-sidebar
	{
		width: auto;
		border-left: none;
		border-top: @xf-borderSize solid @xf-borderColor;
	}

	.resourceSidebarGroup
	{
		max-width: 600px;
		margin-left: auto;
		margin-right: auto;
	}
}';
	return $__finalCompiled;
}
);