<?php
// FROM HASH: e5fe7f432283e035158fffba7cb2db79
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeJs(array(
		'src' => 'xf/payment.js',
	));
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action: Retry payment for Order #' . $__templater->escape($__vars['order']['order_id']) . '');
	$__finalCompiled .= '

' . $__templater->form('

	<div class="block-container">
		<div class="block-body">

			' . $__templater->formInfoRow('
				<p class="block-rowMessage block-rowMessage--error block-rowMessage--iconic">
					<strong>' . 'Warning' . $__vars['xf']['language']['label_separator'] . '</strong>
					' . 'Retrying payment should only be done if payment failed, or if you never completed payment in the first place. If you have already submitted payment, but it has not cleared yet, please do not continue.' . '
				</p>
			', array(
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'retry_purchase',
		'value' => '1',
		'data-xf-init' => 'disabler',
		'data-container' => '.js-submitDisable',
		'label' => 'I understand the above warning, I want to retry payment',
		'_type' => 'option',
	)), array(
		'rowtype' => 'fullWidth noLabel',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'purchase',
	), array(
		'rowtype' => 'simple',
		'rowclass' => 'js-submitDisable',
	)) . '
	</div>

	<div class="js-paymentProviderReply-dbtech_ecommerce_order' . $__templater->escape($__vars['order']['order_id']) . '"></div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/checkout/retry', $__vars['order'], ), false),
		'class' => 'block',
		'ajax' => 'true',
		'data-xf-init' => 'payment-provider-container',
	));
	return $__finalCompiled;
}
);