<?php
// FROM HASH: f882fbb7a67735605999e382361b9a8c
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
					' . 'Cancelling this order will also delete any associated licenses.' . '
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
		'action' => $__templater->func('link', array('dbtech-ecommerce/orders/cancel', $__vars['order'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);