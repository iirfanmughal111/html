<?php
// FROM HASH: f0aeeb8bc616a3a178dc2bf83b9f9573
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Rebuild caches');
	$__finalCompiled .= '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Categories',
		'body' => '',
		'job' => 'Truonglv\\Groups\\Job\\CategoryRebuild',
	), $__vars) . '

';
	$__vars['groupBodyHtml'] = $__templater->preEscaped('
    ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[counter]',
		'selected' => 1,
		'label' => 'Rebuild group counters',
		'_type' => 'option',
	)), array(
	)) . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Groups',
		'body' => $__vars['groupBodyHtml'],
		'job' => 'Truonglv\\Groups\\Job\\GroupRebuild',
	), $__vars) . '

';
	$__vars['memberBodyHtml'] = $__templater->preEscaped('
    ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[remove_deleted]',
		'label' => 'Remove deleted users',
		'hint' => 'If checked, users deleted from XenForo system will be removed from the group.',
		'selected' => 1,
		'_type' => 'option',
	),
	array(
		'name' => 'options[remove_banned]',
		'label' => 'Remove banned users',
		'hint' => 'If checked, users banned from XenForo system will be removed from the group.',
		'selected' => 1,
		'_type' => 'option',
	)), array(
	)) . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Rebuild post metadata',
		'body' => '',
		'job' => 'Truonglv\\Groups\\Job\\PostRebuild',
	), $__vars) . '
' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Rebuild comment metadata',
		'body' => '',
		'job' => 'Truonglv\\Groups\\Job\\CommentRebuild',
	), $__vars) . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Members',
		'body' => $__vars['memberBodyHtml'],
		'job' => 'Truonglv\\Groups\\Job\\MemberRebuild',
	), $__vars) . '
' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Rebuild user caches',
		'body' => '',
		'job' => 'Truonglv\\Groups\\Job\\RebuildUserCache',
	), $__vars) . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Events',
		'body' => '',
		'job' => 'Truonglv\\Groups\\Job\\EventRebuild',
	), $__vars);
	return $__finalCompiled;
}
);