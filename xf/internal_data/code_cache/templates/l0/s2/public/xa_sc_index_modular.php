<?php
// FROM HASH: 4c6934a4acc5a03c931473b7669726ea
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Items');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped(' ');
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'metadata', array(
		'description' => $__vars['xf']['options']['xaScMetaDescription'],
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase', null, array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), ));
	$__finalCompiled .= '

';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->widgetPosition('xa_sc_modular_index_main', array()) . '

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebar8c6a4490c9ec9615a5e973c71e81d4b8', $__templater->widgetPosition('xa_sc_modular_index_sidebar', array()), 'replace');
	return $__finalCompiled;
}
);