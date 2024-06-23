<?php
// FROM HASH: fa45764c5cccbc5f20f6995f1cfa49af
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.productCostList
{
	.m-listPlain();
	.xf-contentAltBase();
	border: @xf-borderSize solid @xf-borderColor;
	border-radius: @xf-borderRadiusSmall;
	margin-bottom: @xf-paddingLarge;
	padding-top: @xf-paddingMedium;

	&.productCostList--spaced
	{
		margin-top: @xf-paddingLarge;
	}

	> li
	{
		border-bottom: @xf-borderSize solid @xf-borderColorLight;
		padding: @xf-paddingMedium;

		&:last-child
		{
			border-bottom: none;
		}

		.inputGroup
		{
			.input--inline
			{
				&.input--currency
				{
					height:35px;
					position:relative;
					top:27px;
				}
			}
		}
	}
}';
	return $__finalCompiled;
}
);