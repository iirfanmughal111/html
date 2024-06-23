<?php
// FROM HASH: 92df871229f78d5d9560166279345808
return array(
'extends' => function($__templater, array $__vars) { return 'thread_view'; },
'extensions' => array('content_top' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
		$__finalCompiled .= '
	';
	if ($__vars['resource']) {
		$__finalCompiled .= '
		';
		$__vars['originalH1'] = $__templater->preEscaped($__templater->func('page_h1', array('')));
		$__finalCompiled .= '
		';
		$__vars['originalDescription'] = $__templater->preEscaped($__templater->func('page_description'));
		$__finalCompiled .= '

		';
		$__templater->pageParams['noH1'] = true;
		$__finalCompiled .= '
		';
		$__templater->pageParams['pageDescription'] = $__templater->preEscaped('');
		$__templater->pageParams['pageDescriptionMeta'] = true;
		$__finalCompiled .= '

		' . $__templater->callMacro('xfrm_resource_wrapper_macros', 'header', array(
			'resource' => $__vars['resource'],
			'titleHtml' => $__vars['originalH1'],
			'metaHtml' => $__vars['originalDescription'],
		), $__vars) . '

		' . $__templater->callMacro('xfrm_resource_wrapper_macros', 'tabs', array(
			'resource' => $__vars['resource'],
			'selected' => 'discussion',
		), $__vars) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . $__templater->renderExtension('content_top', $__vars, $__extensions);
	return $__finalCompiled;
}
);