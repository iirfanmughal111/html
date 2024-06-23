<?php
// FROM HASH: 8f9a8572a4a986070b2167f9e2d3b149
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Batch update media');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['criteriaTemplate'] = $__templater->preEscaped('xfmg_helper_media_search_criteria');
	$__finalCompiled .= $__templater->includeTemplate('xfmg_base_batch_update', $__compilerTemp1);
	return $__finalCompiled;
}
);