<?php
// FROM HASH: ebf153352899c268a284d68dcdff7410
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Batch update albums');
	$__finalCompiled .= '

' . $__templater->includeTemplate('xfmg_base_batch_update_confirm', $__vars);
	return $__finalCompiled;
}
);