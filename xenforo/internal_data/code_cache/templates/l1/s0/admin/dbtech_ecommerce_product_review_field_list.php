<?php
// FROM HASH: 2604834e425bda8588b5abab98745d24
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Product review fields');
	$__finalCompiled .= '

' . $__templater->includeTemplate('base_custom_field_list', $__vars);
	return $__finalCompiled;
}
);