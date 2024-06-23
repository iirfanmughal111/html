<?php
// FROM HASH: 12bc98c72004e66292e5d706fc0e2b76
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFRM: ' . 'Rebuild resources',
		'job' => 'XFRM:ResourceItem',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFRM: ' . 'Rebuild resource categories',
		'job' => 'XFRM:Category',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFRM: ' . 'Rebuild user resource counts',
		'job' => 'XFRM:UserResourceCount',
	), $__vars) . '
' . '

';
	$__vars['mdBody'] = $__templater->preEscaped('
	' . $__templater->formCheckBoxRow(array(
		'name' => 'options[types]',
		'listclass' => 'listColumns',
	), array(array(
		'value' => 'attachments',
		'label' => 'Attachments',
		'selected' => true,
		'_type' => 'option',
	)), array(
	)) . '
');
	$__finalCompiled .= '
' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFRM: ' . 'Rebuild resource embed metadata',
		'body' => $__vars['mdBody'],
		'job' => 'XFRM:ResourceUpdateEmbedMetadata',
	), $__vars) . '
';
	return $__finalCompiled;
}
);