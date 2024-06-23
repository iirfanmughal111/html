<?php
// FROM HASH: dd79ebe9ec70eafc6a86f7473a518b93
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add sales tax rate');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['countries']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formSelectRow(array(
		'name' => 'country_code',
	), $__compilerTemp1, array(
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'sales_tax_rate',
		'value' => '-1.000',
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
		'action' => $__templater->func('link', array('dbtech-ecommerce/sales-tax/save', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);