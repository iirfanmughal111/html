<?php
// FROM HASH: 3228a35603234d9546bd5883c6133c42
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				<p class="block-rowMessage block-rowMessage--error block-rowMessage--iconic">
					<strong>' . 'Warning' . $__vars['xf']['language']['label_separator'] . '</strong>
					' . 'Deleting this order will prevent payment from being taken.' . '
				</p>
				<p>
					' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'delete_licenses',
		'data-xf-init' => 'disabler',
		'data-container' => '.js-submitDisable',
		'label' => 'I have read the above warning, cancel the order.',
		'_type' => 'option',
	))) . '
				</p>
			', array(
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
		'rowclass' => 'js-submitDisable',
	)) . '
	</div>

', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/checkout/cancel', $__vars['order'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);