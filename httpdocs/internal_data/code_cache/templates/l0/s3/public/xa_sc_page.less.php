<?php
// FROM HASH: 0052cb9159b1f718797cef8cfb3b5899
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.sc-page 
{
	.message-responseRow.sc-page.is-item-owner
	{
		background-color: @xf-contentHighlightBg; 
	}
}

' . $__templater->includeTemplate('message.less', $__vars);
	return $__finalCompiled;
}
);