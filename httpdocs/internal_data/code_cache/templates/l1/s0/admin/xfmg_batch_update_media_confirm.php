<?php
// FROM HASH: a5e4b2d381f358f2940843eb1c5bd870
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Batch update media');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['type'] = 'xfmg_media';
	$__finalCompiled .= $__templater->includeTemplate('xfmg_base_batch_update_confirm', $__compilerTemp1);
	return $__finalCompiled;
}
);