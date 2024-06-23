<?php
// FROM HASH: 7ca4404466eb1399cd901427c2c9e0db
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('License fields');
	$__finalCompiled .= '

' . $__templater->includeTemplate('base_custom_field_list', $__vars);
	return $__finalCompiled;
}
);