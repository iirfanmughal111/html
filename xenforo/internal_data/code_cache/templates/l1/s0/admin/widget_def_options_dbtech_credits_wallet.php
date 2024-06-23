<?php
// FROM HASH: dbee06e2631cedc64326045841927510
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

';
	$__compilerTemp1 = array(array(
		'value' => '',
		'label' => 'All currencies',
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['currencies']);
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'options[currencyIds][]',
		'value' => ($__vars['options']['currencyIds'] ?: ''),
		'multiple' => 'multiple',
		'size' => '7',
	), $__compilerTemp1, array(
		'label' => 'Currency limit',
		'explain' => 'Only the currencies selected here will be included.',
	));
	return $__finalCompiled;
}
);