<?php
// FROM HASH: 6620c3d78fc711794fadf49e75d9c3ba
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit sales tax rate');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->escape($__vars['country']['name']) . '
			', array(
		'label' => 'Country',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'sales_tax_rate',
		'value' => $__vars['country']['sales_tax_rate'],
		'min' => '-1.000',
		'max' => '100.000',
		'step' => 'any',
	), array(
		'label' => 'Sales tax rate',
		'explain' => 'Enter the sales tax rate that should be added to the chosen country. Existing rates will be overwritten. <br />
Enter <code>-1.000</code> to revert to the global rate.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/sales-tax/save', $__vars['country'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);