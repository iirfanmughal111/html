<?php
// FROM HASH: 45ed03b1697569b56e2ceb27073f3a31
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Product prefixes');
	$__finalCompiled .= '

' . $__templater->includeTemplate('base_prefix_list', $__vars);
	return $__finalCompiled;
}
);