<?php
// FROM HASH: 56c70b9e3b34521063e824b16fc84f0e
return array(
'macros' => array('payment_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'order' => '!',
		'profiles' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'xf/payment.js',
	));
	$__finalCompiled .= '
	
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
	$__compilerTemp1 = '';
	if (($__templater->func('count', array($__vars['xf']['options']['dbtechEcommercePaymentProfileIds'], ), false) > 1)) {
		$__compilerTemp1 .= '
								';
		$__compilerTemp2 = array(array(
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'Choose a payment method' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		));
		if ($__templater->isTraversable($__vars['xf']['options']['dbtechEcommercePaymentProfileIds'])) {
			foreach ($__vars['xf']['options']['dbtechEcommercePaymentProfileIds'] AS $__vars['profileId']) {
				$__compilerTemp2[] = array(
					'value' => $__vars['profileId'],
					'label' => $__templater->escape($__vars['profiles'][$__vars['profileId']]),
					'_type' => 'option',
				);
			}
		}
		$__compilerTemp1 .= $__templater->formSelect(array(
			'name' => 'payment_profile_id',
		), $__compilerTemp2) . '

								<span class="inputGroup-splitter"></span>

								' . $__templater->button('', array(
			'type' => 'submit',
			'icon' => 'purchase',
		), '', array(
		)) . '
							';
	} else {
		$__compilerTemp1 .= '
								' . $__templater->button('', array(
			'type' => 'submit',
			'icon' => 'purchase',
		), '', array(
		)) . '

								' . $__templater->formHiddenVal('payment_profile_id', '
									' . $__templater->filter($__vars['xf']['options']['dbtechEcommercePaymentProfileIds'], array(array('first', array()),), false) . '
								', array(
		)) . '
							';
	}
	$__finalCompiled .= $__templater->form('

					' . $__templater->formRow('

						<div class="inputGroup">

							' . $__compilerTemp1 . '
						</div>
					', array(
		'rowtype' => 'button',
		'label' => 'Order #' . $__templater->escape($__vars['order']['order_id']) . '',
	)) . '
				', array(
		'action' => $__templater->func('link', array('purchase', array('purchasable_type_id' => 'dbtech_ecommerce_order', ), array('order_id' => $__vars['order']['order_id'], ), ), false),
		'ajax' => 'true',
		'data-xf-init' => 'payment-provider-container',
	)) . '
				<div class="js-paymentProviderReply-dbtech_ecommerce_order' . $__templater->escape($__vars['order']['order_id']) . '"></div>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);