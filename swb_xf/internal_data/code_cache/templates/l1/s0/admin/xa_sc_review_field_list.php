<?php
// FROM HASH: 630fceddfbadb803d21676ca2339f8c7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Review fields');
	$__finalCompiled .= '

' . $__templater->includeTemplate('base_custom_field_list', $__vars);
	return $__finalCompiled;
}
);