<?php
// FROM HASH: e1068a1345f853c54fa7516027607bca
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
	<div class="block-container">
		<h3 class="block-header">' . ($__templater->escape($__vars['title']) ?: 'Categories') . '</h3>		
		<div class="block-body">
			';
	if ($__templater->method($__vars['categoryTree'], 'count', array())) {
		$__finalCompiled .= '
				' . $__templater->callMacro('xa_sc_category_list_macros', 'simple_category_list', array(
			'children' => $__vars['categoryTree'],
			'extras' => $__vars['categoryExtras'],
			'isActive' => true,
			'selected' => $__vars['selected'],
			'pathToSelected' => ($__vars['selected'] ? $__templater->method($__vars['categoryTree'], 'getPathTo', array($__vars['selected'], )) : array()),
		), $__vars) . '
				';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'N/A' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);