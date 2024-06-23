<?php
// FROM HASH: 17b3dce07da8a9324180912f5aa1b72d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Showcase: ' . 'Rebuild categories',
		'job' => 'XenAddons\\Showcase:Category',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Showcase: ' . 'Rebuild items',
		'job' => 'XenAddons\\Showcase:Item',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Showcase: ' . 'Rebuild item location data',
		'job' => 'XenAddons\\Showcase:ItemLocationData',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Showcase: ' . 'Rebuild item updates',
		'job' => 'XenAddons\\Showcase:ItemUpdate',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Showcase: ' . 'Rebuild reviews',
		'job' => 'XenAddons\\Showcase:Review',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Showcase: ' . 'Rebuild series',
		'job' => 'XenAddons\\Showcase:Series',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Showcase: ' . 'Rebuild series parts',
		'job' => 'XenAddons\\Showcase:SeriesPart',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'Showcase: ' . 'Rebuild user counts',
		'job' => 'XenAddons\\Showcase:UserItemCount',
	), $__vars) . '
' . '

';
	$__vars['scItemMdBody'] = $__templater->preEscaped('
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
		'header' => 'Showcase: ' . 'Rebuild item embed metadata',
		'body' => $__vars['scItemMdBody'],
		'job' => 'XenAddons\\Showcase:ScItemEmbedMetadata',
	), $__vars) . '
' . '

';
	$__vars['scPageMdBody'] = $__templater->preEscaped('
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
		'header' => 'Showcase: ' . 'Rebuild page embed metadata',
		'body' => $__vars['scPageMdBody'],
		'job' => 'XenAddons\\Showcase:ScItemPageEmbedMetadata',
	), $__vars) . '
' . '

';
	$__vars['scItemUpdateMdBody'] = $__templater->preEscaped('
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
		'header' => 'Showcase: ' . 'Rebuild item update embed metadata',
		'body' => $__vars['scItemUpdateMdBody'],
		'job' => 'XenAddons\\Showcase:ScItemUpdateEmbedMetadata',
	), $__vars) . '
' . '

';
	$__vars['scCommentMdBody'] = $__templater->preEscaped('
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
		'header' => 'Showcase: ' . 'Rebuild comment embed metadata',
		'body' => $__vars['scCommentMdBody'],
		'job' => 'XenAddons\\Showcase:ScCommentEmbedMetadata',
	), $__vars) . '
' . '

';
	$__vars['scReviewMdBody'] = $__templater->preEscaped('
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
		'header' => 'Showcase: ' . 'Rebuild review embed metadata',
		'body' => $__vars['scReviewMdBody'],
		'job' => 'XenAddons\\Showcase:ScReviewEmbedMetadata',
	), $__vars) . '
' . '

';
	$__vars['scSeriesMdBody'] = $__templater->preEscaped('
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
		'header' => 'Showcase: ' . 'Rebuild series embed metadata',
		'body' => $__vars['scSeriesMdBody'],
		'job' => 'XenAddons\\Showcase:ScSeriesEmbedMetadata',
	), $__vars) . '
';
	return $__finalCompiled;
}
);