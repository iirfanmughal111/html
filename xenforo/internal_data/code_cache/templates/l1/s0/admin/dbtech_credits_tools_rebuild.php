<?php
// FROM HASH: 9e0e5ff9a8ffa18052e749e64b18c3ae
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'All' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\Credits:EventTrigger', )), 'getRebuildableEventTriggerPairs', array()));
	$__vars['rebuildBody'] = $__templater->preEscaped('
	' . $__templater->formSelectRow(array(
		'name' => 'options[type]',
	), $__compilerTemp1, array(
		'label' => 'Content type',
	)) . '

	' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[truncate]',
		'label' => 'Delete all transaction data before rebuilding',
		'_dependent' => array('
				' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'options[reset]',
		'label' => 'Also reset all currencies to 0',
		'_type' => 'option',
	))) . '
			'),
		'_type' => 'option',
	)), array(
	)) . '

	' . $__templater->formNumberBoxRow(array(
		'name' => 'options[batch]',
		'value' => '500',
		'min' => '1',
	), array(
		'label' => 'Items to process per page',
	)) . '
');
	$__finalCompiled .= '
' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte Credits: ' . 'Rebuild transactions',
		'body' => $__vars['rebuildBody'],
		'job' => 'DBTech\\Credits:TransactionRebuild',
	), $__vars) . '
' . '


';
	$__vars['rebuildBody'] = $__templater->preEscaped('
	' . $__templater->formInfoRow('This will rebuild users\' balances based on the latest available balance in their transaction log. If you have 3rd party add-ons that interface with users\' currency columns, running this will undo those changes.', array(
	)) . '
');
	$__finalCompiled .= '
' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte Credits: ' . 'Rebuild balances',
		'body' => $__vars['rebuildBody'],
		'job' => 'DBTech\\Credits:BalanceRebuild',
	), $__vars) . '
' . '

';
	if ($__vars['xf']['development']) {
		$__finalCompiled .= '
	' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
			'header' => 'DragonByte Credits: Daily Credits',
			'job' => 'DBTech\\Credits:DailyCredits',
		), $__vars) . '
';
	}
	return $__finalCompiled;
}
);