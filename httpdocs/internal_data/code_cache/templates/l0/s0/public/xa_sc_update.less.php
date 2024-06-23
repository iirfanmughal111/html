<?php
// FROM HASH: 705662854318376ff59a8ffd9c5daedc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.sc-update .block-textHeaderSc
{
    font-weight: @xf-fontWeightHeavy;
    font-size: @xf-fontSizeLarge;
    color: @xf-linkColor;
}

.sc-update .sc-update-fields
{
	&.sc-update-fields--above
	{
		padding-top: @xf-paddingMedium;
		padding-bottom: @xf-paddingMedium;
		border-bottom: @xf-borderSize solid @xf-borderColorLight;
	}

	&.sc-update-fields--below
	{
		margin-top: @xf-paddingLarge;
		padding-top: @xf-paddingMedium;
		padding-bottom: @xf-paddingMedium;
		border-top: @xf-borderSize solid @xf-borderColorLight;
	}
}

' . $__templater->includeTemplate('message.less', $__vars);
	return $__finalCompiled;
}
);