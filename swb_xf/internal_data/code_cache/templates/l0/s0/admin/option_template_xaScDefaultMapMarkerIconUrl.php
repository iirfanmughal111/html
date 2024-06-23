<?php
// FROM HASH: d256bacb2ee05b8cde3a3dfa0413833b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formAssetUploadRow(array(
		'name' => $__vars['inputName'],
		'value' => $__vars['option']['option_value'],
		'asset' => 'xa_sc_map_markers',
	), array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);