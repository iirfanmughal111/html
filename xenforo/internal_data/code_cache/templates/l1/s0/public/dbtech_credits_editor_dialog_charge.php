<?php
// FROM HASH: d3200a0a0c27243086684812f2bd5d6d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Charge for content');
	$__finalCompiled .= '

<form class="block" id="editor_dbtech_credits_charge_form">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formNumberBoxRow(array(
		'id' => 'editor_dbtech_credits_charge_title',
		'min' => '0',
		'step' => 'any',
	), array(
		'label' => 'Enter charge amount',
		'explain' => 'This is the amount (in ' . $__templater->escape($__vars['currency']['title']) . ') that other members will have to pay in order to see this content.',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
		'id' => 'editor_dbtech_credits_charge_submit',
	), array(
	)) . '
	</div>
</form>';
	return $__finalCompiled;
}
);