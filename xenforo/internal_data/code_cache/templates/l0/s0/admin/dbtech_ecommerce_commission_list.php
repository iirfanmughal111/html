<?php
// FROM HASH: 60caa5f0f269b583754ffb559c658e96
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Commissions');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped('Commissions allow you to keep track of payments owed to employees or sub-contractors that receive a portion of sales for products.');
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('Find outstanding payments', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/commissions/find-payments', ), false),
		'icon' => 'payment',
	), '', array(
	)) . '
	' . $__templater->button('Add commission', array(
		'href' => $__templater->func('link', array('dbtech-ecommerce/commissions/add', ), false),
		'icon' => 'add',
	), '', array(
	)) . '
');
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
				'href' => $__templater->func('link', array('dbtech-ecommerce/commissions/edit', $__vars['commission'], ), false),
				'label' => $__templater->escape($__vars['commission']['name']),
				'hint' => $__templater->escape($__vars['commission']['email']),
				'explain' => '
								<ul class="listInline listInline--bullet">
									<li>' . '' . $__templater->filter($__templater->func('count', array($__vars['commission']['product_commissions'], ), false), array(array('number', array()),), true) . ' commissions' . '</li>
									<li>' . 'Total payments: ' . $__templater->filter($__vars['commission']['total_payments'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '' . '</li>
								</ul>
							',
				'_type' => 'main',
				'html' => '',
			),
			array(
				'class' => 'dataList-cell--action',
				'label' => 'Manage' . $__vars['xf']['language']['ellipsis'],
				'_type' => 'popup',
				'html' => '

							<div class="menu" data-menu="menu" aria-hidden="true">
								<div class="menu-content">
									<h3 class="menu-header">' . 'Manage' . $__vars['xf']['language']['ellipsis'] . '</h3>
									<a href="' . $__templater->func('link', array('dbtech-ecommerce/commissions/payment', $__vars['commission'], ), true) . '" class="menu-linkRow">' . 'Record payment' . '</a>
									<a href="' . $__templater->func('link', array('dbtech-ecommerce/logs/commission-payments', null, array('criteria' => array('commission_id' => $__vars['commission']['commission_id'], ), ), ), true) . '" class="menu-linkRow">' . 'Payment history' . '</a>
								</div>
							</div>
						',
			),
			array(
				'href' => $__templater->func('link', array('dbtech-ecommerce/commissions/delete', $__vars['commission'], ), false),
				'tooltip' => 'Delete' . ' ',
				'_type' => 'delete',
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