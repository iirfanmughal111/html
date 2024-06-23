<?php
// FROM HASH: 12778a8bc1473d8b3d97508580dbd5e4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['noH1'] = true;
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['item']['Category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

';
	if ($__vars['item']['prefix_id']) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= $__templater->func('prefix_description', array('sc_item', $__vars['item']['prefix_id'], ), true);
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
		<div class="blockMessage blockMessage--alt blockMessage--small blockMessage--close">
			' . $__compilerTemp1 . '
		</div>
	';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'status', array(
		'item' => $__vars['item'],
	), $__vars) . '

' . $__templater->callMacro('xa_sc_item_page_macros', 'item_page_options', array(
		'category' => $__vars['item']['Category'],
		'item' => $__vars['item'],
	), $__vars) . '

' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'header', array(
		'item' => $__vars['item'],
	), $__vars) . '

' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'tabs', array(
		'item' => $__vars['item'],
		'selected' => $__vars['pageSelected'],
	), $__vars) . '

' . $__templater->filter($__vars['innerContent'], array(array('raw', array()),), true);
	return $__finalCompiled;
}
);