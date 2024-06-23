<?php
// FROM HASH: 3ca5290cde19fd955d70346d66fbca6c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['statsBody'] = $__templater->preEscaped('
	' . $__templater->formNumberBoxRow(array(
		'name' => 'options[batch]',
		'value' => '28',
		'min' => '1',
	), array(
		'label' => 'Days to process per page',
	)) . '

	' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[delete]',
		'value' => '1',
		'label' => 'Delete existing cached data',
		'_type' => 'option',
	)), array(
	)) . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild daily statistics',
		'body' => $__vars['statsBody'],
		'job' => 'DBTech\\eCommerce:IncomeStats',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild categories',
		'job' => 'DBTech\\eCommerce:Category',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild addresses',
		'job' => 'DBTech\\eCommerce:Address',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild products',
		'job' => 'DBTech\\eCommerce:Product',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild downloads',
		'job' => 'DBTech\\eCommerce:Download',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild licenses',
		'job' => 'DBTech\\eCommerce:License',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild shipping zones',
		'job' => 'DBTech\\eCommerce:ShippingZone',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild user product counts',
		'job' => 'DBTech\\eCommerce:UserProductCount',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild user license counts',
		'job' => 'DBTech\\eCommerce:UserLicenseCount',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild commission counts',
		'job' => 'DBTech\\eCommerce:Commission',
	), $__vars) . '
' . '

' . $__templater->callMacro('tools_rebuild', 'rebuild_job', array(
		'header' => 'DragonByte eCommerce: ' . 'Rebuild amount spent',
		'job' => 'DBTech\\eCommerce:AmountSpent',
	), $__vars) . '
';
	return $__finalCompiled;
}
);