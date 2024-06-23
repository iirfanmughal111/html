<?php
// FROM HASH: 4e12587a7458f44649e8240c1710251a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@keyframes reveal {
	from {
		transform: scale(0.001);
	}
	to {
		transform: scale(1);
	}
}

@keyframes slide {
	to {
		transform: translateX(1.5em);
	}
}

.product-loader {
	display: none;
	text-align: center;
}

.product-status
{
	display: none;
}

.product-ellipsis
{
	font-size: @xf-fontSizeLarger;
	position: relative;
	width: 4em;
	height: 1em;
	margin: @xf-paddingLarge auto;

	.product-ellipsis--dot
	{
		display: block;
		width: 1em;
		height: 1em;
		border-radius: @xf-borderRadiusLarge;
		background: @xf-textColor;
		position: absolute;
		animation-duration: 0.5s;
		animation-timing-function: ease;
		animation-iteration-count: infinite;

		&:nth-child(1) {
			left: 0;
			animation-name: reveal;
		}

		&:nth-child(2) {
			left: 0;
			animation-name: slide;
		}

		&:nth-child(3) {
			left: 1.5em;
			animation-name: slide;
		}

		&:nth-child(4) {
			left: 3em;
			animation-name: reveal;
			animation-direction: reverse;
		}
	}
}';
	return $__finalCompiled;
}
);