<?php
// FROM HASH: 95618ed9e7e2afc024f640dd6bd6265f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search media comments');
	$__finalCompiled .= '

' . $__templater->callMacro('search_form_macros', 'keywords', array(
		'input' => $__vars['input'],
	), $__vars) . '
' . $__templater->callMacro('search_form_macros', 'user', array(
		'input' => $__vars['input'],
	), $__vars) . '
' . $__templater->callMacro('search_form_macros', 'date', array(
		'input' => $__vars['input'],
	), $__vars) . '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['types'])) {
		foreach ($__vars['types'] AS $__vars['type'] => $__vars['label']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['type'],
				'label' => $__templater->escape($__vars['label']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
		'name' => 'c[types][]',
		'multiple' => 'multiple',
		'value' => $__templater->filter($__vars['input']['c']['types'], array(array('default', array(array('xfmg_media', 'xfmg_album', ), )),), false),
	), $__compilerTemp1, array(
		'label' => 'Comments posted on',
	)) . '

' . $__templater->callMacro('search_form_macros', 'order', array(
		'isRelevanceSupported' => $__vars['isRelevanceSupported'],
		'input' => $__vars['input'],
	), $__vars);
	return $__finalCompiled;
}
);