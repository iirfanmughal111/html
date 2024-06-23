<?php
// FROM HASH: 3fa370f4f0c995be8a5c3a59f2e980fb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Albums');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['type'] = $__templater->preEscaped('albums');
	$__finalCompiled .= $__templater->includeTemplate('xfmg_base_batch_update_list', $__compilerTemp1);
	return $__finalCompiled;
}
);