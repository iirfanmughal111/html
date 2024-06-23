<?php
// FROM HASH: cee59b97429c2e85fc7c720e2b684d41
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Record payment' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['commission']['name']));
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->escape($__vars['commission']['name']) . '
				<div class="u-muted">' . $__templater->escape($__vars['commission']['email']) . '</div>
			', array(
		'label' => 'Payment for',
	)) . '

			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/commission-payments', null, array('criteria' => array('commission_id' => $__vars['commission']['commission_id'], ), ), ), true) . '">
					' . $__templater->filter($__vars['commission']['total_payments'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '
				</a>
			', array(
		'label' => 'Payment history',
	)) . '

			' . $__templater->formRow('
				' . ($__vars['commission']['last_paid_date'] ? $__templater->func('date', array($__vars['commission']['last_paid_date'], ), true) : 'Never') . '
			', array(
		'label' => 'Last paid date',
	)) . '

			' . $__templater->formRow('
				' . $__templater->filter($__vars['amountOwed'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '
			', array(
		'label' => 'Amount owed',
	)) . '
		</div>

		';
	if (!$__templater->test($__vars['purchases'], 'empty', array())) {
		$__finalCompiled .= '
			<h3 class="block-formSectionHeader">
				<span class="collapseTrigger collapseTrigger--block" data-xf-click="toggle" data-target="< :up:next">
					<span class="block-formSectionHeader-aligner">' . 'Applicable purchases' . '</span>
				</span>
			</h3>
			<div class="block-body block-body--collapsible">
				';
		if ($__templater->isTraversable($__vars['purchases'])) {
			foreach ($__vars['purchases'] AS $__vars['purchase']) {
				$__finalCompiled .= '
					';
				$__vars['productCommission'] = $__vars['commissions'][$__vars['purchase']['product_id']];
				$__finalCompiled .= '
					';
				$__compilerTemp1 = '';
				if ($__vars['purchase']['Product']) {
					$__compilerTemp1 .= '
							' . $__templater->escape($__vars['purchase']['Product']['title']) . '
							<div class="u-muted">
								<dl class="pairs pairs--columns pairs--fluidSmall">
									<dt>' . 'Type' . '</dt>
									<dd>' . $__templater->escape($__templater->method($__vars['purchase'], 'getLogTypePhrase', array())) . '</dd>
								</dl>
								<dl class="pairs pairs--columns pairs--fluidSmall">
									<dt>' . 'Date' . '</dt>
									<dd>' . $__templater->func('date_time', array($__vars['purchase']['log_date'], ), true) . '</dd>
								</dl>
								<dl class="pairs pairs--columns pairs--fluidSmall">
									<dt>' . 'Price' . '</dt>
									<dd>' . $__templater->filter($__vars['purchase']['log_details']['taxable_price'], array(array('currency', array($__vars['purchase']['currency'], )),), true) . '</dd>
								</dl>
								<dl class="pairs pairs--columns pairs--fluidSmall">
									<dt>' . 'Commission value' . '</dt>
									<dd>' . $__templater->filter($__templater->method($__vars['productCommission'], 'getCommission', array($__vars['purchase'], )), array(array('currency', array($__vars['purchase']['currency'], )),), true) . '</dd>
								</dl>
							</div>
						';
				} else {
					$__compilerTemp1 .= '
							' . 'Unknown product' . '
						';
				}
				$__finalCompiled .= $__templater->formRow('
						' . $__compilerTemp1 . '
					', array(
				)) . '
				';
			}
		}
		$__finalCompiled .= '
			</div>
		';
	}
	$__finalCompiled .= '
	</div>
</div>

' . $__templater->form('
	<div class="block-container">
		<h3 class="block-header">' . 'Record payment' . '</h3>
		<div class="block-body">

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'date',
		'value' => $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false),
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formTextBox(array(
		'type' => 'time',
		'name' => 'time',
		'value' => $__templater->func('date', array($__vars['xf']['time'], 'H:i', ), false),
	)) . '
				</div>
			', array(
		'label' => 'Date',
		'rowtype' => 'input',
	)) . '

			' . $__templater->formNumberBoxRow(array(
		'name' => 'payment_amount',
		'value' => $__vars['amountOwed'],
	), array(
		'label' => 'Payment amount',
	)) . '


			' . $__templater->formEditorRow(array(
		'name' => 'message',
		'previewable' => '0',
	), array(
		'label' => 'Payment notes',
		'explain' => 'Any internal notes that will help clarify discrepancies in payments, etc.',
	)) . '
		</div>

		' . $__templater->formHiddenVal('commission_id', $__vars['commission']['commission_id'], array(
	)) . '
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/commissions/payment', $__vars['commission'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);