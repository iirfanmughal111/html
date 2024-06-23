<?php
// FROM HASH: 6c807acd531e21bff7478ca970e5149d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Find outstanding payments');
	$__finalCompiled .= '

<div class="block">
	<div class="block-outer">
		' . $__templater->callMacro('filter_macros', 'quick_filter', array(
		'key' => 'dbtech-ecommerce/commissions',
		'class' => 'block-outer-opposite',
	), $__vars) . '
	</div>
	<div class="block-container">
		<div class="block-body">
			';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['commissions'])) {
		foreach ($__vars['commissions'] AS $__vars['commission']) {
			$__compilerTemp1 .= '
					' . $__templater->dataRow(array(
			), array(array(
				'hash' => $__vars['commission']['commission_id'],
				'href' => $__templater->func('link', array('dbtech-ecommerce/commissions/payment', $__vars['commission'], ), false),
				'label' => $__templater->escape($__vars['commission']['name']),
				'hint' => $__templater->escape($__vars['commission']['email']),
				'explain' => '
								<ul class="listInline listInline--bullet">
									<li>' . '' . $__templater->filter($__templater->func('count', array($__vars['commission']['product_commissions'], ), false), array(array('number', array()),), true) . ' commissions' . '</li>
									<li>' . 'Amount owed: ' . $__templater->filter($__vars['amountOwed'][$__vars['commission']['commission_id']], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '' . '</li>
									<li>' . 'Total payments: ' . $__templater->filter($__vars['commission']['total_payments'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '' . '</li>
								</ul>
							',
				'_type' => 'main',
				'html' => '',
			))) . '
				';
		}
	}
	$__finalCompiled .= $__templater->dataList('
				' . $__compilerTemp1 . '
			', array(
	)) . '
		</div>
		<div class="block-footer">
			<span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['commissions'], ), true) . '</span>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);