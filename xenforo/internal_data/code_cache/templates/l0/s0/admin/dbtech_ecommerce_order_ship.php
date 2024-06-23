<?php
// FROM HASH: d8d46b9dc00104b1080bbe498393acbf
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
				' . 'Please confirm that you want to ship the following' . $__vars['xf']['language']['label_separator'] . '
				<strong><a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/order', $__vars['order'], ), true) . '">
					' . 'Order #' . $__templater->escape($__vars['order']['order_id']) . '' . '
				</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '

			' . $__templater->callMacro('public:dbtech_ecommerce_helper_action', 'customer_alert', array(
		'selected' => true,
	), $__vars) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>

', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/orders/ship', $__vars['order'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);