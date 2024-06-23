<?php
// FROM HASH: b6e0125a4eec561f35a71bcd603429d7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.sc-review .sc-review-fields
{
	&.sc-review-fields--top
	{
		padding-top: @xf-paddingMedium;
		padding-bottom: @xf-paddingMedium;
	}

	&.sc-review-fields--middle
	{
		margin-bottom: @xf-paddingLarge;
		padding-bottom: @xf-paddingMedium;
	}

	&.sc-review-fields--bottom
	{
		margin-top: @xf-paddingLarge;
		padding-top: @xf-paddingMedium;
	}
}

.sc-review .message-body.sc-pros-container,
.sc-review .message-body.sc-cons-container
{
	margin-bottom: 10px;
}

.sc-review .sc-pros-container
{
    color: green;
}

.sc-review .sc-cons-container
{
    color: #B40000;	
}

.sc-review .pros-header,
.sc-review .cons-header
{
	font-weight: bold;
}

' . $__templater->includeTemplate('message.less', $__vars);
	return $__finalCompiled;
}
);