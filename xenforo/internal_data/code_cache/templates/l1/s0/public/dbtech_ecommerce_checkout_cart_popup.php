<?php
// FROM HASH: 75c0d2b00db786e4ab6d751f1c0e3c4d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['orderItems'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="menu-scroller">
		<ol class="listPlain">
			';
		if ($__templater->isTraversable($__vars['orderItems'])) {
			foreach ($__vars['orderItems'] AS $__vars['item']) {
				$__finalCompiled .= '
				<li class="menu-row menu-row--separated menu-row--clickable">
					<div class="fauxBlockLink">
						' . $__templater->callMacro(null, 'dbtech_ecommerce_cart_macros::row', array(
					'item' => $__vars['item'],
				), $__vars) . '
					</div>
				</li>
			';
			}
		}
		$__finalCompiled .= '
		</ol>
	</div>
	<div class="menu-footer menu-footer--split">
		<span class="menu-footer-main">
			' . 'Order total' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['order']['order_total'], array(array('currency', array($__vars['xf']['options']['dbtechEcommerceCurrency'], )),), true) . '
		</span>
		<span class="menu-footer-opposite">
			<a href="' . $__templater->func('link', array('dbtech-ecommerce/checkout', ), true) . '">' . 'Checkout' . '</a>
		</span>
	</div>	
';
	} else {
		$__finalCompiled .= '
	<div class="menu-row">' . 'You have no items in your cart.' . '</div>
';
	}
	return $__finalCompiled;
}
);