<?php
// FROM HASH: a0d1c60b8b2555b5223e012cd68992c4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Income statistics');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->filter($__vars['commission']['total_payments'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '
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

';
	if (!$__templater->test($__vars['entries'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				';
		$__compilerTemp2 = '';
		if ($__templater->isTraversable($__vars['entries'])) {
			foreach ($__vars['entries'] AS $__vars['entry']) {
				$__compilerTemp2 .= '
						';
				$__compilerTemp3 = '';
				if ($__vars['entry']['message']) {
					$__compilerTemp3 .= '
											<li>' . $__templater->escape($__vars['entry']['message']) . '</li>
										';
				}
				$__compilerTemp2 .= $__templater->dataRow(array(
				), array(array(
					'label' => '
									' . $__templater->filter($__vars['entry']['payment_amount'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '
								',
					'explain' => '
									<ul class="listInline listInline--bullet">
										<li>' . $__templater->func('date_dynamic', array($__vars['entry']['payment_date'], array(
					'data-full-date' => 'true',
				))) . '</li>
										' . $__compilerTemp3 . '
									</ul>
								',
					'_type' => 'main',
					'html' => '',
				))) . '
					';
			}
		}
		$__finalCompiled .= $__templater->dataList('
					' . $__compilerTemp2 . '
				', array(
		)) . '
			</div>
			<div class="block-footer">
				<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['entries'], $__vars['total'], ), true) . '</span>
			</div>
		</div>
		' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['total'],
			'link' => 'dbtech-ecommerce/authors/income-stats',
			'data' => $__vars['user'],
			'wrapperclass' => 'block-outer block-outer--after',
			'perPage' => $__vars['perPage'],
		))) . '
	</div>
	';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . ($__vars['criteria'] ? 'No records matched.' : 'No entries have been logged.') . '</div>
';
	}
	return $__finalCompiled;
}
);