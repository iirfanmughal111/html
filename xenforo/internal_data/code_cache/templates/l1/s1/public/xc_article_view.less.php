<?php
// FROM HASH: 6145edafc245597fd23ad42fd321a534
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@font-face {
	font-family: \'nebularegular\';
	src: url(\'/styles/new-theme/fonts/nebula-regular-webfont.woff2\') format(\'woff2\'),
		url(\'/styles/new-theme/fonts/nebula-regular-webfont.woff\') format(\'woff\');
	font-weight: normal;
	font-style: normal;

}

@font-face {
	font-family: \'Liberation Sans\';
	src: url(\'/styles/new-theme/fonts/LiberationSans-Bold.woff2\') format(\'woff2\'),
		url(\'/styles/new-theme/fonts/LiberationSans-Bold.woff\') format(\'woff\');
	font-weight: bold;
	font-style: normal;
	font-display: swap;
}

.banner-slider {
	margin-top: 60px !important;
	margin-bottom: 60px;
	height: 460px;

}

.banner-slider img {
	position: absolute;
	height: 460px;
	width: 100%;
	left: 0;
	object-fit: cover;
	object-position: right;
}

.p-title {
	margin-top: 120px;

}

.p-title-value {
	font-family: \'Liberation Sans\';
	font-weight: bold;
	font-size: 40px;
	line-height: 46px;
	color: #fff;
	margin-bottom: 24px;
}

.p-title-value .label {
	display: block;
	width: fit-content;
	text-align: center;
	margin: auto;
	margin-bottom: auto;
	background: rgba(0, 157, 212, 1);
	border-radius: 0;
	font-size: 14px;
	line-height: 16.11px;
	text-transform: uppercase;
	margin-bottom: 12px;
	font-family: \'nebularegular\';
}

.block--messages .message,
.block--messages .block-row {

	background: #1A1A1A;
	border: none;
	border-radius: 0;
	padding: 8px;
}

.block--messages .message,
.block--messages .block-row {

	color: #fff;
	font-size: 14px;
}

.message-attribution {
	border-bottom: none;
	margin-bottom: 5px;
	color: rgba(118, 118, 118, 1);

}

.message-userArrow {
	display: none;
}

.message-cell.message-cell--user {
	border: 1px solid rgba(168, 168, 168, 0.25) !important;
	border-radius: 0 !important;
	padding: 0px;
}

.message-cell.message-cell--user .message-name,
.message-cell.message-cell--user .message-userTitle {
	padding: 8px;
	background: linear-gradient(0deg, #1E1E1E, #1E1E1E),
		linear-gradient(0deg, rgba(168, 168, 168, 0.25), rgba(168, 168, 168, 0.25));

}

.message-cell.message-cell--user .message-name {

	padding-bottom: 0px;
}

.message-cell.message-cell--user .message-userTitle {
	padding-top: 0px;
	margin-bottom: 8px;
}

.message-avatar {
	padding: 12px 16px;

}

.message-footer a {
	color: rgba(118, 118, 118, 1);


}

.first-post-article .message-cell.message-cell--user {
	display: none;

}

.first-post-article .message-attribution {
	display: none;


}

.first-post-article {
	background: transparent !important;
	padding: 0px !important;
}

.first-post-article .message-inner {

	max-width: 680px;
	width: 100%;
	margin: auto;


}

.first-post-article .message-cell {
	padding: 0px !important;

}

.p-body-header {

	text-align: center;
	max-width: 900px;
	margin: auto;
}

.first-post-article h3 {
	font-family: \'Liberation Sans\';
	font-weight: bold;
	font-size: 24px;
	line-height: 33px;
	color: #fff;
	margin-bottom: 30px;

}

.first-post-article h4 {
	font-family: \'Open Sans\', sans-serif;
	font-size: 16px;
	font-weight: 700;
	line-height: 28px;
	margin-top: 30px;
	margin-bottom: 30px;
}

.first-post-article p,
.message-body {

	font-family: \'Open Sans\', sans-serif;
	font-size: 16px;
	font-weight: 400;
}

.first-post-article::after {
	content: \'discussion\';
	font-family: \'nebularegular\';
	font-size: 16px;
	color: #767676;
	line-height: 25px;
	border-bottom: 1px solid rgba(168, 168, 168, 0.25);
	width: 100%;
	display: block;
	margin-bottom: 20px;
	margin-top: 80px;
	text-transform: uppercase;
}

@media (max-width:768px) {
	.p-title-value {
		font-size: 24px;
		line-height: 26px;
	}

	.banner-slider {
		height: auto;
	}

	.banner-slider img {
		position: relative;
		height: auto;
		width: 100%;
		left: 0;
		object-fit: cover;
	}

	.first-post-article h3 {

		font-size: 20px;


	}

	.bbImageWrapper {
		display: block;
		max-width: 100%;
		text-align: center;
		margin: auto;
	}

	.first-post-article {
		padding: 10px !important;
	}';
	return $__finalCompiled;
}
);