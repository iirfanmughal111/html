<?php
// FROM HASH: a33f4b3351dc92132766695ee4a7b6be
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@postBitWidth: (@xf-messageUserBlockWidth) + 2 * (@xf-messagePadding);
@postBitWidthSmall: @_messageSimple-userColumnWidth + 2 * @xf-messagePaddingSmall;
@postBitAdjusted: @postBitWidth - @postBitWidthSmall;
@postbitSpacerDesktop: @xf-messagePadding + @avatar-xs;

//Start fix for country flags addon
/*.block--messages .message .thpostcomments_commentsContainer{
	.userflag-m {
		position: absolute;
		top: calc(96px - 75px);
		left: calc(96px - 110px);
		width: 25px;
		height: 15px;
		z-index: 2;
	}
}*/
//End fix for country flags addon

.message--post {
	.message-attribution--plain {display: none;}

	&.thpostcomments_message--condensed {
		.message-attribution--plain {display: block;}

		.message-attribution--condensed {
			display: flex;

			.avatar {
				display: none !important;
			}
		}

		.message-attribution-user .avatar {
			display: inline-block;
			.m-avatarSize((@xf-fontSizeNormal) * (@xf-lineHeightDefault));
		}

		.message-attribution:not(.message-attribution--condensed) {display: none;}
	}
}

.message--depth0 {
	> .message-inner {
		border-bottom: 1px solid @xf-borderColor;
		margin-bottom: @xf-messagePadding;

		.message-attribution--condensed {
			display: none !important;
		}
	}
}

.block--messages .message {
	&:not(.thpostcomments_message--expanded, .message--tv, .message--movie) .message {
		display: none;
	}

	.thpostcomments_commentsContainer {
		border-top: 1px solid @xf-borderColor;
		
		//Start fix for country flags addon	
		.userflag-m {
				display: none;
		}
		//End fix for country flags addon
		
		.message {
			box-shadow: none;
			margin-left: 20px;
			border: none;
			position: relative;
			margin-top: 20px;

			.message-userArrow {display: none;}

			.thpostcomments_commentLink {
				margin-bottom: @xf-messagePaddingSmall;
				display: block;
				float: left;
			}

			.message-lastEdit {
				display: none;
			}

			.message-inner  {
				display: flex;
			}

			.message-cell--main {
				padding-top: 0;
				background: none;
				padding-left: @xf-messagePadding;
			}

			&.message--depth1 {
				margin-left: 10px;
			}

			&:not(:last-child) .thpostcomments_exandLine {
				border-left: 1px dashed @xf-borderColor;
				width: 1px;
				display: inline-block;
				position: absolute;
				top: 10px + @avatar-xxs;
				bottom: 0;
				left: @avatar-xxs / 2;
			}

			.message-signature {
				display: none;
			}

			.message-attribution-user {
				display: flex;
				align-items: center;
				margin-right: 4px;

				.avatar {
					display: inline-block;
					margin-right: 8px;
				}
			}

			.message-attribution .listInline {
				display: flex;
				align-items: center;
				flex-wrap: wrap;
			}

			@media (max-width: @xf-responsiveEdgeSpacerRemoval) {
				margin-right: @xf-paddingMedium;
			}

			.message-cell--user {
				padding: 0;
				background: none;
				flex-basis: auto;
				border: none;

				.avatar {
					.m-avatarSize(@avatar-xxs) !important;
				}

				.message-user > *:not(.message-avatar) {
					display: none;
				}
			}

			@media (min-width: @xf-responsiveMedium) {
				margin-left: @postbitSpacerDesktop;

				&.message--depth1 {
					margin-left: (@postbitSpacerDesktop) / 2;
				}

				&:not(:last-child) .thpostcomments_exandLine {
					left: @avatar-xs / 2;
					top: 20px + @avatar-xs;
				}

				.message-cell--user .avatar {
					.m-avatarSize(@avatar-xs) !important;
				}
			}
		}
	}
}';
	return $__finalCompiled;
}
);