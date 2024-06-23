<?php
// FROM HASH: bbc24f039504ddef19aff7884eb07b39
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Media items');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['type'] = $__templater->preEscaped('media');
	$__finalCompiled .= $__templater->includeTemplate('xfmg_base_batch_update_list', $__compilerTemp1);
	return $__finalCompiled;
}
);