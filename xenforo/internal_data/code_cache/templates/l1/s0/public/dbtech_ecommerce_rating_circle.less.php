<?php
// FROM HASH: 4e2dc12aee9dc3400b1e3f054f217079
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '/* Set initial vars */
@_circleWidth: (@xf-sidebarWidth - (@xf-sidebarSpacer * 2));
@_halfWidth: (@_circleWidth) * .5;
@_barWidth: @xf-dbtechEcommerceProductRatingCircleBarWidth;
@_offset: (@_barWidth) * 2;
@_barColor: @xf-dbtechEcommerceProductRatingCircleBarColor;

.rating-circle {
	font-size: @xf-fontSizeLarge;
	margin: @xf-elementSpacer auto;
	background-color: @xf-dbtechEcommerceProductRatingCircleBgColor;
	position: relative;
	width: @_circleWidth;
	height: @_circleWidth;
	line-height: @_circleWidth;
	border-radius: 50%;

	&:after {
		content: " ";
		position: absolute;
		top: @_barWidth;
		left: @_barWidth;
		border: none;
		background-color: @xf-contentBg; /* Should matche bg of block container */
		text-align: center;
		display: block;
		width: (@_circleWidth - @_offset);
		height: (@_circleWidth - @_offset);
		border-radius: 50%;
	}
	.ratingCircleRow {
		position: absolute;
		width: 100%;
		text-align: center;
		z-index: 20;
		display: flex;
		height: 200px;
		align-items: center;
		justify-items: center;
	}
	.ratingCircleRow-inner {
		display: flex;
		flex-direction: column;
		width: 90%;
		line-height: initial;
		height: auto;
		margin: auto;
		flex: 1;

		/* Resets from default alignment */
		.ratingStars-star {
			float: none;
		}
		span.ratingPercent {
			font-size: @xf-fontSizeLarger;
			font-weight: bold;
		}
	}
	.leftCover {
		position: absolute;
		width: @_circleWidth;
		height: @_circleWidth;
		clip: rect(0, @_circleWidth, @_circleWidth, @_halfWidth);
		border-radius: 50%;
	}
	&.overHalf .initialBar {
		position: absolute;
		background-color: @_barColor;
		width: @_circleWidth;
		height: @_circleWidth;
		clip: rect(0, @_circleWidth, @_circleWidth, @_halfWidth);
		border-radius: 50%;
	}
	&:not(.overHalf) .initialBar {
		display: none;
	}
	&.overHalf .leftCover {
		clip: rect(auto,auto,auto,auto);
	}
	.valueBar {
		position: absolute;
		width: @_circleWidth;
		height: @_circleWidth;
		border: @_barWidth solid @_barColor;
		clip: rect(0, @_halfWidth, @_circleWidth, 0);
		border-radius: 50%;
	}
	.rating-0 .valueBar {
		display: none;
	}
}

/* Set ratings */
.generate-ratings(100);

.generate-ratings(@n, @i: 1) when (@i =< @n) {
	.rating-@{i} {
		.valueBar {
			transform: rotate(ceil((@i * 360deg / @n)));
		}
	}
	.generate-ratings(@n, (@i + 1));
}';
	return $__finalCompiled;
}
);