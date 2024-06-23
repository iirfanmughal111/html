<?php
// FROM HASH: 9da0e09ec426a7e3539d6694761f430b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Commission payment #' . $__templater->escape($__vars['payment']['commission_payment_id']) . '');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->escape($__vars['payment']['Commission']['name']) . '
				<div class="u-muted">' . $__templater->escape($__vars['payment']['Commission']['email']) . '</div>
			', array(
		'label' => 'Paid to',
	)) . '

			';
	$__compilerTemp1 = '';
	if ($__vars['payment']['user_id']) {
		$__compilerTemp1 .= '
					' . ($__vars['payment']['User'] ? $__templater->escape($__vars['payment']['User']['username']) : 'Unknown user') . '
					';
	} else {
		$__compilerTemp1 .= '
					' . 'Guest' . '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp1 . '
			', array(
		'label' => 'Recorded by',
	)) . '

			' . $__templater->formRow('
				<a href="' . $__templater->func('link_type', array('public', 'misc/ip-info', null, array('ip' => $__templater->filter($__vars['payment']['Ip']['ip'], array(array('ip', array()),), false), ), ), true) . '" target="_blank" class="u-ltr">' . $__templater->filter($__vars['payment']['Ip']['ip'], array(array('ip', array()),), true) . '</a>
			', array(
		'label' => 'IP address',
	)) . '

			' . $__templater->formRow('
				' . $__templater->func('date_dynamic', array($__vars['payment']['payment_date'], array(
		'data-full-date' => 'true',
	))) . '
			', array(
		'label' => 'Date',
	)) . '

			' . $__templater->formRow('
				' . $__templater->filter($__vars['payment']['payment_amount'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '
			', array(
		'label' => 'Payment amount',
	)) . '

			';
	if (!$__templater->test($__vars['payment']['message'], 'empty', array())) {
		$__finalCompiled .= '
				<hr class="formRowSep" />

				' . $__templater->formRow('
					' . $__templater->func('bb_code', array($__vars['payment']['message'], 'help', null, ), true) . '
				', array(
			'label' => 'Message',
		)) . '
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);