<?php
// FROM HASH: b4da4555047b2ed691f1ac44840aa4a7
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild media items',
		'job' => 'XFMG:MediaItem',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild media thumbnails',
		'job' => 'XFMG:MediaThumb',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild media posters',
		'job' => 'XFMG:MediaPoster',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Update media watermarks',
		'job' => 'XFMG:UpdateWatermark',
	), $__vars) . '
' . '

';
	$__vars['xfmgSyncMirrorBody'] = $__templater->preEscaped('
	' . $__templater->formInfoRow('
		' . 'Running this will update the attachment media mirrors to match the current settings. This may create additional media items, remove ones that are no longer applicable, or move existing ones to a different category.' . '
	', array(
	)) . '
');
	$__finalCompiled .= '
' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Sync attachment media mirroring',
		'body' => $__vars['xfmgSyncMirrorBody'],
		'job' => 'XFMG:SyncAttachmentMirror',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild albums',
		'job' => 'XFMG:Album',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild album thumbnails',
		'job' => 'XFMG:AlbumThumb',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild categories',
		'job' => 'XFMG:Category',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild user counts',
		'job' => 'XFMG:UserCount',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'XFMG: ' . 'Rebuild user media quotas',
		'job' => 'XFMG:UserMediaQuota',
	), $__vars) . '
';
	return $__finalCompiled;
}
);