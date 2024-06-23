<?php
// FROM HASH: 5f18ac155006a065952400a7b7ee1260
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
				<p class="block-rowMessage block-rowMessage--warning block-rowMessage--iconic">
					<strong>' . 'Warning' . $__vars['xf']['language']['label_separator'] . '</strong>
					' . 'Restoring this order will also restore any associated licenses.' . '
				</p>
				<p>
					' . $__templater->formCheckBox(array(
	), array(array(
		'name' => 'restore_licenses',
		'data-xf-init' => 'disabler',
		'data-container' => '.js-submitDisable',
		'label' => 'I have read the above warning, restore the order.',
		'_type' => 'option',
	))) . '
				</p>
			', array(
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
		'rowclass' => 'js-submitDisable',
	)) . '
	</div>

', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/orders/complete', $__vars['order'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);