<?php
// FROM HASH: 2f8835d34cbd1e59e05ed7f792b6366f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Resource fields');
	$__finalCompiled .= '

' . $__templater->includeTemplate('base_custom_field_list', $__vars);
	return $__finalCompiled;
}
);