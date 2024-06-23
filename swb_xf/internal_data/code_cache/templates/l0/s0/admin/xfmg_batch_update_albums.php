<?php
// FROM HASH: 23fe35c81a35fe6faf798807c68ffbf7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Batch update albums');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['criteriaTemplate'] = $__templater->preEscaped('xfmg_helper_albums_search_criteria');
	$__finalCompiled .= $__templater->includeTemplate('xfmg_base_batch_update', $__compilerTemp1);
	return $__finalCompiled;
}
);