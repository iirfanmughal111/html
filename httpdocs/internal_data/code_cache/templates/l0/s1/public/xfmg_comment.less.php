<?php
// FROM HASH: 0e06e4a7b7fd6c2aa108283870821828
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.message-attribution
{
	.br-select
	{
		display: none;
	}

	.br-wrapper
	{
		display: inline-block;

		.br-wrapper.br-theme-fontawesome-stars
		{
			height: 20px;
		}
	}
}

' . $__templater->includeTemplate('message.less', $__vars);
	return $__finalCompiled;
}
);