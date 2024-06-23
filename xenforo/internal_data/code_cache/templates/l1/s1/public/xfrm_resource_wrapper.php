<?php
// FROM HASH: eb452f38fbd9ac96205de53f7d8c2db1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['noH1'] = true;
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource']['Category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '
';
	$__templater->includeCss('xfrm.less');
	$__finalCompiled .= '

' . $__templater->callMacro('xfrm_resource_page_macros', 'resource_page_options', array(
		'category' => $__vars['resource']['Category'],
		'resource' => $__vars['resource'],
	), $__vars) . '

' . $__templater->callMacro('xfrm_resource_wrapper_macros', 'header', array(
		'resource' => $__vars['resource'],
	), $__vars) . '

' . $__templater->callMacro('xfrm_resource_wrapper_macros', 'tabs', array(
		'resource' => $__vars['resource'],
		'selected' => $__vars['pageSelected'],
	), $__vars) . '

';
	if ($__vars['resource']['prefix_id']) {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= $__templater->func('prefix_description', array('resource', $__vars['resource']['prefix_id'], ), true);
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

' . $__templater->callMacro('xfrm_resource_wrapper_macros', 'status', array(
		'resource' => $__vars['resource'],
	), $__vars) . '

' . $__templater->filter($__vars['innerContent'], array(array('raw', array()),), true);
	return $__finalCompiled;
}
);