<?php
// FROM HASH: 9294fcfe25c9961c54d8593f3fc28fbd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
		';
	$__compilerTemp2 = $__templater->method($__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\Credits:Currency', )), 'findCurrenciesForList', array()), 'fetch', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['currencyId'] => $__vars['currency']) {
			if ($__vars['currency']['active']) {
				$__compilerTemp1 .= '
			' . $__templater->formRow('

				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
					'name' => 'criteria[' . $__vars['currency']['column'] . '][start]',
					'value' => $__vars['criteria'][$__vars['currency']['column']]['start'],
					'step' => 'any',
					'size' => '15',
					'readonly' => $__vars['readOnly'],
				)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
					'name' => 'criteria[' . $__vars['currency']['column'] . '][end]',
					'value' => $__vars['criteria'][$__vars['currency']['column']]['end'],
					'step' => 'any',
					'size' => '15',
					'readonly' => $__vars['readOnly'],
				)) . '
				</div>
			', array(
					'rowtype' => 'input',
					'label' => '' . $__templater->escape($__vars['currency']['title']) . ' amount is between',
				)) . '
		';
			}
		}
	}
	$__compilerTemp1 .= '
	';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	' . $__compilerTemp1 . '
	
	<hr class="formRowSep" />
';
	}
	return $__finalCompiled;
}
);