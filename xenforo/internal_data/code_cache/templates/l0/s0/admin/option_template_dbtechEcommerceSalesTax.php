<?php
// FROM HASH: 27df4141dd9c61865a77db35c8c195bb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => $__vars['inputName'] . '[enabled]',
		'label' => 'Enable sales tax',
		'value' => '1',
		'selected' => $__vars['option']['option_value']['enabled'],
		'data-hide' => 'true',
		'_dependent' => array('
			<dl class="inputLabelPair">
				<dt><label for="' . $__templater->escape($__vars['inputName']) . '_digital">' . 'Digital' . '</label></dt>
				<dd>
					' . $__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[digital]',
		'value' => $__vars['option']['option_value']['digital'],
		'class' => 'input--inline',
		'id' => $__vars['inputName'] . '_digital',
	), array(array(
		'value' => 'buyer',
		'label' => 'Buyer\'s country',
		'_type' => 'option',
	),
	array(
		'value' => 'seller',
		'label' => 'Seller\'s country',
		'_type' => 'option',
	))) . '
				</dd>
			</dl>
			<dl class="inputLabelPair">
				<dt><label for="' . $__templater->escape($__vars['inputName']) . '_physical">' . 'Physical' . '</label></dt>
				<dd>
					' . $__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[physical]',
		'value' => $__vars['option']['option_value']['physical'],
		'class' => 'input--inline',
		'id' => $__vars['inputName'] . '_physical',
	), array(array(
		'value' => 'buyer',
		'label' => 'Buyer\'s country',
		'_type' => 'option',
	),
	array(
		'value' => 'seller',
		'label' => 'Seller\'s country',
		'_type' => 'option',
	))) . '
				</dd>
			</dl>
			<p class="formRow-explain">' . 'The above settings control how sales tax should be calculated. Buyer\'s country calculates tax based on the tax rate of the country entered during checkout. Seller\'s country calculates tax based on the tax rate of the country you chose in the business settings.' . '</p>
		', $__templater->formCheckBox(array(
	), array(array(
		'name' => '_globalDefault',
		'selected' => $__vars['option']['option_value']['globalDefault'],
		'label' => 'Enable global default sales tax, for this amount' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array('
					<div class="inputGroup">
						' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[globalDefault]',
		'min' => '0.000',
		'step' => 'any',
		'value' => $__vars['option']['option_value']['globalDefault'],
	)) . '
						<span class="inputGroup-text">%</span>
					</div>
					<p class="formRow-explain">' . 'Countries that do not have a sales tax rate explicitly set will use this value.' . '</p>
				'),
		'_type' => 'option',
	),
	array(
		'name' => $__vars['inputName'] . '[includeTax]',
		'selected' => $__vars['option']['option_value']['includeTax'],
		'label' => 'Include sales tax in order total sent to the payment processor ',
		'hint' => 'If your payment processor handles sales tax on their end (such as PayPal), you may wish to let it handle adding any tax. This may be required to import your transactions into accounting software such as QuickBooks.',
		'_type' => 'option',
	),
	array(
		'name' => $__vars['inputName'] . '[enableVat]',
		'selected' => $__vars['option']['option_value']['enableVat'],
		'label' => 'Enable VAT',
		'hint' => 'If you need to charge VAT on purchases from EU countries, you can enable it here.<br />
VAT rates will be kept up to date automatically.',
		'_dependent' => array('					
					' . $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[enhancedVatValidation]',
		'selected' => $__vars['option']['option_value']['enhancedVatValidation'],
		'label' => 'Enhanced validation',
		'hint' => 'If this option is enabled, the user\'s current IP address will be compared to the country they chose in their billing address.<br />
The VAT number will be rejected if the countries do not match. This may result in false positives and reduced performance when validating VAT numbers.',
		'_type' => 'option',
	))) . '
				'),
		'_type' => 'option',
	)))),
		'_type' => 'option',
	)), array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);