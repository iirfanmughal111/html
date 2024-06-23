<?php
// FROM HASH: 5d608950f9a7a251a99d757c87a8e60e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.productCostList
{
  	/*border-top: @xf-borderSize solid @xf-borderColorLight;*/

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
			.inputGroup-text
			{
				&.inputGroup-text--fixedSmaller
				{
					width: 100px;
				}
			}
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